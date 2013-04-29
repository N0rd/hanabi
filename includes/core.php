<?php
define('DEBUG', '1');
session_start();
require('includes/db.php');
require('includes/template.php');
require('includes/game.class.php');
require('includes/deck.class.php');
require('includes/player.class.php');

//DEBUG Tools:
if(DEBUG) {
  require('includes/krumo/class.krumo.php');
  require('includes/FirePHPCore/FirePHP.class.php');
  $firephp = FirePHP::init();
}

function encrypt_password($password) {
 return md5($password.PASSWORD_SALT);
}

function validate_email($email){
  return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]*)$/", $email);
}

function check_login() {
  if(!isset($_SESSION['user'])) {
    header('Location: ?page=login');
    die();
  }
}

function get_user_id() {
  return $_SESSION['user']['id'];
}

function object_to_array($obj) {
  if(!$obj || $obj == 'null') {
    return array();
  }
  $obj = json_decode($obj);
  if(is_object($obj)) {
    $obj = get_object_vars($obj);
  }
  foreach($obj as &$o) {
    if(is_object($o)) {
      $o = get_object_vars($o);
    }
  }
  return $obj;
}