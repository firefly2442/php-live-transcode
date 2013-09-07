<?php
require_once("includes/config.inc.php");

//check password
if ((isset($_POST['password']) && $_POST['password'] == PASSWORD) || PASSWORD == "")
{
	//successful login
	session_start();
	$_SESSION['php-live-transcode-loggedin'] = true;
	header("Location: index.php");
	exit();
}
else if (isset($_POST['password']))
{
	//password was incorrect at this point!
	$error = true;
}

//Destroy any previous session data
//This way it requires a login
session_start();
session_destroy();
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="shortcut icon" href="favicon.ico" >
	<title>php-live-transcode</title>
</head>
<body>
<center>
<h2>Login</h2>
</center>

	<?php
	if (isset($error) && $error == true)
		echo "<p class='error'>Error, password is incorrect.<br>Entries are cAsESEnsITiVE, do you have your capslock key on?...</p>\n";
	if (isset($_GET['status']) && $_GET['status'] == "session")
		echo "<p class='error'>Your session has timed out, please re-login.</p>\n";
	if (isset($_GET['status']) && $_GET['status'] == "logout")
		echo "<p class='logout'>Successfully logged out.</p>\n";
	?>
	<center>
	<form action="authenticate.php" method="POST" name="login">
	<p>Password: <input name="password" type="password">
	<input type="submit" name="Log In" value="Log In">
	</form>
	</center>

<br><hr>
<center><a href="https://github.com/firefly2442/php-live-transcode/">php-live-transcode</a>
<br>Version: <?php echo VERSION;?></center>
</body>
</html>
