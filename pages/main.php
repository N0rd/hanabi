<?php
check_login();

//poor(/lazy) man's unit tests :)
$game = new Game();
$game->start();
krumo($game);

$gamerender['players'] = array();
foreach($game->players as $player) {
  $gamerender['players'][] = Template::renderTemplate('hand', array('player' => $player));
}


function hinthelp() {
	echo ('1., 3.');
	return;
}

Template::$content = Template::renderTemplate('main', array('game' => $gamerender));