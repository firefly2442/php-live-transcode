<?php
/*
    PHP Live Transcode -- HTML5 and Flash streaming with live transcoding.
    Copyright (C) 2010  Matthias -apoc- Hecker <http://apoc.cc>

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

/* Please read
 http://sixserv.org/2010/11/30/live-transcoding-for-video-and-audio-streaming/ 
   for more information. */

require('init.inc.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Video and Audio Streaming</title>
<script type="text/javascript" src="<?php echo BASE_URL; ?>/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>/create.js"></script>
<style type="text/css" media="screen">

a { text-decoration: none; }
a:hover { text-decoration: underline; }
a, a:hover, a:visited { color: grey; }

h1 {

}

input {
    border: 1px solid #999;
    background-color: #e7e7e7;
}

#buttons input {
    font-size: 14px;
    font-weight: bold;

    padding: 5px;
}

label {
    display: block;
    margin-top: 8px;
    font-weight: bold;
}

#expert_toggle {
    padding: 8px 8px 0px 0px;
}

#expert {
    display: table-cell;
    border: 1px solid #999;
    padding: 5px 50px 5px 5px;
    background-color: #ededed;
}

#expert h2 {
    margin: 0 0 10px 0;
}

#ab, #vb {
    width: 60px;
}

#ab_byte, #vb_byte {
    display: inline;
    font-style: italic;
    font-size: 10px;
}

#mediainfo {
    list-style-type: square;
    margin-left: 15px;
    padding-left: 10px;
}

#create_stream {
    margin-top: 8px;
}

#video_row {
    display: table-cell;
}

#audio_row {
    display: table-cell;
    padding-left: 15px;
}

</style>
</head>
<body>
    <h1>Create Stream</h1>

    <ul id="mediainfo">
        <li><strong>Filename:</strong> <?php echo filename; ?></li>
        <li><strong>Path:</strong> <?php echo path; ?></li>
        <li><strong>Size:</strong> <?php echo size; ?> MByte</li>

        <li><strong>Video Codec:</strong> <?php echo $mediainfo->video_codec; ?></li>
        <li><strong>Video Bitrate:</strong> <?php echo kbit($mediainfo->video_bitrate)." kBit/s (".kbyte($mediainfo->video_bitrate)." kByte/s)"; ?></li>

        <li><strong>Audio Codec:</strong> <?php echo $mediainfo->audio_codec; ?></li>
        <li><strong>Audio Bitrate:</strong> <?php echo kbit($mediainfo->audio_bitrate)." kBit/s (".kbyte($mediainfo->audio_bitrate)." kByte/s)"; ?></li>
        <li><strong>Audio Rate:</strong> <?php echo $mediainfo->audio_rate; ?> Hz</li>
        <li><strong>Audio Streams:</strong> <?php echo $mediainfo->audio_streams; ?></li>

        <li><strong>Width:</strong> <?php echo $mediainfo->width; ?></li>
        <li><strong>Height:</strong> <?php echo $mediainfo->height; ?></li>

        <li><strong>FPS:</strong> <?php echo $mediainfo->fps; ?></li>
        <li><strong>Aspect:</strong> <?php echo $mediainfo->aspect; ?></li>
    </ul>


    <form name="create_form" method="POST" action="<?php echo BASE_URL . "player.php".$_SERVER['PATH_INFO']; ?>">
        <label for="player">Select Player Technology</label>
        <select name="player" id="player">
            <option value="html5">HTML5</option>
            <option value="flash">Flash (flowplayer)</option>
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

</body>
</html>



