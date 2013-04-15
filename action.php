<?php
require('includes/core.php');
$game = new Game($_SESSION['currentgame']);
if(!isset($_POST['action'])) {
  //We will return changes here without action
} else {
  if(!isset($_POST['param1'])) {
    $_POST['param1'] = null;
  }
  if(!isset($_POST['param2'])) {
    $_POST['param2'] = null;
  }
  $output = $game->action($_POST['action'], $_POST['param1'], $_POST['param2']);
  $game->saveToDb();
}
$output = array('output' => $output, 'debug' => $game);
echo json_encode($output);