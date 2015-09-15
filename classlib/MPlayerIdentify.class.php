<?php

class MPlayerIdentify
{
    var $video_codec = null;
    var $video_bitrate = null;

    var $audio_codec = null;
    var $audio_bitrate = null;
    var $audio_rate = null;
    var $audio_streams = 0;

    var $width = null;
    var $height = null;

    var $fps = null;
    var $aspect = null;
	var $aspect_string = null;

	var $length = 0;

    private static $map = array(
        'ID_VIDEO_CODEC' => 'video_codec',
        'ID_VIDEO_BITRATE' => 'video_bitrate',

        'ID_AUDIO_CODEC' => 'audio_codec',
        'ID_AUDIO_BITRATE' => 'audio_bitrate',
        'ID_AUDIO_RATE' => 'audio_rate',

        'ID_VIDEO_WIDTH' => 'width',
        'ID_VIDEO_HEIGHT' => 'height',

        'ID_VIDEO_FPS' => 'fps',
        'ID_VIDEO_ASPECT' => 'aspect',

		'ID_LENGTH' => 'length'
    );

    function MPlayerIdentify($filename)
    {
		//https://www.linux.com/news/software/applications/292309-conqueror-video-on-linux-with-mplayer-
        $identify = shell_exec(
          MPLAYER.' -vo null -ao null -frames 1 -identify "'.$filename.'"|tac');

        preg_match_all('/(ID_[^=]+)=([\S]+)/m', $identify, $matches);
        foreach($matches[1] as $i=>$name){
            if(isset(MPlayerIdentify::$map[$name])){
                $var = MPlayerIdentify::$map[$name];
                if(!$this->$var)
                    $this->$var = $matches[2][$i];
            }
            if($name == 'ID_AUDIO_ID'){
                $this->audio_streams++;
            }
        }

		//If it's within +/- .03 of the aspect ratio, save special string
		if ($this->aspect > 1.74 && $this->aspect < 1.8) {
			$this->aspect_string = "16:9";
		} else if ($this->aspect > 1.57 && $this->aspect < 1.63) {
			$this->aspect_string = "16:10";
		} else if ($this->aspect > 1.3 && $this->aspect < 1.36) {
			$this->aspect_string = "4:3";
		}

    }
}


