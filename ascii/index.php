<?php


$data = "";
date_default_timezone_set('UTC');
set_time_limit(700);
	include "include/functions.php";
	include "include/ImageAscii.class.php";


	foreach (glob(dirname(dirname(__FILE__))."/server/slides/*.jpg") as $index => $filename) {
		$file = array(
			'type' => 'image/jpeg',
			'tmp_name' => $filename,
			'name' => basename($filename)
		);
			$imgAscii = new ImageAscii();
$imgAscii->setContrast(-3, 17);
$imgAscii->setBlock(3, 5);
		echo 'Opened '. $filename ."\n";
		if ( $imgAscii->load($file) ){
			$imgAscii->convertImage();
			$data = $imgAscii->displayData();
			$save = dirname(dirname(__FILE__)).'/server/frames/'.str_replace(array(' ', '.jpg'), '', strtolower(basename($filename)));
					echo 'Saved '.  $save."\n";

			$f = fopen($save, 'w');
			fwrite($f, $data);
			fclose($f);
		}
	}
	

?>