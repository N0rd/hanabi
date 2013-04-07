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
    }
  }

  public function start() {
    $this->deck = new Deck(null, $this->variant);
    $this->lives = 3;
    //a játékvarianstól függ a sugások száma, sőt extra könnyítésként növelhető is szükség szerint.
    if ($this->variant == 'normal') {
      $this->hints = 8;	
    } elseif ($this->variant == 'hard' or $this->variant == 'harder' or $this->variant == 'anti') {
      $this->hints = 9;
    } elseif ($this->variant == 'easy') {
      $this->hints = 12;
    }
    $this->discard = array();
    $this->builtpiles = array();
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
  
  public function addplayer($player) {
    //later loaded from user data
    $this->players[] = new Player($this, $player['id'], $player['name']);
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
    $order = 0;
    foreach($this->players as $p) {
      $order++;
      $p->saveToDb($insert, $order);
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
      $this->builtpiles = json_decode($game['builtpiles']);
      $this->discard = json_decode($game['discard']);
      $this->colors = Deck::getColorsByVariant($this->variant);
      $query = DB::$db->prepare('SELECT * FROM game_player WHERE gameid = :id ORDER BY `order`');
      $query->bindParam(':id', $id);
      $query->execute();
      while($p = $query->fetch()) {
        $hand = json_decode($p['hand']);
        //later, name will be loaded from users table
        $name = 'Player '.$p['playerid'];
        $current = ($p['order'] == $this->currentplayer);
        $this->players[] = new Player($this, $p['playerid'], $name, $hand, $current);
      }
      return true;
    } else {
      return false;
    }
  }
  
  public function addToDiscard($card) {
    $this->discard[] = $card;
  }
}