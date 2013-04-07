<?php

Class Player {
  private $game;
  public $id; 
  public $hand;
  public $name;
  public $current;
  
  public function __construct($game, $id, $name, $hand = array(), $current = false) {
    $this->game = $game;
    $this->id = $id;
    $this->hand = $hand;
    $this->name = $name;
    $this->current = $current;
  }
  
  public function saveToDb($insert, $order) {
    if($insert) {
      $query = DB::$db->prepare('INSERT INTO game_player(gameid, playerid, `order`, hand)
                                 VALUES (:gameid, :playerid, :order, :hand)');
      $query->bindParam(':order', $order);
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
  
  public function discard($cardplace) {
    $card = $this->hand[$cardplace];
    unset($this->hand[$cardplace]);
    $this->game->addToDiscard($card);
    $this->draw();
  }
    
  public function build($cardplace) {
    $card = $this->hand[$cardplace];
    unset($this->hand[$cardplace]);
    $this->game->buildPile($card);
    $this->draw();
  }
}
