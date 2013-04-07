<?php

Class Game {
  public $id;
  public $name;
  public $status;
  public $variant;
  public $handsize;
  public $playersnum;
  public $players;
  public $lives;
  public $hints;
  public $deck;
  public $discard;
  public function __construct($id = null) {
    if($id) {
      $this->loadFromDB($id);
    } else {
      //test data, later filled in lobby
      $this->id = null;
      $this->name = 'Test Game';
      $this->status = 1;
      $this->playersnum = '0';
      $this->addplayer(array('name' => 'Player 1', 'id' => 4));
      $this->addplayer(array('name' => 'Player 2', 'id' => 5));
      $this->addplayer(array('name' => 'Player 3', 'id' => 16));
  	  $this->variant = 'normal';	  	  
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
	// játékoslétszámtól függ a kézben tartott lapok száma
	if ($this->playersnum == 2 or $this->playersnum == 3) {
		$this->handsize = 5;
	} elseif ($this->playersnum == 4 or $this->playersnum == 5) {
		$this->handsize = 4;
	}
  for($i = 0; $i < $this->handsize; $i++) {
	  foreach($this->players as &$p) {
        $p['hand'][] = $this->deck->draw();
      }
    }
  }
  
  public function addplayer($player) {
    //later loaded from user data
    $this->players[] = $player;
    $this->playersnum += 1;
  }
  
  public function saveToDB() {
    //later, data will be saved in smaller parts
    $data['deck'] = $this->deck->cards;
    $data['lives'] = $this->lives;
    $data['hints'] = $this->hints;
    $data['discard'] = $this->discard;
    $data['variant'] = $this->variant;
    $data = json_encode($data);
    if($this->id) {
      $insert = false;
      $query = DB::$db->prepare('UPDATE games SET name = :name, status = :status, playersnum = :playersnum, data = :data WHERE id = :id');
      $query->bindParam(':id', $this->id);
    } else {
      $insert = true;
      $query = DB::$db->prepare('INSERT INTO games(name, status, playersnum, data, created)
                                 VALUES (:name, :status, :playersnum, :data, NOW())');
    }
    $query->bindParam(':name', $this->name);
    $query->bindParam(':status', $this->status);
    $query->bindParam(':playersnum', $this->playersnum);
    $query->bindParam(':data', $data);
    $query->execute();
    if($insert) {
      $this->id = DB::$db->lastInsertId();
    }
    if($insert) {
      $query = DB::$db->prepare('INSERT INTO game_player(gameid, playerid, `order`, hand)
                                 VALUES (:gameid, :playerid, :order, :hand)');
      $query->bindParam(':order', $order);
    } else {
      //this might require tweaking later, when there will be a lobby with open games (possibly, players added or removed on update)
      $query = DB::$db->prepare('UPDATE game_player SET hand = :hand WHERE gameid = :gameid AND playerid = :playerid');
    }
    $query->bindParam(':gameid', $this->id);
    $query->bindParam(':playerid', $playerid);
    $query->bindParam(':hand', $hand);
    $order = 0;
    foreach($this->players as $p) {
      $playerid = $p['id'];
      $order++;
      $hand = json_encode($p['hand']);
      $query->execute();
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
      $data = json_decode($game['data']);
      $this->lives = $data->lives;
      $this->hints = $data->hints;
      $this->variant = $data->variant;
      $this->discard = $data->discard;
      $this->deck = new Deck($data->deck, $this->variant);
      $query = DB::$db->prepare('SELECT * FROM game_player WHERE gameid = :id ORDER BY `order`');
      $query->bindParam(':id', $id);
      $query->execute();
      while($p = $query->fetch()) {
        $p2['id'] = $p['playerid'];
        $p2['hand'] = json_decode($p['hand']);
        //later, name will be loaded from players table
        $p2['name'] = 'Player '.$p['playerid'];
        $this->players[] = $p2;
      }
      return true;
    } else {
      return false;
    }
  }
}