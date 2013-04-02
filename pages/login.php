<?php
$error = false;
if(isset($_POST['name']) && isset($_POST['password']) ){
  $name = $_POST['name'];
  $password = $_POST['password'];
  $login_query = DB::$db->prepare("SELECT * FROM users WHERE  name = :name LIMIT 1");
  $login_query->bindParam(':name', $name);
  $login_query->execute();
  if($user = $login_query->fetch()) {
    if(encrypt_password($_POST['password']) == $user['password']){
      unset($user['password']);
      $_SESSION['user'] = $user;
      $login_query = DB::$db->prepare("UPDATE users SET  last_login = NOW() WHERE id = :id LIMIT 1");
      $login_query->bindParam(':id', $user['id']);
      $login_query->execute();
      header('Location: index.php');
      die();
    }
  }
  $error = true;
}
Template::$content = Template::renderTemplate('login', array('error' => $error));
