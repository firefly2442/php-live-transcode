<?php

require('init.inc.php');


//iterate through the media path and create links to create the stream for the appropriate files

//TODO: recursively list folders and contents  AND  only create links for files, not folders

echo "<b>Stream File</b><br><br>";

if ($handle = opendir(MEDIA_PATH)) {
    while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..")
		{
        	echo "<a href='".BASE_URL."create.php/".$file."'/>".$file."</a><br>\n";
		}
    }
}



?>
