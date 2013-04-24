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