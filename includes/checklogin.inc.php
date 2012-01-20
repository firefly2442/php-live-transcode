
<?php
require_once("config.inc.php");


//Check login session
session_start();

if (!$_SESSION['php-live-transcode-loggedin'])
{
	//check fails
	header("Location: authenticate.php?status=session");
	exit();
}

?>
