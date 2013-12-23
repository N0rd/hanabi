<?php
require('includes/core.php');

if(!isset($_POST['card']) || !isset($_POST['info'])) {
  $output = array('status' => 0);
} else {
  $player = new Player($_SESSION['currentgame'], get_user_id());
  $info = $player->setInfo($_POST['card'], $_POST['info']);
  $player->saveToDb();
  if($info) {
    $output = array('status' => 1, 'info' => $info);
  } else {
    $output = array('status' => 0, 'info' => $info);
  }
}

echo json_encode($output);