<?php
    //用给定角度旋转图像，以jpeg图像格式为例
    function rotate($filename,$degrees){
        //创建图像资源，以jpeg格式为例
        $source = imagecreatefromjpeg($filename);
        //使用imagerotate()函数按指定的角度旋转
		$pngTransparency = imagecolorallocatealpha($source, 0, 0, 0, 127);
imagefill($source, 0, 0, $pngTransparency);
        $rotate = imagerotate($source, $degrees, $pngTransparency);
        //旋转后的图片保存
		
		//裁剪开区域左上角的点的坐标
$x = 730;
$y = 660;
//裁剪区域的宽和高
$width = imagesx($rotate);
$height = imagesy($rotate);
//最终保存成图片的宽和高，和源要等比例，否则会变形
$final_width = 1310;
$final_height = round($final_width * $height / $width);
 
//将裁剪区域复制到新图片上，并根据源和目标的宽高进行缩放或者拉升
$new_image = imagecreatetruecolor(704, 726);
$color = imagecolorallocate($new_image, 255, 255, 255);
		imagefill($new_image, 0, 0, $color);
imagecopyresampled($new_image, $rotate, 0, 0, $x, $y, $final_width, $final_height, $width, $height);
		header('Content-Type: image/jpg');
	    imagejpeg($new_image);
        //$imagejpeg($rotate,$filename);
    }
 
    //把一幅图像brophp.jpg旋转180度
    rotate("../../t6.jpg", -45);
?>