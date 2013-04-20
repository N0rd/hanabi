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

  public static function renderLog($log, $game) {
    if(isset($log['player'])) {
      $player = $game->getPlayerById($log['player'])->name;
    }
    switch($log['event']) {
      case 'hint':
        $receiverplayer = $game->getPlayerById($log['receiver'])->name;
        $match = array();
        foreach($log['match'] as $place) {
          $match[] = $place + 1;
        }
        $match = implode(', ', $match);
        if(is_numeric($log['hint'])) {
          $hint = '<strong>'.$log['hint'].'</strong>';
        } else {
          $color = $game->colors[$log['hint']];
          $hint = Template::fontColor($color['color'], $color['name']);
        }
        return $player.' súgott neki: '.$receiverplayer.'<br />Az alábbi lapjai '.$hint.': '.$match;
      case 'loselife': return '<strong>Elvesztettetek egy életet!</strong>';
      case 'increasehints': return 'Kaptatok egy súgási lehetőséget';
      case 'decreasehints': return 'Elhasználtatok egy súgási lehetőséget';
      case 'draw': return $player.' húzott egy lapot';
      case 'firesuccess':
        $color = $game->colors[Deck::getCardColor($log['card'])];
        return $player.' sikeresen fellőtt egy '.Template::fontColor($color['color'], $color['name']).' tüzijátékrakétát';
      case 'firefail': return $player.' elrontott egy kilövést';
      case 'discard':
        $color = $game->colors[Deck::getCardColor($log['card'])];
        $number = Deck::getCardNumber($log['card']);
        return $player.' eldobott egy lapot: '.Template::fontColor($color['color'], $color['name'].' '.$number);
      default: return json_encode($log, true);
    }
  }

  public static function fontColor($color, $string) {
    return '<strong><font style="color: '.$color.'">'.$string.'</font></strong>';
  }

}