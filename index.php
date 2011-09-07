<?php

require_once("config.inc.php");


function recursiveDirectory($path = '.', $level = 0) {
	//iterate through the media path and create links to create the stream for the appropriate files

	// Only show media types specified in config.inc.php file
	$media  = unserialize(MEDIATYPES);

	$dh = @opendir($path);

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
					echo "$spaces<a href='".BASE_URL."create.php/".$file."'/>".$file."</a><br>\n";
				}
			}
		}
	}

	closedir($dh);
}



echo "<b>Stream File</b><br><br>";

recursiveDirectory(MEDIA_PATH)



?>

<br><hr>
<center><a href="http://www.rivetcode.com">php-live-transcode</a>
<br>Version: <?php echo $version;?></center>
</body>
</html>
