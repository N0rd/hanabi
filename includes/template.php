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
      case 'players': 
        $output = '';
        foreach($game->players as $player) {
          $output .= Template::renderTemplate('hand', array('player' => $player));
        }
        return $output;
      default: return '';
    }
  }
  
}