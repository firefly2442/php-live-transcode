<?php

require_once("includes/checklogin.inc.php");


function recursiveDirectory($path = '.', $level = 0) {
	//iterate through the media path and create links to create the stream for the appropriate files

	// Only show media types specified in config.inc.php file
	$media  = unserialize(MEDIATYPES);

	$dh = opendir($path) or die("Unable to open directory.");

	while (false !== ($file = readdir($dh))) {
		//Ignore directories back
		if ($file != "." && $file != "..") {

			$spaces = str_repeat('&nbsp;', ($level * 4));

			if (is_dir( "$path/$file")) {
				//It's a directory, continue reading
				echo "<img src='images/folder.png' alt='Folder' title='Folder'><strong>$spaces $file</strong><br>\n";
				recursiveDirectory("$path/$file", ($level+1));
			} else {
				//Make sure it's an approved extension
				if (in_array(substr($file, -4), $media)) {
					$folder = str_replace(MEDIA_PATH, "", $path);
					echo "$spaces<a href=\"create.php?media=".$folder."/".$file."\"/>".$file."</a><br>\n";
				}
			}
		}
	}

	closedir($dh);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="shortcut icon" href="favicon.ico" >
	<title>php-live-transcode</title>
</head>
<body>

<h1>Stream Video File</h1>

<a href="authenticate.php?status=logout"><img src="./images/logout.png" title="Logout" alt="Logout" /> Logout</a>
<div id="header-nav">
	<p><u>Select Video</u> -> Pick Decoding Settings -> Watch Video</p>
</div>

<?php

recursiveDirectory(MEDIA_PATH)

?>

<br><hr>
<center><a href="http://www.rivetcode.com">php-live-transcode</a>
<br>Version: <?php echo VERSION;?></center>
</body>
</html>
