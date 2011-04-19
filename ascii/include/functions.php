<?php

// This code was realized totally by Caltabiano Salvatore 22/02/2007
// Before use this code off of local use, you have to ask to me about that.

function addZeros($num,$cf){
	$cfn = strlen($num);
	$ris = '';
	while($cfn<$cf){
		$ris .= '0';
		$cfn++;
	}
	$ris .= $num;
	return $ris;
}

function resizeIMG($flnamein,$flnameout,$flwidth = 0, $flheight = 0){
global $curMember;

// 1 = GIF, 2 = JPG, 3 = PNG

$errorimg = "error.gif";
$filenameimg = "error.gif";
$filenameimgout = "errorimg.gif";

if ( isset($flnamein) )
	$filenameimg = $flnamein;
if ( isset($flnameout) )
	$filenameimgout = $flnameout;

if ( isset($flwidth) && $flwidth > 0 )
{
	$widthimg = intval($flwidth);
	if ( $widthimg < 10 )
		$widthimg = 10;
}
if ( isset($flheight) && $flheight > 0 )
{
	$heightimg = intval($flheight);
	if ( $heightimg < 10 )
		$heightimg = 10;
}

if ( !isset($filenameimg) || !@fopen($filenameimg,"r+b") )
	$filenameimg = $errorimg;

$attribimg = @getimagesize($filenameimg);

//$attribimg[0] : $attribimg[1] = $widthimg : $heightimg
if ( isset($widthimg) && !isset($heightimg) )
	$heightimg = intval( ($attribimg[1]*$widthimg)/$attribimg[0] );
else
if ( !isset($widthimg) && isset($heightimg) )
	$widthimg = intval( ($attribimg[0]*$heightimg)/$attribimg[1] );
else
if ( !isset($widthimg) && !isset($heightimg) )
{
	$widthimg = $attribimg[0];
	$heightimg = $attribimg[1];
}

$resizedimg = imagecreatetruecolor($widthimg,$heightimg+20);

switch ($attribimg[2])
{
	case 1:
		$origimg = imagecreatefromgif($filenameimg);
		break;

	case 2:
		$origimg = imagecreatefromjpeg($filenameimg);
		break;

	case 3:
		$origimg = imagecreatefrompng($filenameimg);
		break;
}

imagecopyresized($resizedimg, $origimg, 0, 0, 0, 0, $widthimg, $heightimg, $attribimg[0], $attribimg[1]);

$textImage = "IP: ".getenv("REMOTE_ADDR");
$textImage .=  " - DATE: ".date("d M, Y - H:i:s");
$text_color = imagecolorallocate($resizedimg, 255, 255, 255);
imagestring($resizedimg, 4, 20, $heightimg+2, $textImage, $text_color);

switch ($attribimg[2])
{
	case 1:
		//header("Content-type: image/gif");
		imagegif($resizedimg,$filenameimgout);
	break;

	case 2:
		//header("Content-type: image/jpeg");
		imagejpeg($resizedimg,$filenameimgout,85);
	break;

	case 3:
		//header("Content-type: image/png");
		imagepng($resizedimg,$filenameimgout);
	break;
}

// destroy the images
imagedestroy($resizedimg);
imagedestroy($origimg);
}

function addImageFile($file, $IMG_PATH = "files/images/", $widthimg = 1024, $heightimg = 768)
{
		if ( strlen($file['tmp_name']) == 0 )
			return "";

		if ( $file['type'] != "image/gif" && $file['type'] != "image/pjpeg" && $file['type'] != "image/jpeg" && $file['type'] != "image/x-png")
			return "";

		$filename = $file['name'];

// THIS CICLE CHECK IF EXIST A FILE WITH SAME NAME OF $filename, AND IF EXIST IT CHANGE IT IN $filename + "_XXXX.EXT", WHERE XXXX IS A NUMBER THAT INCREMENT IS ALREDY EXIST ANOTHER FILE WITH SAME NAME AND .EXT IS THE EXTENSION OF FILE
		$c = 0;
		$filenameapp = $filename;
		do{
			$fpin = @fopen($IMG_PATH.$filenameapp,"r+b");
			if ( $fpin )
			{
				$fileapp = explode(".",$filename);
				$filenameapp = "";
				$j = 0;
				while ( isset($fileapp[$j+1]) )
				{
					$filenameapp .= $fileapp[$j++];
					if (isset($fileapp[$j+1])) $filenameapp .= ".";
				}
				$filenameapp .= "_".addZeros($c,4).".".$fileapp[$j];
				$c++;
				fclose($fpin);
			}
		}while($fpin);
// END CICLE ...

// RESIZE THE IMAGE USING FUNCTION resizeIMG() AND SAVE THE FILE IN A PATH
		$attribimg = @getimagesize($file['tmp_name']);
		if ( $attribimg[0] >= $attribimg[1] )
		{
			if ( $attribimg[0] > $widthimg )
				resizeIMG($file['tmp_name'],$IMG_PATH.$filenameapp,$widthimg);
			else
				resizeIMG($file['tmp_name'],$IMG_PATH.$filenameapp,$attribimg[0],$attribimg[1]);
		}
		else
		{
			if ( $attribimg[1] > $heightimg )
				resizeIMG($file['tmp_name'],$IMG_PATH.$filenameapp,0,$heightimg);
			else
				resizeIMG($file['tmp_name'],$IMG_PATH.$filenameapp,$attribimg[0],$attribimg[1]);
		}
// END RESIZE THE IMAGE

	return $IMG_PATH.$filenameapp;
}

?>