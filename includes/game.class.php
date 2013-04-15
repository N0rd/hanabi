<?php

Class Game {
  public $id;
  public $name;
  public $status;
  public $variant;
  public $colors;
  public $handsize;
  public $playersnum;
  public $players;
  public $currentplayer;
  public $lives;
  public $maxhints;
  public $hints;
  public $deck;
  public $builtpiles;
  public $discard;
  
  public function __construct($id = null) {
    if($id) {
      $this->loadFromDB($id);
    } else {
      //test data, later filled in lobby
      $this->id = null;
      $this->name = 'Test Game';
      $this->status = 1;
      $this->playersnum = 0;
      $this->currentplayer = 0;
      $this->addplayer(array('name' => 'Player 1', 'id' => 4));
      $this->addplayer(array('name' => 'Player 2', 'id' => 5));
      $this->addplayer(array('name' => 'Player 3', 'id' => 16));
  	  $this->variant = 'normal';
      $this->colors = Deck::getColorsByVariant($this->variant);
      $this->getMaxHints();
      $this->hints = $this->maxhints;
    }
  }
  
  public function start() {
    $this->deck = new Deck(null, $this->variant);
    $this->lives = $this->getMaxLives();
    $this->discard = array();
    
    foreach($this->colors as $cid => $c) {
      $this->builtpiles[$cid] = 0;
    }
    // játékoslétszámtól függ a kézben tartott lapok száma
    if ($this->playersnum == 2 or $this->playersnum == 3) {
      $this->handsize = 5;
    } elseif ($this->playersnum == 4 or $this->playersnum == 5) {
      $this->handsize = 4;
    }
    foreach($this->players as $p) {
      for($i = 0; $i < $this->handsize; $i++) {
        $p->draw();
      }
    }
  }
  
  public function addplayer($player, $playerplace = null) {
    if($playerplace == null) {
      $playerplace = count($this->players);
    }
    $this->players[] = new Player($this, $player['id'], $player['name'], $playerplace);
    $this->playersnum += 1;
  }
  
  public function saveToDB() {
    $deck = json_encode($this->deck->cards);
    $builtpiles = json_encode($this->builtpiles);
    $discard = json_encode($this->discard);
    if($this->id) {
      $insert = false;
      $query = DB::$db->prepare('UPDATE games SET name = :name, status = :status, variant = :variant, handsize = :handsize, playersnum = :playersnum, currentplayer = :currentplayer, lives = :lives, hints = :hints, deck = :deck, builtpiles = :builtpiles, discard = :discard WHERE id = :id');
      $query->bindParam(':id', $this->id);
    } else {
      $insert = true;
      $query = DB::$db->prepare('INSERT INTO games SET name = :name, status = :status, variant = :variant, handsize = :handsize, playersnum = :playersnum, currentplayer = :currentplayer, lives = :lives, hints = :hints, deck = :deck, builtpiles = :builtpiles, discard = :discard, created = NOW()');
    }
    $query->bindParam(':name', $this->name);
    $query->bindParam(':status', $this->status);
    $query->bindParam(':variant', $this->variant);
    $query->bindParam(':handsize', $this->handsize);
    $query->bindParam(':playersnum', $this->playersnum);
    $query->bindParam(':currentplayer', $this->currentplayer);
    $query->bindParam(':lives', $this->lives);
    $query->bindParam(':hints', $this->hints);
    $query->bindParam(':deck', $deck);
    $query->bindParam(':builtpiles', $builtpiles);
    $query->bindParam(':discard', $discard);
    $query->execute();
    if($insert) {
      $this->id = DB::$db->lastInsertId();
    }
    foreach($this->players as $p) {
      $p->saveToDb($insert);
    }
  }

  public function loadFromDB($id) {
    $query = DB::$db->prepare('SELECT * FROM games WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    if($game = $query->fetch()) {
      $this->id = $game['id'];
      $this->name = $game['name'];
      $this->status = $game['status'];
      $this->variant = $game['variant'];
      $this->handsize = $game['handsize'];
      $this->playersnum = $game['playersnum'];
      $this->currentplayer = $game['currentplayer'];
      $this->lives = $game['lives'];
      $this->hints = $game['hints'];
      $deck = json_decode($game['deck']);
      $this->deck = new Deck($deck);
      $this->builtpiles = get_object_vars(json_decode($game['builtpiles']));
      $this->discard = json_decode($game['discard']);
      $this->colors = Deck::getColorsByVariant($this->variant);
      $this->getMaxHints();
      $query = DB::$db->prepare('SELECT * FROM game_player WHERE gameid = :id ORDER BY `order`');
      $query->bindParam(':id', $id);
      $query->execute();
      while($p = $query->fetch()) {
        $hand = json_decode($p['hand']);
        //later, name will be loaded from users table
        $name = 'Player '.$p['playerid'];
        $current = ($p['order'] == $this->currentplayer);
        $this->players[] = new Player($this, $p['playerid'], $name, $p['order'], $hand, $current);
      }
      return true;
    } else {
      return false;
    }
  }
  
  public function action($action, $param1 = null, $param2 = null) {
    if($_SESSION['user']['id'] != $this->players[$this->currentplayer]->id) {
      //disabled for initial testing
      //return false;
    }
    $this->players[$this->currentplayer]->action($action, $param1, $param2);
    $this->nextPlayer();
  }
  
  public function addToDiscard($card) {
    $color = Deck::getCardColor($card);
    $this->discard[$color][] = $card;
    sort($this->discard[$color]);
  }
  
  public function buildPile($card) {
    $cid = Deck::getCardColor($card);
    $number = Deck::getCardNumber($card);
    if($this->builtpiles[$cid] == $number-1) {
      $this->builtpiles[$cid] = $number;
      if($number == Deck::getMaxNumber()) {
        $this->increaseHints();
      }
      return true;
    } else {
      $this->addToDiscard($card);
      $this->loseLife();
      return false;
    }
  }
  
  public function loseLife() {
    $this->lives--;
    if($this->lives < 1) {
      //Game Over
    }
  }
  
  public function nextPlayer() {
    $this->currentplayer++;
    if($this->currentplayer >= $this->playersnum) {
      $this->currentplayer = 0;
    }
  }
  
  public function getMaxHints() {
    //a játékvarianstól függ a sugások száma, sőt extra könnyítésként növelhető is szükség szerint.
    if ($this->variant == 'normal') {
      $this->maxhints = 8;	
    } elseif ($this->variant == 'hard' or $this->variant == 'harder' or $this->variant == 'anti') {
      $this->maxhints = 9;
    } elseif ($this->variant == 'easy') {
      $this->maxhints = 12;
    }
  }

  public function getMaxLives() {
    return 3;
  }
  
  public function increaseHints() {
    if($this->hints < $this->maxhints) {
      $this->hints++;
    }
  }
  
  public function decreaseHints() {
    if($this->hints > 0) {
      $this->hints--;
      return true;
    }
    return false;
  }
}