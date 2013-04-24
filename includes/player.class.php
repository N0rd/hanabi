<?php

Class Player {
  private $game;
  public $id; 
  public $hand;
  public $info;
  public $name;
  public $playerplace;
  public $current;
  
  public function __construct($game, $id, $playerplace = null, $hand = array(), $info = array(), $current = false) {
    $this->id = $id;
    if(is_object($game)) {
      $this->game = $game;
      $this->hand = $hand;
      $this->info = $info;
      $this->playerplace = $playerplace;
      $this->current = $current;
    } else {
      $this->game = new Game();
      $this->game->id = $game;
      $this->loadFromDb();
    }
  }
  
  //this will be cached in session later
  public static function getUserName($id) {
    $query = DB::$db->prepare('SELECT name FROM users WHERE id = :id');
    $query->bindParam(':id', $id);
    if($name = $query->fetch()) {
      return $name['name'];
    }
    return '';
  }
  
  public function saveToDb($insert = false) {
    if($insert) {
      $query = DB::$db->prepare('INSERT INTO game_player(gameid, playerid, `order`, hand, info)
                                 VALUES (:gameid, :playerid, :order, :hand, :info)');
      $query->bindParam(':order', $this->playerplace);
    } else {
      //this might require tweaking later, when there will be a lobby with open games (possibly, players added or removed on update)
      $query = DB::$db->prepare('UPDATE game_player SET hand = :hand, info = :info WHERE gameid = :gameid AND playerid = :playerid');
    }
    $query->bindParam(':gameid', $this->game->id);
    $query->bindParam(':playerid', $this->id);
    $hand = json_encode($this->hand);
    $query->bindParam(':hand', $hand);
    $info = json_encode($this->info);
    $query->bindParam(':info', $info);
    $query->execute();
  }
  
  public function loadFromDb() {
    $query = DB::$db->prepare('SELECT hand, info FROM game_player WHERE gameid = :gameid AND playerid = :playerid');
    $query->bindParam(':gameid', $this->game->id);
    $query->bindParam(':playerid', $this->id);
    $query->execute();
    if($player = $query->fetch()) {
      $this->hand = json_decode($player['hand']);
      $this->info = Player::decodeInfo($player['info']);
      return true;
    } else {
      return false;
    }
  }
  
  public static function decodeInfo($info) {
    $info = json_decode($info);
    foreach($info as &$i) {
      $i = get_object_vars($i);
    }
    return $info;
  }
  
  public function setInfo($card, $info) {
    if(isset($this->info[$card][$info])) {
      $current = $this->info[$card][$info];
    } else {
      $current = 'unk';
    }
    if($current == 'yes' || $current == 'not') {
      return false;
    } else {
      if($current == 'unk') {
        $this->info[$card][$info] = 'thn';
      } else if($current == 'thn') {
        $this->info[$card][$info] = 'thy';
      } else {
        $this->info[$card][$info] = 'unk';
      }
      return $this->info[$card][$info];
    }
  }
  
  public function draw($initial = false) {
    if(count($this->hand) < $this->game->handsize) {
      $card = $this->game->deck->draw();
      $this->hand[] = $card;
      $this->reorder();
      if(!$initial) {
        $this->game->log(array('event' => 'draw', 'player' => $this->id, 'card' => $card));
      }
      return true;
    }
    return false;
  }

  //rearrange the hand indexes to 0..<handsize>  
  private function reorder() {
    $new = array();
    $key = 0;
    foreach($this->hand as $c) {
      $new[$key] = $c;
      $key++;
    }
    $this->hand = $new;
  }
  
  public function action($action, $param1, $param2) {
    if($action == 'discard') {
      return $this->discard($param1);
    }
    if($action == 'build') {
      return $this->build($param1);
    }
    if($action == 'hint') {
      return $this->hint($param1, $param2);
    }
    return false;
  }
  
  private function discard($cardplace) {
    $card = $this->hand[$cardplace];
    unset($this->hand[$cardplace]);
    $this->game->addToDiscard($card);
    $this->game->log(array('event' => 'discard', 'player' => $this->id, 'card' => $card));
    $this->draw();
    $this->game->increaseHints();
    return array('success' => true, 'refresh' => array('ownhand', 'discard', 'hints', 'deckbox', /*debug*/'players'));
  }
    
  private function build($cardplace) {
    $card = $this->hand[$cardplace];
    unset($this->hand[$cardplace]);
    $success = $this->game->buildPile($card);
    if($success) {
      $this->game->log(array('event' => 'firesuccess', 'player' => $this->id, 'card' => $card));
      $output = array('success' => $success, 'refresh' => array('ownhand', 'fireworks', 'lives', 'deckbox', /*debug*/'players'));
    } else {
      $this->game->log(array('event' => 'firefail', 'player' => $this->id, 'card' => $card));
      $output = array('success' => $success, 'refresh' => array('ownhand', 'discard', 'lives', 'deckbox', /*debug*/'players'));
    }
    $this->draw();
    return $output;
  }
  
  private function hint($playerplace, $hint) {
    if($playerplace != $this->playerplace) {
      if($this->game->decreaseHints()) {
        $this->game->players[$playerplace]->receiveHint($this, $hint);
        return array('success' => true, 'refresh' => array('hints'));
      }
    }
    return array('success' => false);
  }
  
  public function receiveHint($source, $hint) {
    $match = array();
    foreach($this->hand as $place => $card) {
      if(is_numeric($hint)) {
        if($hint == Deck::getCardNumber($card)) {
          $match[] = $place;
          $this->info[$place][$hint] = 'yes';
        } else {
          $this->info[$place][$hint] = 'not';
        }
      } else {
        if($hint == Deck::getCardColor($card)) {
          $match[] = $place;
          $this->info[$place][$hint] = 'yes';
        } else {
          $this->info[$place][$hint] = 'not';
        }
      }
    }
    $this->game->log(array('event' => 'hint', 'player' => $source->id, 'receiver' => $this->id, 'match' => $match, 'hint' => $hint));
  }
}
