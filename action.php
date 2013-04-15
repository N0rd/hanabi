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
$refreshlist = $output['refresh'];
unset($output['refresh']);

$refresh = array();
foreach($refreshlist as $element) {
  if(!isset($refresh[$element])) {
    $refresh[$element] = Template::renderElement($element, $game);
  }
}

$output = array('output' => $output, 'refresh' => $refresh, 'debug' => $game);



echo json_encode($output);