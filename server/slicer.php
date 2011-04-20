<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

echo exec('/Users/mdunham/ffmpeg/ffmpeg -i '.dirname(__FILE__).'/n.m4v -an -r 5 -vframes 20000 -y '.dirname(__FILE__).'/slides/%d.jpg');

?>
Done