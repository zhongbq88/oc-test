<?php
class ModelShopifyImage extends Model {
	
	
	function merge($uploadPath, $sourePath,$savePath)
	{
		$soure = imagecreatefrompng($sourePath);
		imagesavealpha($soure,true);
		$width = imagesx($soure);
		$height = imagesy($soure);
		$upload = $this->imageThumb($uploadPath,$width, $height);
		$mergeimg = imagecreatetruecolor($width, $height);
		imagealphablending($mergeimg,false);
	    imagesavealpha($mergeimg,true);
		for ($x = 0; $x < $width; $x++) {
			for ($y = 0; $y < $height; $y++) {
				$color = imagecolorat($upload, $x, $y);
				$tR = ($color >> 16) & 0xFF;
				$tG = ($color >> 8) & 0xFF;
				$tB = $color & 0xFF;
				$color = imagecolorat($soure, $x, $y);
				$bR = ($color >> 16) & 0xFF;
				$bG = ($color >> 8) & 0xFF;
				$bB = $color & 0xFF;
				imagesetpixel($mergeimg, $x, $y, $color & 0xFF000000|$this->layerMultiply( $tR, $bR)<< 16|$this->layerMultiply($tG, $bG)<< 8|$this->layerMultiply($tB, $bB));
			 
			}
		}
		$photoArray =   explode('/',$uploadPath);
    	$fileName   =   explode('.',end($photoArray));
    	$fileName   =   $fileName[0]."_".time().'_n.png';
		imagepng($mergeimg,$savePath.$fileName);
		imagedestroy($mergeimg);
		imagedestroy($upload);
		imagedestroy($soure);
		return $fileName;
	}
	
	function imageThumb($sourePic,$width,$heigh){  
		$image=imagecreatefromjpeg($sourePic);
		$BigWidth=imagesx($image);
		$BigHeigh=imagesy($image);
		$thumb = imagecreatetruecolor($width,$heigh);  
		if(imagecopyresampled($thumb,$image,0,0,0,0,$width,$heigh,$BigWidth,$BigHeigh)){  
		 	imagedestroy($image);
			return $thumb;
		}  
		return $image;
    } 
	
	public function layerMultiply($A, $B)
    {
        return $A * $B / 255;
    }
	
	public function resize($filename, $width, $height) {
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) { 
				return DIR_IMAGE . $image_old;
			}
						
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}
		
		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +
		
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}
	
	
	public function getImagePath($filename){
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $filename;
		} else {
			return $this->config->get('config_url') . 'image/' . $filename;
		}
	}
	
	public function resizeupload($filename, $width, $height) {
		if (!is_file(DIR_UPLOAD . $filename) || substr(str_replace('\\', '/', realpath(DIR_UPLOAD . $filename)), 0, strlen(DIR_UPLOAD)) != str_replace('\\', '/', DIR_UPLOAD)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

		if (!is_file(DIR_UPLOAD . $image_new) || (filemtime(DIR_UPLOAD . $image_old) > filemtime(DIR_UPLOAD . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_UPLOAD . $image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) { 
				return DIR_UPLOAD . $image_old;
			}
						
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_UPLOAD . $path)) {
					@mkdir(DIR_UPLOAD . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_UPLOAD . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_UPLOAD . $image_new);
			} else {
				copy(DIR_UPLOAD . $image_old, DIR_UPLOAD . $image_new);
			}
		}
		
		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +
		
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}
}