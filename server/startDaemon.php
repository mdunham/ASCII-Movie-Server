<?php
header('Content-Type: text/html');
header('Cache-Control: no-cache');

$frames = array('','','','','','','','','','');

$len = strlen(sha1(md5('test')));

for ($frame = 0; $frame < 10; $frame++) {
	for ($index = 0; $index < ($len/2); $index++) {
		$frames[$frame] .= sha1(uniqid())."\n";
	}
}

echo json_encode($frames);

exit();