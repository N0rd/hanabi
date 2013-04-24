<?php
check_login();

//temporary game join code
if(isset($_GET['action']) || isset($_GET['game'])) {
  $game = new Game($_GET['game']);
  if($game->addPlayer($_SESSION['user']['id'])) {
    $player = $game->players[count($game->players)-1];
    $player->saveToDb(true);
  }
}

$query = DB::$db->query('SELECT id FROM games WHERE status = 0');
$query->execute();
$games = array();
while($game = $query->fetch()) {
  $games[] = new Game($game['id']);
}

Template::$content = Template::renderTemplate('lobby', array('games' => $games));