<?php

require_once("includes/checklogin.inc.php");

require_once("includes/init.inc.php");

$transcode_args = array();
foreach($_POST as $key=>$value){
    if(!empty($value) && $key != 'preset' && $key != 'bitrate'){
        $transcode_args[] = $key.':'.$value;
    }
}
//BASE_URL is required otherwise it won't work!
$transcode_url = BASE_URL . "stream.php/".implode(';', $transcode_args);
$transcode_url .= '/'.basename($mediafile);
$transcode_url .= "?media=" . $_GET['media'];

if($_POST['container'] == 'ogg' && $mediatype == 'video')
    $transcode_url .= '.ogv';
else
    $transcode_url .= '.'.$_POST['container'];

if(!empty($_POST['size'])){
    $size = explode('x', $_POST['size']);
}
else {
	$size[0] = $mediainfo->width;
	$size[1] = $mediainfo->height;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link rel="shortcut icon" href="favicon.ico" >
	<title>php-live-transcode</title>
	<?php
	if ($_POST['player'] == 'flash') { //flowplayer (flash version)
		echo "<script src='flowplayer/flowplayer-3.2.12.min.js'></script>";
		echo "<style type='text/css' media='screen'>";
		echo "#flowplayer {";
			echo "display: block;";
			echo "width: " . $size[0] . "px;";
			echo "height: " . $size[1] . "px;";
		echo "}";
		echo "</style>\n";
	}
	else if ($_POST['player'] == 'flowplayer-html5') { //flowplayer (HTML5 version)
		//JQuery is required
		echo "<script type='text/javascript' src='./javascript/jquery-1.9.1.min.js'></script>\n";
		//player skin
		echo "<link rel='stylesheet' type='text/css' href='./flowplayerhtml5/skin/minimalist.css' />\n";
		echo "<script src='./flowplayerhtml5/flowplayer.min.js'></script>\n";
		//http://flowplayer.org/docs/#video-size
		echo "<style type='text/css' media='screen'>";
		echo ".flowplayer {";
			echo "width: " . $size[0] . "px;";
			echo "height: " . $size[1] . "px;";
		echo "}";
		echo "</style>\n";
	}
	?>
</head>
<body>
    <h1>Video Stream</h1>

	<a href="authenticate.php?status=logout"><img src="./images/logout.png" title="Logout" alt="Logout" /> Logout</a>
	<div id="header-nav">
		<p><a href="index.php">Select Video</a> -> <a href="create.php?media=<?php echo $_GET['media']; ?>">Pick Decoding Settings</a> -> <u>Watch Video</u></p>
	</div>

    <?php
	//http://diveintohtml5.info/video.html
	if ($_POST['player'] == 'html5') {
        if ($mediatype == "video") {
            echo "<video src='".$transcode_url."' width=".$size[0]." height=".$size[1]." preload controls> your browser must support html5/video tag</video>\n";
		}
        else {
            echo "<audio src='".$transcode_url."' preload controls> your browser must support html5/audio tag</audio>\n";
        }
	}
    else if ($_POST['player'] == 'flash') { //flowplayer (flash version)
        echo "<div id='flowplayer'></div>\n";
        echo "<script>\n";
			echo "flowplayer('flowplayer', '".BASE_URL."flowplayer/flowplayer-3.2.16.swf', {clip: {url: '".$transcode_url."'}});";
        echo "</script>\n";
    }
	else if ($_POST['player'] == 'flowplayer-html5') { //flowplayer (HTML5 version)
		//http://flowplayer.org/docs/
		//This will default to HTML5 but fallback to Flash if necessary
		echo "<div class='flowplayer'>\n";
   		echo "<video>\n";
      		echo "<source src='".$transcode_url."'/>\n";
   		echo "</video>\n";
		echo "</div>\n";
	}
	?>

<br><hr>
<center><a href="https://github.com/firefly2442/php-live-transcode/">php-live-transcode</a>
<br>Version: <?php echo VERSION;?></center>
</body>
</html>
