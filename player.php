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
	<script src="flowplayer/flowplayer-3.2.6.min.js"></script>

	<style type="text/css" media="screen">
	#flowplayer {
		display: block;
		width: <?php echo $size[0]; ?>px;
		height: <?php echo $size[1]; ?>px;
	}
	</style>
</head>
<body>
    <h1>Video Stream</h1>

    <?php
	if ($_POST['player'] == 'html5') {
        if ($mediatype == "video") {
            echo "<video src='".$transcode_url."' width=".$size[0]." height=".$size[1]." preload controls> your browser must support html5/video tag</video>\n";
		}
        else {
            echo "<audio src='".$transcode_url."' preload controls> your browser must support html5/audio tag</audio>\n";
        }
	}
    else if ($_POST['player'] == 'flash') {
        echo "<div id='flowplayer'></div>\n";
        echo "<script>\n";
            echo "flowplayer('flowplayer', '".BASE_URL."flowplayer/flowplayer-3.2.7.swf', {clip: {url: '".$transcode_url."'}});";
        echo "</script>\n";
    }
	?>

<br><hr>
<center><a href="http://www.rivetcode.com">php-live-transcode</a>
<br>Version: <?php echo VERSION;?></center>
</body>
</html>
