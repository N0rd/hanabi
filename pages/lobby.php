<?php
check_login();

//temporary game join code, later this will be in an ajax call, probably
if(isset($_GET['action']) || isset($_GET['game'])) {
  $game = new Game($_GET['game']);
  if($game->addPlayer($_SESSION['user']['id'])) {
    $player = $game->players[count($game->players)-1];
    $player->saveToDb(true);
  }
  header('Location: ?page=lobby');  
}

$newgames = new GameList('new');
$newgames = $newgames->render();
$currentgames = new GameList('current');
$currentgames = $currentgames->render();

Template::$content = Template::renderTemplate('lobby', array('newgames' => $newgames, 'currentgames' => $currentgames));