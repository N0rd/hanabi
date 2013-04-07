<?php
check_login();

//poor(/lazy) man's unit tests :)
$game = new Game();
$game->start();
krumo($game);
$game->saveToDb();
$game2 = new Game($game->id);
krumo($game2);
$game2->players[0]->discard(1);
krumo($game2);
$game2->players[0]->discard(2);
krumo($game2);
$game2->players[0]->discard(1);
krumo($game2);
$game2->saveToDb();

Template::$content = Template::renderTemplate('main', array('game' => $game));