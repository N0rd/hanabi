<?php
check_login();

//poor(/lazy) man's unit tests :)
$game = new Game();
$game->start();
//TEST CODE, CAN BE MODIFIED, DELETED FREELY
$game->builtpiles['Y'] = 3;
$game->builtpiles['R'] = 2;
$game->lives = 1;
$game->action('discard', 1);
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
$_SESSION['currentgame'] = $game->id;




krumo($game);

//TODO: separate own hand
$gamerender['players'] = array();
foreach($game->players as $player) {
  $gamerender['players'][] = Template::renderTemplate('hand', array('player' => $player));
}

$gamerender['fireworks'] = Template::renderTemplate('fireworks', array('fireworks' => $game->builtpiles));
$gamerender['hints'] = Template::renderTemplate('hints', array('available' => $game->hints, 'used' => $game->maxhints - $game->hints));
$gamerender['lives'] = Template::renderTemplate('lives', array('available' => $game->lives, 'used' => $game->getMaxLives() - $game->lives));
$gamerender['discard'] = Template::renderTemplate('discard', array('discard' => $game->discard));



function hinthelp() {
	echo ('1., 3.');
	return;
}

Template::$content = Template::renderTemplate('main', array('game' => $gamerender));