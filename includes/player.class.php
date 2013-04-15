<?php

Class Player {
  private $game;
  public $id; 
  public $hand;
  public $name;
  public $playerplace;
  public $current;
  
  public function __construct($game, $id, $name, $playerplace, $hand = array(), $current = false) {
    $this->game = $game;
    $this->id = $id;
    $this->hand = $hand;
    $this->name = $name;
    $this->playerplace = $playerplace;
    $this->current = $current;
  }
  
  public function saveToDb($insert) {
    if($insert) {
      $query = DB::$db->prepare('INSERT INTO game_player(gameid, playerid, `order`, hand)
                                 VALUES (:gameid, :playerid, :order, :hand)');
      $query->bindParam(':order', $this->playerplace);
    } else {
      //this might require tweaking later, when there will be a lobby with open games (possibly, players added or removed on update)
      $query = DB::$db->prepare('UPDATE game_player SET hand = :hand WHERE gameid = :gameid AND playerid = :playerid');
    }
    $query->bindParam(':gameid', $this->game->id);
    $query->bindParam(':playerid', $this->id);
    $hand = json_encode($this->hand);
    $query->bindParam(':hand', $hand);
    $query->execute();
  }
  
  public function draw() {
    if(count($this->hand) < $this->game->handsize) {
      $this->hand[] = $this->game->deck->draw();
      $this->reorder();
      
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
    $this->draw();
    $this->game->increaseHints();
    return array('success' => true, 'refresh' => array('ownhand', 'discard', 'hints', /*debug*/'players'));
  }
    
  private function build($cardplace) {
    $card = $this->hand[$cardplace];
    unset($this->hand[$cardplace]);
    $this->draw();
    $success = $this->game->buildPile($card);
    if($success) {
      return array('success' => $success, 'refresh' => array('ownhand', 'discard', 'lives', /*debug*/'players'));
    } else {
      return array('success' => $success, 'refresh' => array('ownhand', 'fireworks', 'lives', /*debug*/'players'));
    }
  }
  
  private function hint($playerplace, $hint) {
    if($playerplace != $this->playerplace) {
      if($this->game->decreaseHints()) {
        $this->game->players[$playerplace]->receiveHint($hint);
        return array('success' => true, 'refresh' => array('hints'));
      }
    }
    return array('success' => false);
  }
  
  public function receiveHint($hint) {
    //later
  }
  
}
