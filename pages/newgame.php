<?php
check_login();
if(count($_POST)){
  if(!$_POST['name']) {
    $_POST['name'] = 'Játék';
  }
  $playersnum = (int)$_POST['playersnum'];
  if($playersnum < 2) {
    $playersnum = 2;
  } else if($playersnum > 5) {
    $playersnum = 5;
  }
  $game = new Game(null, $_POST['name'], $playersnum);
  $game->creator = get_user_id();
  $game->addplayer(get_user_id());
  $game->saveToDb();
  header('Location: ?page=lobby');
}
Template::$content = Template::renderTemplate('newgame');