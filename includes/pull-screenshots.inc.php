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


function saveScreenshots($mediainfo)
{
	/* -ss time in seconds
		-i input video
		-y overwrite any files that are there
		-vframes only grab 1 frame
		-s thumbnail size
	*/

	if(defined("FFMPEG_VERSION")) {
		$transcoder = FFMPEG;
	} else if (defined("AVCONV_VERSION")) {
		$transcoder = AVCONV;
	}

	//if the file already exists, don't bother with regenerating thumbnails
	if (!file_exists("./images/screenshots/".filename."_1.jpg"))
	{
		//Pick 5 evenly spaced screenshots from the video
		for ($i = 1; $i < THUMBNAILS+1; $i++)
		{
			$time = getTime($mediainfo, $i);

			//http://ffmpeg.org/ffmpeg.html
			//http://www.php-code.net/2010/07/capturing-multiple-thumbnails-from-a-movie-using-ffmpeg/

			//use appropriate aspect ratio for thumbnails
			if ($mediainfo->aspect_string == "16:9" || $mediainfo->aspect_string == "16:10") { //use widescreen 16:9 aspect ratio
				$transcode_query = $transcoder." -ss ".$time." -i '".realpath(path."/".filename)."' -y -vframes 1 -s 480x240 ./images/screenshots/".filename."_".$i.".jpg";
			} else { //default to 4:3
				$transcode_query = $transcoder." -ss ".$time." -i '".realpath(path."/".filename)."' -y -vframes 1 -s 480x360 ./images/screenshots/".filename."_".$i.".jpg";
			}


			//https://www.linux.com/news/software/applications/292309-conqueror-video-on-linux-with-mplayer-
			//This works and is fast but you can't set the resolution or the filename
			//$mplayer_query = MPLAYER.' -nosound -vo jpeg:outdir=./images/screenshots/ -frames 1 -ss '.$time.' '.path.'/'.filename;

			$console = shell_exec($transcode_query);
		}
	}
}

function getTime($mediainfo, $i)
{
	return round(($i/(THUMBNAILS+1)) * $mediainfo->length);
}

function convertSecondsToString($seconds)
{
	$h = str_pad($seconds / 3600 % 24, 2, "0", STR_PAD_LEFT);
	$m = str_pad($seconds / 60 % 60, 2, "0", STR_PAD_LEFT);
	$s = str_pad($seconds % 60, 2, "0", STR_PAD_LEFT);
	return $h.":".$m.":".$s;
}
