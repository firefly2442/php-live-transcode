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

require_once("includes/checklogin.inc.php");

require_once("includes/init.inc.php");

require_once("includes/pull-screenshots.inc.php");

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link rel="shortcut icon" href="favicon.ico" >
	<title>php-live-transcode</title>
	<script type="text/javascript" src="./javascript/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="./javascript/create.js"></script>
</head>
<body>

    <h1>Create Stream</h1>

	<a href="authenticate.php?status=logout"><img src="./images/logout.png" title="Logout" alt="Logout" /> Logout</a>
	<div id="header-nav">
		<p><a href="index.php">Select Video</a> -> <u>Pick Decoding Settings</u> -> Watch Video</p>
	</div>

	<?php

	if (THUMBNAILS > 0)
	{
		//check the permissions to make sure we can write to screenshots directory
		if (is_writeable("./images/screenshots/")) {
			//generate preview screenshots from the video
			saveScreenshots($mediainfo);

			//display images
			for ($i = 1; $i < THUMBNAILS+1; $i++) {
				echo "<img src='./images/screenshots/".filename."_".$i.".jpg' />\n";
			}
		} else {
			echo "<p>Insufficient privileges to generate thumbnail preview images.</p>\n";
		}
	}

	?>

	<div id="codecs">
		<table border="0">
			<tr>
				<th>Codec</th><th>Browser Supported</th>
			</tr>
				<tr><td>MPEG-4</td><td>
					<div id="mpeg4"><img src="./images/positive.png" alt="Supported" title="Supported" /></div>
					<div id="mpeg4-unsupported"><img src="./images/negative.png" alt="Unsupported" title="Unsupported" /></div>
				</td></tr>
				<tr><td>H264</td><td>
					<div id="h264"><img src="./images/positive.png" alt="Supported" title="Supported" /></div>
					<div id="h264-unsupported"><img src="./images/negative.png" alt="Unsupported" title="Unsupported" /></div>
				</td></tr>
				<tr><td>Theora</td><td>
					<div id="theora"><img src="./images/positive.png" alt="Supported" title="Supported" /></div>
					<div id="theora-unsupported"><img src="./images/negative.png" alt="Unsupported" title="Unsupported" /></div>
				</td></tr>
				<tr><td>Webm</td><td>
					<div id="webm"><img src="./images/positive.png" alt="Supported" title="Supported" /></div>
					<div id="webm-unsupported"><img src="./images/negative.png" alt="Unsupported" title="Unsupported" /></div>
				</td></tr>
		</table>
	</div>

	<script>
		//default all to unsupported
		$("#mpeg4").hide();
		$("#h264").hide();
		$("#theora").hide();
		$("#webm").hide();

		//http://diveintohtml5.info/video.html
		//check for browser codec support
		var testEl = document.createElement("video"), mpeg4, h264, theora, webm;
		if ( testEl.canPlayType ) {
		    // Check for MPEG-4 support
		    var typeStr = testEl.canPlayType('video/mp4; codecs="mp4v.20.8"');
			if (typeStr !== "") {
				$("#mpeg4").show();
				$("#mpeg4-unsupported").hide();
			}

		    // Check for h264 support
		    typeStr = (testEl.canPlayType('video/mp4; codecs="avc1.42E01E"') || testEl.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"'))
			if (typeStr !== "") {
				$("#h264").show();
				$("#h264-unsupported").hide();
			}

			// Check for OGG theora support
			typeStr = testEl.canPlayType('video/ogg; codecs="theora"')
			if (typeStr !== "") {
				$("#theora").show();
				$("#theora-unsupported").hide();
			}

		    // Check for Webm support
			//http://www.webmproject.org/code/specs/container/
		    typeStr = testEl.canPlayType('video/webm; codecs="vp8, vorbis"'); //TODO: is the vorbis codec necessary here? does it check for one or the other?
			if (typeStr !== "") {
				$("#webm").show();
				$("#webm-unsupported").hide();
			}
		}
	</script>


    <ul id="mediainfo">
        <li><strong>Filename:</strong> <?php echo htmlentities(filename); ?></li>
        <li><strong>Path:</strong> <?php echo htmlentities(path); ?></li>
		<li><strong>Length:</strong> <?php echo secondsToString(htmlentities($mediainfo->length)); ?></li>
        <li><strong>Size:</strong> <?php echo htmlentities(size); ?> MByte</li>

        <li><strong>Video Codec:</strong> <?php echo htmlentities($mediainfo->video_codec); ?></li>
        <li><strong>Video Bitrate:</strong> <?php echo htmlentities(kbit($mediainfo->video_bitrate))." kBit/s (".htmlentities(kbyte($mediainfo->video_bitrate))." kByte/s)"; ?></li>

        <li><strong>Audio Codec:</strong> <?php echo htmlentities($mediainfo->audio_codec); ?></li>
        <li><strong>Audio Bitrate:</strong> <?php echo htmlentities(kbit($mediainfo->audio_bitrate))." kBit/s (".htmlentities(kbyte($mediainfo->audio_bitrate))." kByte/s)"; ?></li>
        <li><strong>Audio Rate:</strong> <?php echo htmlentities($mediainfo->audio_rate); ?> Hz</li>
        <li><strong>Audio Streams:</strong> <?php echo htmlentities($mediainfo->audio_streams); ?></li>

        <li><strong>Width:</strong> <?php echo htmlentities($mediainfo->width); ?></li>
        <li><strong>Height:</strong> <?php echo htmlentities($mediainfo->height); ?></li>

        <li><strong>FPS:</strong> <?php echo htmlentities($mediainfo->fps); ?></li>
        <li><strong>Aspect Ratio:</strong> <?php echo htmlentities($mediainfo->aspect);

		if ($mediainfo->aspect_string != null) {
			echo " (" . htmlentities($mediainfo->aspect_string) . ")";
		}

		?></li>
    </ul>


    <form name="create_form" method="POST" action="player.php?media=<?php echo htmlentities($_GET['media']); ?>">
        <label for="player">Select Player Technology</label>
        <select name="player" id="player">
            <option value="html5">HTML5</option>
            <option value="flash">Flash (flowplayer)</option>
			<option value="flowplayer-html5">HTML5 (flowplayer)</option>
        </select>

        <label for="bitrate">Set Bitrate (Video Bitrate / Audio Bitrate)</label>
        <select name="bitrate" id="bitrate">
            <option value="0">Keep Same</option>
            <option value="300/48">43 kbyte/s (300 kbit/s / 48 kbit/s)</option>
            <option value="600/64">83 kbyte/s (600 kbit/s / 64 kbit/s)</option>
            <option value="1000/96">137 kbyte/s (1000 kbit/s / 96 kbit/s)</option>
        </select>

        <div id="buttons">
            <input type="submit" id="create_stream" value="Create Stream" />
            <input type="reset" value="Reset settings to default" />
        </div>

        <div id="expert_toggle">
            <a href="#please_activate_js">Show Transcode Settings</a>
            <a href="#please_activate_js" style="display: none;">Hide Transcode Settings</a>
        </div>

        <div id="expert" style="display: none;">
            <h2>Transcode Settings</h2>

            <label for="preset">Set Preset</label>
            <select name="preset" id="preset">
                <option value="0">Video Codec / Audio Codec / Container</option>
                <option value="flv/libmp3lame/flv">Sorenson Spark / MP3 / FLV</option>
                <option value="libx264/libfaac/flv">H.264 / AAC / FLV (?)</option>
                <!-- <option value="libx264/libfaac/mp4">H.264 / AAC / MP4</option> -->
                <option value="libtheora/libvorbis/ogg">Theora / Vorbis / OGG</option>
                <option value="libvpx/libvorbis/webm">VP8 / Vorbis / WebM</option>
            </select>

            <div id="video_row">

                <label for="vcodec">Select Video Codec</label>
                <select name="vcodec" id="vcodec">
                    <option value="">Keep Same</option>
                    <option value="flv">Sorenson Spark (flv)</option>
                    <option value="libx264">H.264 (libx264)</option>
                    <option value="libtheora" selected="selected">Theora (libtheora)</option>
                    <option value="libvpx">VP8 (libvpx)</option>
                </select>

                <label for="vb">Video Bitrate (kbit/s)</label>
                <input type="text" name="vb" id="vb" value="<?php echo kbit($mediainfo->video_bitrate); ?>" />
                <div id="vb_byte">(0 kbyte/s)</div>

                <label for="aspect">Select Aspect Ratio</label>
                <select name="aspect" id="aspect">
                    <option value="">Keep Same</option>
                    <option>4:3</option>
                    <option>16:9</option>
                    <option>16:10</option>
                </select>

                <label for="size">Change Resolution</label>
                <select name="size" id="size">
                    <option value="">Keep Same</option>
                    <option>320x240</option>
                    <option>352x288</option>
                    <option>640x480</option>
                    <option>800x600</option>
                </select>

                <label for="fps">Change FPS</label>
                <input type="text" name="fps" id="fps" value="<?php echo $mediainfo->fps; ?>" />


                <label for="seek">Seek to Position (HH:MM:SS)</label>
                <input type="text" name="seek" id="seek" value="00:00:00" />

                <div id="vpre_options" style="display: none;">
                    <label for="vpre">Select Preset (x264)</label>
                    <select name="vpre" id="vpre">
                        <option>ultrafast</option>
                        <option>superfast</option>
                        <option selected="selected">veryfast</option>
                        <option>faster</option>
                        <option>fast</option>
                        <option>medium</option>
                        <option>slow</option>
                        <option>slower</option>
                        <option>veryslow</option>
                        <option>placebo</option>
                    </select>
                    <label for="vpre2">Select Preset (x264)</label>
                    <select name="vpre2" id="vpre2">
                        <option>main</option>
                        <option selected="selected">baseline</option>
                    </select>
                </div>

                <label for="container">Select Container</label>
                <select name="container" id="container">
                    <option value="flv">flv</option>
                    <!-- <option value="mp4">mp4</option> -->
                    <option value="ogg" selected="selected">ogg</option>
                    <option value="webm">webm</option>
                    <option value="mkv">mkv</option>
                    <option value="mp3">mp3</option>
                </select>

            </div>

            <div id="audio_row">

                <label for="acodec">Select Audio Codec</label>
                <select name="acodec" id="acodec">
                    <option value="">Keep Same</option>
                    <option value="libmp3lame">MP3 (libmp3lame)</option>
                    <option value="libvorbis" selected="selected">Vorbis (libvorbis)</option>
                    <option value="libfaac">AAC (libfaac)</option>
                </select>

                <label for="ab">Audio Bitrate (kbit/s)</label>
                <input type="text" name="ab" id="ab" value="<?php echo kbit($mediainfo->audio_bitrate); ?>" />
                <div id="ab_byte">(0 kbyte/s)</div>

                <label for="audio_stream">Select Audio Stream</label>
                <select name="audio_stream" id="audio_stream">
				<?php
					for ($i = 1; $i <= $mediainfo->audio_streams; $i++)
					{
						echo "<option value='".$i."'>\n";
						echo $i . "</option>\n";
					}
				?>
                </select>

            </div>

        </div>

    </form>

<br><hr>
<center><a href="http://www.rivetcode.com">php-live-transcode</a>
<br>Version: <?php echo VERSION;?></center>
</body>
</html>


