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



/* used for transcoding*/
define('FFMPEG', 'ffmpeg');
/* used to gather file information */
define('MPLAYER', 'mplayer');

/* how many ffmpeg instances are allowed to run (thats x concurrent viewers) */
define('FFMPEG_MAX_INSTANCES', '3');

/* how many threads should ffmpeg use? 0 means that it will select the optimal number of threads */
//This doesn't work for some codecs
//define('FFMPEG_THREADS', 0);

/* man nice */
define('FFMPEG_PRIORITY', '15');

/* how many bytes should fread() read from stdout of FFmpeg? */
define('CHUNKSIZE', 500*1024);

/* very useful debug information. logs stderr of ffmpeg! Make sure this is writeable by
   your server */
define('DEBUG_LOG', 'logs/output.log');

/* the folder where all the media to be served up is
   make sure php can read it (openbasedir etc.) */
define('MEDIA_PATH', '/srv/media');

/* This will limit the displaying and linking to files with these extensions on the index.php page. */
$mediatypes = array(".mkv", ".avi", ".mov", ".mpg", "mpeg", ".ogv", ".ogg");
define("MEDIATYPES", serialize($mediatypes));

/* Full URL path.  Note that this is the only URL through which the streaming will probably work.
   If you are doing testing via localhost or via a local network, make sure the URL here is set
   appropriately. */
define("BASE_URL", "http://yourdomainhere.com/php-live-transcode/");


/* If password is left blank, NO authentication will
	be required and the page will be visible to anyone.
	However, if it is NOT blank, the only
	way to view the page is by entering the correct password.
	This password is in cleartext, if other people can access
	this file, this is NOT secure.  For true security, use
	Apache digest authentication or other methods such as
	an htaccess file. */
define("PASSWORD", "secret");

/* generate number of thumbnails for preview, can be taxing on CPU
	must be numeric value, set to 0 for no thumbnails */
define("THUMBNAILS", "6");


/* php-live-transcode version (you don't need to change this) */
define("VERSION", "0.1");
