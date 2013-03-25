<?php

Class Game {
  
  public $id;
  public $name;
  public $status;
  public $deck;
  public $lives;
  public $hints;
  public $players;
  public $playersnum;
  public $discard;
  public function __construct($id = null) {
    if($id) {
      $this->loadFromDB($id);
    } else {
      //test data, later filled in lobby
      $this->id = null;
      $this->name = 'Test Game';
      $this->status = 1;
      $this->playersnum = 3;
      $this->addplayer(array('name' => 'Player 1', 'id' => 4));
      $this->addplayer(array('name' => 'Player 2', 'id' => 5));
      $this->addplayer(array('name' => 'Player 3', 'id' => 16));
    }
  }

  public function start() {
    $this->deck = new Deck();
    $this->lives = 3;
    $this->hints = 8;
    $this->discard = array();
    foreach($this->players as &$p) {
      for($i = 0; $i < 4; $i++) {
        $p['hand'][] = $this->deck->draw();
      }
    }
  }
  
  public function addplayer($player) {
    //later loaded from user data
    $this->players[] = $player;
  }
  
  public function saveToDB() {
    //later, data will be saved in smaller parts
    $data['deck'] = $this->deck->cards;
    $data['lives'] = $this->lives;
    $data['hints'] = $this->hints;
    $data['players'] = $this->players;
    $data['discard'] = $this->discard;
    $data = json_encode($data);
    if($this->id) {
      //update
    } else {
      $query = DB::$db->prepare("INSERT INTO games(name, status, playersnum, data, created)
        VALUES (:name, :status, :playersnum, :data, NOW())");
    }
    $query->bindParam(':name', $this->name);
    $query->bindParam(':status', $this->status);
    $query->bindParam(':playersnum', $this->playersnum);
    $query->bindParam(':data', $data);
    $query->execute();
    $this->id = DB::$db->lastInsertId();
  }

  public function loadFromDB($id) {
    $query = DB::$db->prepare("SELECT * FROM games WHERE id = :id");
    $query->bindParam(':id', $id);
    $query->execute();
    if($game = $query->fetch()) {
      $this->id = $game['id'];
      $this->name = $game['name'];
      $this->status = $game['status'];
      $data = json_decode($game['data']);
      $this->deck = new Deck($data->deck);
      $this->lives = $data->lives;
      $this->hints = $data->hints;
      $this->players = $data->players;
      $this->discard = $data->discard;
      return true;
    } else {
      return false;
    }
  }
}