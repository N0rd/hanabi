<?php
check_login();
include_once('includes/Game.class.php');
include_once('includes/Deck.class.php');

$game = new Game();
$game->start();
$game->saveToDb();

Template::$content = Template::renderTemplate('main', array('game' => $game));