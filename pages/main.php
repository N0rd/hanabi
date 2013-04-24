<?php
check_login();
if(isset($_GET['id'])) {
  //TODO: check if user is present in this game
  $game = new Game($_GET['id']);
} else {
  header('Location: ?page=lobby');
}
$_SESSION['currentgame'] = $game->id;
//TODO: separate own hand
$gamerender['players'] = Template::renderElement('players', $game);
$gamerender['ownhand'] = Template::renderElement('ownhand', $game);
$gamerender['fireworks'] = Template::renderElement('fireworks', $game);
$gamerender['hints'] = Template::renderElement('hints', $game);
$gamerender['lives'] = Template::renderElement('lives', $game);
$gamerender['discard'] = Template::renderElement('discard', $game);
$gamerender['deckbox'] = Template::renderElement('deckbox', $game);

//we should rearrange these somehow
$gamerender['game'] = $game;

Template::$content = Template::renderTemplate('main', array('gamerender' => $gamerender, 'game' => $game));