<?php
check_login();
if(isset($_GET['id'])) {
  //TODO: check if user is present in this game
  $game = new Game($_GET['id']);
} else {
  //poor(/lazy) man's unit tests :)
  $game = new Game();
  $game->start();
  //TEST CODE, CAN BE MODIFIED, DELETED FREELY
  $game->builtpiles['Y'] = 3;
  $game->builtpiles['R'] = 2;
  $game->action('discard', 1);
  $game->action('discard', 1);
  $game->action('discard', 1);
  $game->action('discard', 1);
  $game->action('discard', 1);
  $game->action('discard', 1);
  $game->action('discard', 1);
  $game->action('discard', 1);
  $game->action('discard', 1);
  $game->hints = 5;
  //END OF TEST CODE
  $game->saveToDb();
}
$_SESSION['currentgame'] = $game->id;
krumo($game);

//TODO: separate own hand
$gamerender['players'] = Template::renderElement('players', $game);
$gamerender['ownhand'] = Template::renderElement('ownhand', $game);
$gamerender['fireworks'] = Template::renderElement('fireworks', $game);
$gamerender['hints'] = Template::renderElement('hints', $game);
$gamerender['lives'] = Template::renderElement('lives', $game);
$gamerender['discard'] = Template::renderElement('discard', $game);
//we should rearrange these somehow
$gamerender['game'] = $game;

Template::$content = Template::renderTemplate('main', array('game' => $gamerender));