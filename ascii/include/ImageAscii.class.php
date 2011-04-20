<?php

// This code was realized totally by Caltabiano Salvatore 22/02/2007
// Before use this code off of local use, you have to ask to me about that.

class ImageAscii{
	var $blockw;
	var $blockh;
	var $data;
	var $txtCols;
	var $txtRows;
	var $x1;
	var $y1;
	var $x2;
	var $y2;
	var $contrast;
	var $brightness;
	var $stringChars;
	var $chars;
	var $file;
	var $filename;
	var $filenameimg;
	var $width;
	var $height;
	var $type;
	var $attr;
	var $im;
	var $TMP_PATH;
	var $IMGS_PATH;

	function ImageAscii()
	{
		$this->blockw = 8;
		$this->blockh = 13;
		$this->data = "";
		$this->txtCols = 80;
		$this->txtRows = 10;
		$this->x1 = 128; $this->y1 = 128;
		$this->x2 = 0; $this->y2 = 0;
		$this->contrast = 0;
		$this->brightness = 0;
		$this->stringChars = " \t.\t,\t-\tr\tv\t*\tc\ty\tx\ti\tL\tJ\tY\tt\t7\tl\tT\tu\tn\tf\to\t}\tC\tz\ts\tI\te\tV\tX\tk\th\tF\ta\tU\tA\tG\t4\t2\tm\tK\tZ\td\tb\tP\tS\tO\t5\tH\t3\t0\tD\tQ\t6\t9\t$\tR\tE\tN\tM\t#\t8\tW\t@";
		$this->chars = explode("\t",$this->stringChars);
		$this->chars = array_reverse($this->chars);
		$this->file = NULL;
		$this->filenameimg = "";
		$this->width = $this->blockw;
		$this->height = $this->blockh;
		$this->type = "";
		$this->attr = "";
		$this->im = NULL;
		$this->TMP_PATH = "temp/";
		$this->IMGS_PATH = "imgsUpLoaded/";
	}

	function load($file)
	{
		if ( strlen($file['tmp_name']) == 0 )
			return FALSE;

		if ( $file['type'] != "image/gif" && $file['type'] != "image/pjpeg" && $file['type'] != "image/jpeg" && $file['type'] != "image/x-png")
			return FALSE;

		@mkdir($this->TMP_PATH);

		$this->filenameimg = $file['tmp_name'];
		$this->filename = $file['name'];
		list($this->width, $this->height, $this->type, $this->attr) = getImageSize($this->filenameimg);

		switch ($this->type)
		{
			case 1:
				$this->im = imagecreatefromGif($this->filenameimg);
				$fileOut = $this->TMP_PATH.$this->filename.".jpg";
				imagejpeg($this->im,$fileOut,100);
				$this->im = imagecreatefromjpeg($fileOut);
				@unlink($fileOut);
				break;

			case 2:
				$this->im = imagecreatefromjpeg($this->filenameimg);
				break;

			case 3:
				$this->im = imagecreatefrompng($this->filenameimg);
				break;
		}
		@mkdir($this->IMGS_PATH);
		addImageFile($file, $this->IMGS_PATH, 0, 0);

		return TRUE;
	}

	function setBlock($blockw, $blockh)
	{
		$blockw = abs(intval($blockw));
		if ( $blockw > 0 )
			$this->blockw = $blockw;

		$blockh = abs(intval($blockh));
		if ( $blockh > 0 )
			$this->blockh = $blockh;

		return TRUE;
	}

	function setContrast($contrast, $brightness)
	{
		$contrast = intval($contrast);
		$brightness = intval($brightness);

		if ( $contrast>100 ) $contrast = 100;
		else
		if ( $contrast<-100 ) $contrast = -100;
		if ( $contrast < 0 )
			$this->y2 = -(intval($contrast * 1.27));
		else
			$this->x2 = intval($contrast * 1.27);

		if ( $brightness>50 ) $brightness = 50;
		else
		if ( $brightness<-50 ) $brightness = -50;

		$this->y1 = $this->y1 + intval($brightness * 5.11);
		$this->y2 = $this->y2 + intval($brightness * 5.11);

		$this->contrast = $contrast;
		$this->brightness = $brightness;

		return TRUE;
	}

	function convertImage()
	{
		$cr = 0; $cc = 0;
		$br = 0; $bc = 0;

		$pixelValue = 0;

		//this function read the image matrix and calculates the right greyscale for each color pixel
		for ( $cr=0 ; $cr<$this->height ; $cr+=$this->blockh )
		{
			for ( $cc=0 ; $cc<$this->width ; $cc+=$this->blockw )
			{
				$pixelValue = 0;
				for ( $br=0; ($br<$this->blockh && $cr+$br<$this->height) ; $br++ )
				{
					for ( $bc=0; ($bc<$this->blockw && $cc+$bc<$this->width && $cr+$br<$this->height) ; $bc++ )
					{
						$RGB = ImageColorAt($this->im, $cc+$bc, $cr+$br);
						$R = ($RGB >> 16) & 0xFF;
						$G = ($RGB >> 8) & 0xFF;
						$B = $RGB & 0xFF;
						$Y = ($R * 0.30 + $G * 0.59 + $B * 0.11);
						$pixelValue += $Y;
					}
				}
				$pixelValue = intval($pixelValue/($br*$bc));
				$pixelValue = $this->contrast($pixelValue,$this->x1,$this->y1,$this->x2,$this->y2);
				$this->data .= $this->chars[intval($pixelValue/4)];
			}
			$this->data .= "\n";
		}

		return TRUE;
	}

	function displayData()
	{
		$datapage = "";

		$this->txtCols = intval($this->width/$this->blockw)+4;
		$this->txtRows = intval($this->height/$this->blockh);

		$datapage .= "<textarea name=\"data\" rows=\"".$this->txtRows."\" cols=\"".$this->txtCols."\" style=\"overflow:auto;\">".$this->data."</textarea>";

		return $datapage;
	}

	function downloadData()
	{
		$fileOut = $this->filename.".txt";

		header("Content-type: text/plain");
		header("Content-transfer-encoding: binary");
		header("Content-Disposition: attachment; filename=".$fileOut);

		echo $this->data;
	}

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
}

?>