<?php
/*
    PHP Live Transcode -- HTML5 and Flash streaming with live transcoding.
    Copyright (C) 2010  Matthias -apoc- Hecker <http://apoc.cc>

	Forked by firefly2442
	8/31/11
	https://github.com/firefly2442/php-live-transcode

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once("checklogin.inc.php");

require_once ("config.inc.php");

/* just for internal use */
define('P_STDIN', 0);
define('P_STDOUT', 1);
define('P_STDERR', 2);



/* define some modifiers for bitrate conversion */
function kbyte($bits){
    return round($bits/8/1024, 2);
}
function kbit($bits){
    return round($bits/1000, 2);
}

function secondsToString($seconds) {
	$units = array(
            "hour"   =>      3600,
            "minute" =>        60,
            "second" =>         1,
    );

	// specifically handle zero
    if ( $seconds == 0 ) return "0 seconds";

    $s = "";

    foreach ( $units as $name => $divisor ) {
        if ( $quot = intval($seconds / $divisor) ) {
            $s .= "$quot $name";
            $s .= (abs($quot) > 1 ? "s" : "") . ", ";
            $seconds -= $quot * $divisor;
        }
    }

    return substr($s, 0, -2);
}

function join_paths() {
	//since we don't have anything like os.path.join in python    
	$paths = array();
    foreach (func_get_args() as $arg) {
        if ($arg !== '') { $paths[] = $arg; }
    }
    return preg_replace('#/+#','/',join('/', $paths));
}


/* just a very simple debug logger */
function dbg($message)
{
    file_put_contents(DEBUG_LOG, time().": $message\n", FILE_APPEND);
}


function errorMessage($message)
{
	echo "Error:\n";
	echo htmlentities($message);
	exit;
}




/* test for mplayer */
if(!preg_match('/mplayer (\S+)/', strtolower(shell_exec(MPLAYER)), $mplayer_version)){
    errorMessage("MPlayer not found. (".MPLAYER.")");
}
define('MPLAYER_VERSION', $mplayer_version[1]);

/* test for ffmpeg or avconv transcoder */
if(preg_match('/ffmpeg (\S+)/', strtolower(shell_exec(FFMPEG.' -version')), $ffmpeg_version)){
	define('FFMPEG_VERSION', $ffmpeg_version[1]);
}
if(preg_match('/avconv (\S+)/', strtolower(shell_exec(AVCONV.' -version')), $avconv_version)){
	define('AVCONV_VERSION', $avconv_version[1]);
}

if(!defined("FFMPEG_VERSION") && !defined("AVCONV_VERSION")) {
     errorMessage("ffmpeg not found: (".FFMPEG.") - avconv not found: (".AVCONV.")");
}


/* check running ffmpeg/avconv instances */
$instances_ffmpeg = `ps -A | grep ffmpeg | wc -l`;
$instances_ffmpeg = (int)(trim($instances_ffmpeg));
$instances_avconv = `ps -A | grep avconv | wc -l`;
$instances_avconv = (int)(trim($instances_avconv));
$instances = $instances_avconv + $instances_ffmpeg;
if ($instances > MAX_INSTANCES){
    errorMessage("There are too many running instances of ffmpeg/avconv. (".$instances."/".MAX_INSTANCES.")");
}

if(!is_writable("./images/screenshots/")) {
	errorMessage("The ./images/screenshots/ folder is not writeable by the webserver.");
}
if(file_exists(DEBUG_LOG) && !is_writable(DEBUG_LOG)) {
	errorMessage(DEBUG_LOG." is not writeable by the webserver.");
}


/* gather mediafile */
if(isset($_GET["media"]) && $_GET["media"] != ""){
	$mediafile = join_paths(MEDIA_PATH, urldecode($_GET["media"]));

	if(strstr($mediafile, '..') !== false){ /* directory traversal */
		errorMessage("Illegal characters in directory traversal."); //security feature
	}
	$mediafilename = basename($mediafile);
	if (preg_match('/\.([^\.]+)$/', $mediafilename, $match) >= 2)
	{
		$mediaext = $match[1];
	}
	if(!file_exists($mediafile)){
		/* look for existing mediafile without last extension */
		$mediafile = preg_replace('/\.[^\.]+$/','',$mediafile);
		if(!file_exists($mediafile)){
		    errorMessage("Mediafile does not exist. (".htmlentities($mediafile).")");
		}
	}

	/* identify the mediafile with mplayer */
	require ("classlib/MPlayerIdentify.class.php");
	$mediainfo = new MPlayerIdentify($mediafile);
	if(empty($mediainfo->audio_codec)){
		errorMessage('MPlayer has not identified the file you provided. ('.htmlentities($mediafile).')');
	}

	/* assign mediafile information */
	define('filename', basename($mediafile));
	define('path', dirname($mediafile));
	define('size', round(filesize($mediafile) / 1024 / 1024, 2));

	if(empty($mediainfo->video_codec))
		$mediatype = "audio";
	else
		$mediatype = "video";
}
else
{
	echo "<p class='error'>You didn't specify a file.</p>";
	exit();
}

