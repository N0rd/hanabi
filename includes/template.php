<?php
Class Template {
  public static $content;

  public static function renderTemplate($filename, $parameters = array()){
    ob_start();
    extract($parameters);
    include('templates/'.$filename.'.tpl.php');
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
  }

  public static function renderElement($element, $game) {
    switch($element) {
      case 'fireworks': return Template::renderTemplate('fireworks', array('fireworks' => $game->builtpiles));
      case 'hints': return Template::renderTemplate('hints', array('available' => $game->hints, 'used' => $game->maxhints - $game->hints));
      case 'lives': return Template::renderTemplate('lives', array('available' => $game->lives, 'used' => $game->getMaxLives() - $game->lives));
      case 'discard': return Template::renderTemplate('discard', array('discard' => $game->discard));
      case 'deckbox': return Template::renderTemplate('deckbox', array('size' => count($game->deck->cards)));		
      case 'ownhand': return Template::renderTemplate('ownhand', array('cards' => /*debug*/ $game->players[$game->currentplayer]->hand));
      case 'players':
        $output = '';
        foreach($game->players as $player) {
          $output .= Template::renderTemplate('hand', array('player' => $player));
        }
        return $output;
      default: return '';
    }
  }
	
	public static function numberText($number) {
		if ($number == 1) {
      return 'egyes';
		} elseif ($number ==  2) {
      return 'kettes';
		} elseif ($number ==  3) {
      return 'hármas';
	  } elseif ($number == 4) {
      return 'négyes';
		} elseif ($number ==  5) {
      return 'ötös';
		} else {
			return 'bug';
		}
	}
  
  public static function renderLog($log, $game) {
    if(isset($log['player'])) {
      $player = $game->getPlayerById($log['player'])->name;
    }
    switch($log['event']) {
      case 'hint':
        $receiverplayer = $game->getPlayerById($log['receiver'])->name;
        $match = array();
        foreach($log['match'] as $place) {
          if ($place == 0) {$match[] = 'az első';}
          if ($place == 1) {$match[] = 'a második';}
          if ($place == 2) {$match[] = 'a harmadik';}
          if ($place == 3) {$match[] = 'a negyedik';}
          if ($place == 4) {$match[] = 'az ötödik';}					
        }
				if (is_numeric($log['hint'])) {
					$hint = '<strong>'.Template::numberText($log['hint']).'</strong>';
        } else {
          $color = $game->colors[$log['hint']];
          $hint = Template::fontColor($color['color'], $color['name']);
        }
				$text = $receiverplayer.': ';
				if (empty($match)) {
					$text .= 'nincs'.$hint.'lapod';
				} else {
					$i = 0;
					while ($i < count($match)-1) {
	        	$text .= $match[$i].', ';
						$i++;
					}
					if ($i >= 1) {
						$text .= ' és ';
					}
					$text .=$match[$i].' lapod '.$hint;
				}
        return $text.'.<br />'.$player.' elhasznált egy súgási lehetőséget.';
      case 'decreasehints':
				// todb? Elhasználtak egy súgási lehetőséget;
				return false;
      case 'firesuccess':
        $color = $game->colors[Deck::getCardColor($log['card'])];
				$number = Template::numberText(Deck::getCardNumber($log['card']));
        return $player.' sikeresen fellőtt egy '.Template::fontColor($color['color'], $color['name'].' '.$number).' tüzijáték rakétát.<br />'
				     . $player.' húzott egy lapot.';
      case 'firefail':
        $color = $game->colors[Deck::getCardColor($log['card'])];
				$number = Template::numberText(Deck::getCardNumber($log['card']));		
	      return $player.' elrontott egy '.Template::fontColor($color['color'], $color['name'].' '.$number).' kilövését.<br />'		     
						 . 'Elveszítettetek egy életet.<br />'
						 . $player.' húzott egy lapot.';				
      case 'loselife':
				//todb? Elvesztettek egy életet;
			  return false;
      case 'discard':
        $color = $game->colors[Deck::getCardColor($log['card'])];
				$number = Template::numberText(Deck::getCardNumber($log['card']));
        return $player.' eldobott egy '.Template::fontColor($color['color'], $color['name'].' '.$number).'t.<br />'
				     . 'Kaptatok egy súgási lehetőséget.<br />'
				     . $player.' húzott egy lapot.';
      case 'increasehints':
				//todb? Kaptak egy súgási lehetőséget; 
				return false;
      case 'draw':
				//todb? $player húzott egy $log['card'];
				return false;
      default: return json_encode($log, true);
    }
  }

  public static function fontColor($color, $string) {
    return '<strong><font style="color: '.$color.'">'.$string.'</font></strong>';
  }

}