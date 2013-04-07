<?php
check_login();
$game = new Game();
$game->start();
krumo($game);
$game->saveToDb();
$game2 = new Game($game->id);
krumo($game2);
$game2->saveToDb();

Template::$content = Template::renderTemplate('main', array('game' => $game));