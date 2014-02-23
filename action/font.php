<?php
// Create a 300x100 image
$im = imagecreatefromjpeg($_GET['url']);
$color = imagecolorallocate($im,255,255,255);
imagecolortransparent($im,$color);
imagefill($im,0,0,$color);
$black = imagecolorallocate($im, 0x00, 0x00, 0x00);

// Path to our ttf font file
$font_file = ACTION_PATH .'/MFDingDing_Noncommercial-Regular.otf';

// Draw the text 'PHP Manual' using font size 13
imagefttext($im, 35, 0, 150, 140, $black, $font_file, '看秀场');
imagefttext($im, 25, 0, 10, 160, $black, $font_file, '更多精彩 kanxiuchang.com');

// Output image to the browser
header('Content-Type: image/png');

imagepng($im);
imagedestroy($im);
?>
