<?php
require('includes/core.php');

if(!isset($_POST['card']) || !isset($_POST['info'])) {
  $output = array('status' = 0);
} else {

}





echo json_encode($output);