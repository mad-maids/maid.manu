---
toc: false
title: "PHP yordamida rasm chizish."
description: "PHP dasturlash tilida grafik kutubxona yordamida rasm chizish."
keywords: "php, gd, kutubxona, image, chizish"
---

PHP dasturlash tilida grafik kutubxona yordamida rasm chizish.

![php image](https://i.ibb.co/zhRWWZ0/image.png)

```php
<?php

header("Content-type: image/png");

$img_width = 800;
$img_height = 600;
 
$img = imagecreatetruecolor($img_width, $img_height);
 
imagesetthickness($img, 3);
 
$black = imagecolorallocate($img, 0, 0, 0);
$white = imagecolorallocate($img, 255, 255, 255);
$red   = imagecolorallocate($img, 255, 0, 0);
$green = imagecolorallocate($img, 0, 255, 0);
$blue  = imagecolorallocate($img, 0, 200, 250);
$orange = imagecolorallocate($img, 255, 200, 0);
$brown = imagecolorallocate($img, 220, 110, 0);
 
imagefill($img, 0, 0, $white);
 
imagefilledrectangle($img, 0, 0, $img_width, $img_height*8/10, $blue); // ko'k fon
 
imagefilledrectangle($img, $img_width*2/10, $img_height*5/10, $img_width*4/10, $img_height*8/10, $red); // eshik foni
imagefilledrectangle($img, $img_width*4/10 - 2, $img_height*5/10, $img_width*8/10, $img_height*8/10, $red); //devor
 
imagefilledpolygon(
	$img,
	[$img_width*3/10, $img_height*2/10, $img_width*2/10, $img_height*5/10, $img_width*4/10, $img_height*5/10],	
	$red
); // tom burchagi

imagefilledpolygon(
	$img,
	[$img_width*3/10, $img_height*2/10, $img_width*7/10, $img_height*2/10, $img_width*8/10, $img_height*5/10, $img_width*4/10, $img_height*5/10],
	$red
); //tom
 
imagefilledarc($img, 100, 100, 100, 100, 0, 360, $orange, IMG_ARC_PIE); // quyosh
imagefilledarc($img, $img_width*3/10, $img_height*8/10, 100, 200, 180, 360, $brown, IMG_ARC_PIE); // eshik
 
imagefilledrectangle($img, 0, $img_height*8/10, $img_width, $img_height, $green); // maysazor

/***	Ramka	***/
 
imagerectangle($img, $img_width*2/10, $img_height*5/10, $img_width*4/10, $img_height*8/10, $black);
imagerectangle($img, $img_width*4/10 - 2, $img_height*5/10, $img_width*8/10, $img_height*8/10, $black);
 
imagepolygon(
	$img,
	[$img_width*3/10, $img_height*2/10, $img_width*2/10, $img_height*5/10, $img_width*4/10, $img_height*5/10],
	$black
);
imageopenpolygon(
	$img,
	[$img_width*3/10, $img_height*2/10, $img_width*7/10, $img_height*2/10, $img_width*8/10, $img_height*5/10],
	$black
);
 
imagearc($img, 100, 100, 100, 100, 0, 360, $black);
imagearc($img, $img_width*3/10, $img_height*8/10, 100, 200, 180, 360, $black);
 
imageline($img, $img_width*9/10, 50, $img_width*9/10, 50, IMG_COLOR_BRUSHED);
 
imagerectangle($img, -100, $img_height*8/10, $img_width*11/10, $img_height*11/10, $black);
 
imagepng($img);
```
