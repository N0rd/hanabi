<?php
check_login();

//poor(/lazy) man's unit tests :)
$game = new Game();
$game->start();
krumo($game);
$game->saveToDb();
$game2 = new Game($game->id);
krumo($game2);
$game2->action('hint', '1', 'R');
krumo($game2);
$game2->action('hint', '2', '3');
krumo($game2);
$game2->saveToDb();
$game3 = new Game($game2->id);
$game3->action('discard', 3);
krumo($game3);
$game3->action('build',1);
krumo($game3);
$game3->saveToDb();

Template::$content = Template::renderTemplate('main', array('game' => $game));