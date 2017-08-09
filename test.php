<?php
$imgs    = array();
$imgs[0] = 'image/catalog/designs/51251920x1440_n.jpg';
$imgs[1] = 'image/catalog/designs/821583_n.jpg';
$target  = 'ss.jpg'; //背景图片
 
//$target_img = Imagecreatefromjpeg($target);
 
$source = array();
 
foreach ($imgs as $k => $v) {
    $source[$k]['source'] = Imagecreatefromjpeg($v);
     
    $source[$k]['size'] = getimagesize($v);
     
}
 $padding = 200;
//创建一个新的，和大图一样大的画布
$target_img     = imageCreatetruecolor(imagesx( $source[0]['source'])+ $padding*2,imagesy( $source[0]['source'])*2);
$color = imagecolorallocate($target_img, 255, 255, 255);
imagefill($target_img, 0, 0, $color);
$num1 = 0;
$num  = 1;
$tmp  = 2;
$tmpy = 0; //图片之间的间距
for ($i = 0; $i < 2; $i++) {
    imagecopy($target_img, $source[$i]['source'],$padding, $tmpy, 0, 0, $source[$i]['size'][0], $source[$i]['size'][1]);
     
    $tmpy =  $tmpy+ $source[$i]['size'][1];

}
Imagejpeg($target_img, 'pin.jpg');
 
?>
<img src="pin.jpg">