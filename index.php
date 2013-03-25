<?php
define('DEFAULT_MAIN_PAGE', 'main');
require_once('includes/core.php');

//check if the page is set and exists, the preg_match protects against directory manipulation
if(isset($_GET['page']) && preg_match('/^[a-zA-Z0-9]+$/', $_GET['page']) && file_exists('pages/'.$_GET['page'].'.php')) {
  $page = $_GET['page'];
} else {
  $page = DEFAULT_MAIN_PAGE;
}

require_once('pages/'.$page.'.php');

require_once('templates/index.tpl.php');
