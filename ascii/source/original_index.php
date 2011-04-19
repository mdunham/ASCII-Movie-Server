<?php

// This code was realized totally by Caltabiano Salvatore 21/02/2007

// This function return the contrasted and brightnessed value of the color
function contrast($x, $x1, $y1, $x2, $y2)
{
	$m=0;
	$y=0;

	if ( $x2-$x1 != 0 )
		$m = (($y2-$y1)*1.0/($x2-$x1)*1.0);
	$y = ($m*($x-$x1)+$y1);

	if ($y < 0) $y = 0;
	if ($y > 255) $y = 255;

	return $y;
}

include "include/ImageAscii.class.php";

$data = "";
$txtCols = 80;
$txtRows = 10;

if ( isset($_POST['imgpix']) && isset($_FILES['image']))
{

$file = $_FILES['image'];

if ( strlen($file['tmp_name']) == 0 )
	header("Location: index.php");

if ( $file['type'] != "image/gif" && $file['type'] != "image/pjpeg" && $file['type'] != "image/jpeg" && $file['type'] != "image/x-png")
	header("Location: index.php");


$x1 = 128; $y1 = 128;
$x2 = 0; $y2 = 0;

// It fix the contrast and brithness value
if ( isset($_POST['contrast']) && isset($_POST['brightness']) )
{
	$contrast = intval($_POST['contrast']);
	$brightness = intval($_POST['brightness']);

	if ( $contrast>100 ) $contrast = 100;
	else
	if ( $contrast<-100 ) $contrast = -100;
	if ( $contrast < 0 )
		$y2 = -(intval($contrast * 1.27));
	else
		$x2 = intval($contrast * 1.27);

	if ( $brightness>50 ) $brightness = 50;
	else
	if ( $brightness<-50 ) $brightness = -50;
	$y1 = $y1 + intval($brightness * 5.11);
	$y2 = $y2 + intval($brightness * 5.11);
}

$stringChars = " \t.\t,\t-\tr\tv\t*\tc\ty\tx\ti\tL\tJ\tY\tt\t7\tl\tT\tu\tn\tf\to\t}\tC\tz\ts\tI\te\tV\tX\tk\th\tF\ta\tU\tA\tG\t4\t2\tm\tK\tZ\td\tb\tP\tS\tO\t5\tH\t3\t0\tD\tQ\t6\t9\t$\tR\tE\tN\tM\t#\t8\tW\t@";
$chars = explode("\t",$stringChars);
$chars = array_reverse($chars);

$filenameimg = $file['tmp_name'];
list($width, $height, $type, $attr) = getImageSize($filenameimg);

// Check with imagecreate use for the right image format
switch ($type)
{
	case 1:
		$im = imagecreatefromGif($filenameimg);
		break;

	case 2:
		$im = imagecreatefromjpeg($filenameimg);
		break;

	case 3:
		$im = imagecreatefrompng($filenameimg);
		break;
}

if ( isset($_POST['blockw']) )
{
	$blockw = abs(intval($_POST['blockw']));
	if ( $blockw < 1 )
		$blockw = 8;
}
else
	$blockw = 8;

if ( isset($_POST['blockh']) )
{
	$blockh = abs(intval($_POST['blockh']));
	if ( $blockh < 1 )
		$blockh = 13;
}
else
	$blockh = 13;

$blocks = $blockw*$blockh;

$cr = 0; $cc = 0;
$br = 0; $bc = 0;

$pixelValue = 0;

//this function read the image matrix and calculates the right greyscale for each color pixel
for ( $cr=0 ; $cr<$height ; $cr+=$blockh )
{
	for ( $cc=0 ; $cc<$width ; $cc+=$blockw )
	{
		$pixelValue = 0;
		for ( $br=0; ($br<$blockh && $cr+$br<$height) ; $br++ )
		{
			for ( $bc=0; ($bc<$blockw && $cc+$bc<$width && $cr+$br<$height) ; $bc++ )
			{
				$RGB = ImageColorAt($im, $cc+$bc, $cr+$br);
				$R = ($RGB >> 16) & 0xFF;
				$G = ($RGB >> 8) & 0xFF;
				$B = $RGB & 0xFF;
				$Y = ($R * 0.30 + $G * 0.59 + $B * 0.11);
				$pixelValue += $Y;
			}
		}
		$pixelValue = intval($pixelValue/($br*$bc));
		$pixelValue = contrast($pixelValue,$x1,$y1,$x2,$y2);
		$data .= $chars[intval($pixelValue/4)];
	}
	$data .= "\n";
}

$fileOut = $file['name'].".txt";

if ( isset($_POST['saveas']) )
{
	header("Content-type: text/plain");
	header("Content-transfer-encoding: binary");
	header("Content-Disposition: attachment; filename=".$fileOut);

	echo $data;

	return;
}

$txtCols = intval($width/$blockw)+4;
$txtRows = intval($height/$blockh);

}


