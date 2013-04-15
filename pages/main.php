<?php
check_login();

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
$game->action('discard', 1);
$game->hints = 5;
$game->lives = 2;
//END OF TEST CODE
$game->saveToDb();
$_SESSION['currentgame'] = $game->id;

//TODO: separate own hand
$gamerender['players'] = Template::renderElement('players', $game);
$gamerender['ownhand'] = Template::renderElement('ownhand', $game);
$gamerender['fireworks'] = Template::renderElement('fireworks', $game);
$gamerender['hints'] = Template::renderElement('hints', $game);
$gamerender['lives'] = Template::renderElement('lives', $game);
$gamerender['discard'] = Template::renderElement('discard', $game);

function hinthelp() {
	echo ('1., 3.');
	return;
}

function chatline() {
	krumo($game);
}

Template::$content = Template::renderTemplate('main', array('gamerender' => $gamerender, 'game' => $game));