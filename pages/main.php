<?php
check_login();

//poor(/lazy) man's unit tests :)
$game = new Game();
$game->start();
krumo($game);
$game->saveToDb();
$game2 = new Game($game->id);
krumo($game2);
$game2->players[0]->build(1);
krumo($game2);
$game2->players[0]->build(2);
krumo($game2);
$game2->players[0]->build(1);
krumo($game2);
$game2->players[0]->build(3);
krumo($game2);
$game2->saveToDb();
$game3 = new Game($game2->id);
$game3->players[0]->build(1);
krumo($game3);
$game3->players[0]->build(2);
krumo($game3);
$game3->saveToDb();

Template::$content = Template::renderTemplate('main', array('game' => $game));