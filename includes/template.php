<?php
Class Template {
  public static $content;

  public static function renderTemplate($filename, $parameters = array()){
    ob_start();
    extract($parameters);
    include('templates/'.$filename.'.tpl.php');
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
  }
}