{

$j = 0;
$menu1 = "<select name=\"contrast\">\n";
for ( $j=-100 ; $j<=100 ; $j++ )
{
	$menu1 .= " <option value=\"".$j."\"";
	if ($j==80) $menu1 .= " SELECTED";
	$menu1 .= ">".$j."</option>\n";
}
$menu1 .= "</select>";

$menu2 = "<select name=\"brightness\">\n";
for ( $j=-50 ; $j<=50 ; $j++ )
{
	$menu2 .= " <option value=\"".$j."\"";
	if ($j==35) $menu2 .= " SELECTED";
	$menu2 .= ">".$j."</option>\n";
}
$menu2 .= "</select>";

$datapage = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"it\" lang=\"it\" dir=\"ltr\">

<head>
<title>WEB IMAGE to ASCII - By Caltabiano Salvatore</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
<style type=\"text/css\">
body{
 font-family:Courier New, Lucida Console, verdana, serif, times new roman;
}

.tab1{
 border-style:solid;
 border-width:1px;
 border-color:#000000;
}

textarea{
 font-family: Lucida Console, courier new, serif, times new roman;
 font-size:6px;
}
</style>
</head>

<body>
<h1>WEB IMAGE to ASCII</h1>
<h4>By Caltabiano Salvatore 21/02/2007</h4>
<hr />
<img alt=\"This picture show you how to use this software\" src=\"help.gif\" border=\"0\" align=\"right\" />
<form action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">
<table cellspacing=\"3\" cellpadding=\"3\" bgcolor=\"#CCCCCC\" WIDTH=\"500\" border=\"0\" class=\"tab1\">
 <tr>
  <td><b>CHAR SIZE BLOCK:</b></td><td><input name=\"blockw\" size=\"1\" value=\"8\"> x <input name=\"blockh\" size=\"1\" value=\"13\"> Pixels</td>
 </tr>
 <tr>
  <td><b>BRIGHTNESS:</b></td><td>".$menu2." ( -50 - 0 - 50 )</td>
 </tr>
 <tr>
  <td><b>CONTRAST:</b></td><td>".$menu1." ( -100 - 0 - 100 )</td>
 </tr>
 <tr>
  <td><b>FILE:</b> (jpg, gif, png)</td><td><input type=\"file\" name=\"image\"><br\n></td>
 </tr>
 <tr>
  <td colspan=\"2\"><hr /></td>
 </tr>
 <tr>
  <td><input type=\"hidden\" name=\"imgpix\" value=\"1\"><input type=\"submit\" value=\"CONVERT\"></td><td><input type=\"submit\" value=\"CONVERT and SAVE AS\" name=\"saveas\"></td>
 </tr>
</table>
</form>

<hr />\n";

if (isset($_POST['imgpix'])) $datapage .= "<textarea name=\"data\" rows=\"".$txtRows."\" cols=\"".$txtCols."\" style=\"overflow:auto;\">".$data."</textarea>
<hr />\n";

$datapage .= "<p>Thanks and have fun! - <a href=\"mailto:caltabianosalvatore@interfree.it?subject=Risposta dal programma PGMTXT_2.EXE\">E-Mail</a></p>
<hr />
 <p>
    <a href=\"http://validator.w3.org/check?uri=referer\"><img
        src=\"http://www.w3.org/Icons/valid-xhtml10\"
        alt=\"Valid XHTML 1.0 Transitional\" height=\"31\" width=\"88\" border=\"0\" /></a>
  </p>
</body>
</html>";

echo $datapage;
}
?>