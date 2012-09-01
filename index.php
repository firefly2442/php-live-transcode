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

			if (is_dir( "$path/$file")) {
				//It's a directory, continue reading
				//div id's can't have periods or other weird characters so we generate a unique ID
				$unique_id = uniqid("unique_");
				echo "<div id='folder'><div id=\"folder_".$unique_id."\"><img src='images/folder.png' alt='Folder' title='Folder'>$file</div></div><br>\n";
				echo "<div id=\"".$unique_id."\"><div id='box'>\n";
				recursiveDirectory("$path/$file", ($level+1));
				echo "</div></div>\n";

				echo "<script>\n";
				//hide all folder contents by default
				echo "$(\"#".$unique_id."\").hide();\n";

				echo "$(\"#folder_".$unique_id."\").click(function () {\n";
				echo "$(\"#".$unique_id."\").show('fast');\n";
				echo "});</script>\n";
			} else {
				//Make sure it's an approved extension
				if (in_array(substr($file, -4), $media)) {
					$folder = str_replace(MEDIA_PATH, "", $path);
					echo "<a href=\"create.php?media=".$folder."/".$file."\">".$file."</a><br>\n";
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
	<script type="text/javascript" src="./javascript/jquery-1.8.1.min.js"></script>
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
