
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

function displayLoginInformation() {
	if (PASSWORD != "") {
		echo "<a href='authenticate.php?status=logout'><img src='./images/logout.png' title='Logout' alt='Logout' /> Logout</a>";
	}
}

?>
