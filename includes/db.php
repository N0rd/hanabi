<?php
define('PASSWORD_SALT', 'as1fds35hrtt24235'); //change this on production server
define('DB_DATA', 'mysql:host=localhost;dbname=hanabi');  //change this on production server
define('DB_USER', 'hanabi');  //change this on production server
define('DB_PASS', 'hanabi');  //change this on production server

class DB{
  public static $db;
  public static function init($db_server, $db_usr, $db_psw) {
    DB::$db = new PDO($db_server, $db_usr, $db_psw, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    DB::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    DB::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  }
}

DB::init(DB_DATA, DB_USER, DB_PASS);
