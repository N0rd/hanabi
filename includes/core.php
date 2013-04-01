<?php
define('DEBUG', '1');
session_start();
require_once('includes/db.php');
require_once('includes/template.php');
//DEBUG Tools:
if(DEBUG) {
  require_once('includes/krumo/class.krumo.php');
  require_once('includes/FirePHPCore/FirePHP.class.php');
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