<?php
include_once('includes/user.class.php');
$user = new User();
$errors = false;
if(count($_POST)){
  $user->loadFromArray($_POST);
  if(!$errors = $user->validate()){
    $user->saveToDB();
  }
}
$parameters = array(
  'user' => $user,
  'errors' => $errors,
);
Template::$content = Template::renderTemplate('userform', $parameters);