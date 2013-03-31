<?php

Class Deck {
  public static $colors = array(
    'R' => array(
      'name' => 'Piros',
      'color' => '#FF0000',
    ),
    'G' => array(
      'name' => 'Zöld',
      'color' => '#00FF00',
    ),
    'B' => array(
      'name' => 'Kék',
      'color' => '0000FF',
    ),
    'P' => array(
      'name' => 'Lila',
      'color' => '#FF00FF',
    ),
    'W' => array(
      'name' => 'Fehér',
      'color' => '#FFFFFF',
    ),
  );

  public static $numbers = array(
    1 => 3,
    2 => 2,
    3 => 2,
    4 => 2,
    5 => 1,
  );

  public $cards; 
  
  public function __construct($cards = null) {
    if($cards) {
      $this->cards = $cards;
    } else {
      $this->build();
    }
  }
  
  public function build() {
    $this->cards = array();
    foreach(Deck::$colors as $cid => $c) {
      foreach(Deck::$numbers as $n => $p) {
        for($i = 1; $i <= $p; $i++) {
          $this->cards[] = $cid.$n;
        }
      }
    }
    shuffle($this->cards);
  }
  
  public function draw() {
    return array_pop($this->cards);
  }
}
