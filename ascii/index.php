<?php

// This code was realized totally by Caltabiano Salvatore 22/02/2007
// Before use this code off of local use, you have to ask to me about that.

$data = "";

if ( isset($_POST['imgpix']) && isset($_FILES['image']))
{
	include "include/functions.php";
	include "include/ImageAscii.class.php";

	$imgAscii = new ImageAscii();

	if ( $imgAscii->load($_FILES['image']) )
	{
		
		$imgAscii->convertImage();

		if ( isset($_POST['saveas']) )
		{
			$imgAscii->downloadData();
			return;
		}

		$data .= $imgAscii->displayData();
	}
}


{

$j = 0;
$menu1 = "<select name=\"contrast\">\n";
for ( $j=-100 ; $j<=100 ; $j++ )
{
	$menu1 .= " <option value=\"".$j."\"";
	//if ($j==80) $menu1 .= " SELECTED";
	$menu1 .= ">".$j."</option>\n";
}
$menu1 .= "</select><script type=\"text/javascript\">document.all.contrast.value = 80;</script>";

$menu2 = "<select name=\"brightness\">\n";
for ( $j=-50 ; $j<=50 ; $j++ )
{
	$menu2 .= " <option value=\"".$j."\"";
	//if ($j==35) $menu2 .= " SELECTED";
	$menu2 .= ">".$j."</option>\n";
}
$menu2 .= "</select><script type=\"text/javascript\">document.all.brightness.value = 35;</script>";

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
<table cellspacing=\"3\" cellpadding=\"3\" bgcolor=\"#CCCCCC\" border=\"0\" class=\"tab1\">
 <tr>
  <td><b>CHAR SIZE BLOCK:</b></td><td><input name=\"blockw\" size=\"1\" value=\"8\" /> x <input name=\"blockh\" size=\"1\" value=\"13\" /> Pixels</td>
 </tr>
 <tr>
  <td><b>BRIGHTNESS:</b></td><td>".$menu2." ( -50 - 0 - 50 )</td>
 </tr>
 <tr>
  <td><b>CONTRAST:</b></td><td>".$menu1." ( -100 - 0 - 100 )</td>
 </tr>
 <tr>
  <td><b>FILE:</b> (jpg, gif, png)</td><td><input type=\"file\" name=\"image\" /><br /></td>
 </tr>
 <tr>
  <td colspan=\"2\"><hr /></td>
 </tr>
 <tr>
  <td><input type=\"hidden\" name=\"imgpix\" value=\"1\" /><input type=\"submit\" value=\"CONVERT\" /></td><td><input type=\"submit\" value=\"CONVERT and SAVE AS\" name=\"saveas\" /></td>
 </tr>
</table>
</form>

<hr />\n";

if ( strlen($data) > 0 ) $datapage .= $data."\n<hr />\n";

$datapage .= "<p>Thanks and have fun! - <a href=\"mailto:caltabianosalvatore@interfree.it?subject=Answer from WEB IMAGE to ASCII\">E-Mail</a></p>
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