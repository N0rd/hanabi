<?php

Class Deck {
  public static $basicColors = array(
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
    'Y' => array(
      'name' => 'Sárga',
      'color' => 'FFFF00',
    ),
    'W' => array(
      'name' => 'Fehér',
      'color' => '#FFFFFF',
    ),
  );
  //Piros, 

  public $colors = array();
  
  public function setVariant($variant) {
	  $this->colors = Deck::$basicColors;
	  if ($variant == 'hard' or $variant == 'harder') {
		  // plusz 1 szin a maga 10 lapjával ez a kártyapakliban a szivárvány, de itt lehet csak egy 6. szin simán
		  $this->colors['P'] = array(	'name' => 'Lila',
								        'color' => '#FF00FF'
							         );
	  } elseif ($variant == 'anti') {
		  // Ilyenkor a szivárvány szín egy szívatós anti-joker, amire minden színnél rá kell mutatni, de hiba ha bárhova lerakod...
		  $this->colors['J'] = array( 'name' => 'Szivárvány',
								      'color' => '#000000'
							         );
	  }
  }

  public static $numbers = array(
    1 => 3,
    2 => 2,
    3 => 2,
    4 => 2,
    5 => 1,
  );
  
  public static $numbersHarder = array(
    1 => 1,
    2 => 1,
    3 => 1,
    4 => 1,
    5 => 1,
  );
  
  public $cards; 
  
  public function __construct($cards = null, $variant = 'normal') {
    if($cards) {
      $this->cards = $cards;
    } else {
      $this->setVariant($variant);
      $this->build($variant);
    }
  }
  
  public function build($variant) {
    $this->cards = array();
	foreach($this->colors as $cid => $c) {
	  $num = Deck::$numbers;
	  if ($variant == 'harder' and $cid == 'P') {
		$num = Deck::$numbersHarder;
	  }
	  foreach($num as $n => $p) {
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
