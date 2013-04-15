<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digitális Hanabi</title>
<link href="css/hanabi.css" rel="stylesheet" type="text/css" />
<link href="css/main.css" rel="stylesheet" type="text/css" />
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="css/hanabi.js"></script>
</head>
<body>
	<div id="container">
		<div id="header">
    	<img id="headImg" src="images/header.gif" alt="Hanabi"/>
            <ul id="menu">
            	<li class="<?php echo ($page=='greet') ? 'menuSelected' : 'menuItem'; ?>">
                	<a href="index.php?page=greet">Főoldal</a>
                </li>
            	<li class="<?php echo ($page=='login') ? 'menuSelected' : 'menuItem'; ?>">
                	<a href="index.php?page=login">Bejelentkezés</a>
                </li>
            	<li class="<?php echo ($page=='newuser') ? 'menuSelected' : 'menuItem'; ?>">
                	<a href="index.php?page=newuser">Regisztráció</a>
                </li>
            	<li class="<?php echo ($page=='lobby') ? 'menuSelected' : 'menuItem'; ?>">
                	<a href="index.php?page=lobby">Váró</a>
                </li>
            	<li class="<?php echo ($page=='main') ? 'menuSelected' : 'menuItem'; ?>">
                	<a href="index.php?page=main">Játék</a>
                </li>
            	<li class="<?php echo ($page=='rules') ? 'menuSelected' : 'menuItem'; ?>">
                	<a href="index.php?page=rules">Szabályok</a>
                </li>
            </ul>
		</div>
    <div id="main">
    	<?php echo Template::$content ?>
    </div>
    <div id="footer">
    	<span class="lablec" onclick="newWindow()">Lábléc</span>
    </div>
	</div>
</body>
</html>