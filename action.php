<?php
require('includes/core.php');
$refresh = array();
$logs = array();
$error = false;
if(!isset($_SESSION['currentgame'])) {
  $game = false;
  $output = array('error' => 'Jelentkezz be újra!');
} else {
  $game = new Game($_SESSION['currentgame']);
  if(!isset($_POST['action'])) {
    $game->loadLogs();
    //We will return changes here without action
    $output = array('success' => true, 'refresh' => array());
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

  if(isset($output['refresh'])) {
    $refreshlist = $output['refresh'];
    unset($output['refresh']);
    foreach($refreshlist as $element) {
      if(!isset($refresh[$element])) {
        $refresh[$element] = Template::renderElement($element, $game);
      }
    }
  }

  if($game->newlog) {
    foreach($game->newlog as $log) {
      $logs[] = Template::renderLog($log, $game);
    }
  }
}
$output = array('output' => $output, 'refresh' => $refresh, 'debug' => $game, 'logs' => $logs, 'error' => $error);
echo json_encode($output);