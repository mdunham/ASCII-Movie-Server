<?php
header('Content-Type: text/html');
header('Cache-Control: no-cache');

$frame = $_GET['start'];
$frames = 5;
$ascii = array();

if (($frame + 5) > 177) {
	$frames = 176 - $frame;
}


for ($index = 0; $index < $frames; $index++) {
	$ascii[] = strip_tags(file_get_contents(dirname(dirname(__FILE__)).'/server/frames/'.($index+$frame)));
}

echo json_encode($ascii);

exit();