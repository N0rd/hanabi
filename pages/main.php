<?php
check_login();

//poor(/lazy) man's unit tests :)
$game = new Game();
$game->start();
$game->builtpiles['Y'] = 3;
$game->builtpiles['R'] = 2;
krumo($game);

//TODO: separate own hand
$gamerender['players'] = array();
foreach($game->players as $player) {
  $gamerender['players'][] = Template::renderTemplate('hand', array('player' => $player));
}

$gamerender['fireworks'] = Template::renderTemplate('fireworks', array('fireworks' => $game->builtpiles));

function hinthelp() {
	echo ('1., 3.');
	return;
}

Template::$content = Template::renderTemplate('main', array('game' => $gamerender));