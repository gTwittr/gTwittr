<?php

	class GraphicService {
		
		private static $instance = null;
		private $cache;
		
		
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new GraphicService();
			}
			return self::$instance;
		}
		
		
		public function __construct() {

		}
		
		
		public function generateProfileImage($url, $color) {
			
			$tmpname = './image_cache/' . $imageType . '_' . md5($url . $color) . '.png';
		
			if (file_exists($tmpname)) {
				if(time() - filemtime($tmpname) < IMAGE_CACHE_TIME) {
					return 'http://' . HOST_NAME . ltrim($tmpname, '.');
				}else{
					unlink($tmpname);
				}
			}
			
			copy($url, $tmpname . '_tmp');
			
			$borderColor = new ImagickPixel();
			$borderColor->setColor($color);
			
			$image = new Imagick($tmpname . '_tmp');
			$image->thumbnailImage(48, 0);
			$image->borderImage($borderColor, 3, 3);
			
			$image->writeImage($tmpname);
			
			unlink($tmpname . '_tmp');
			
			return 'http://' . HOST_NAME . ltrim($tmpname, '.');
		}
	
}

?>