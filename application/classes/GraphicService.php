<?php

	class GraphicService {
		
		private static $instance = null;
		private $cache;
		
		private $enabled = false;
		
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new GraphicService();
			}
			return self::$instance;
		}
		
		
		public function __construct() {
			if (class_exists('Imagick')) {
				$this->enabled = true;
			}
		}
		
		public function isEnabled() {
			return $this->enabled;
		}
		
		public function generateProfileImage($url, $color) {
			
			$template_image = './data/';
			
			if ($color == COLOR_FOLLOWER) {
				$template_image = $template_image . 'profile_follower.png';
			}else if ($color == COLOR_FOLLOWING) {
				$template_image = $template_image . 'profile_following.png';
			}else if ($color == COLOR_USER) {
				$template_image = $template_image . 'profile_user.png';
			}
			
			if (! $this->isEnabled()) {
				return $template_image . 'profile_default.png';
			}
				
			$url = str_replace('\/','/',$url);
			
			$tmpname = './image_cache/' . $profile . '_' . md5($url . $color) . '.png';

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
			$image->thumbnailImage(48, 48);
			//$image->borderImage($borderColor, 3, 3);
			
			$template = new Imagick($template_image);
			$template->compositeImage($image, Imagick::COMPOSITE_DEFAULT, 5, 5);

			$template->writeImage($tmpname);

			unlink($tmpname . '_tmp');

			return 'http://' . HOST_NAME . ltrim($tmpname, '.');	

		}
		
		public function generateInfoBarImage($profileImageUrl, $screenName, $numFollowing, $numFollowers) {
			
			if ($this->isEnabled()) {
				$profileImgTmp = './image_cache/profile_' . md5($profileImageUrl) . '.png';
				$infoBarName = './image_cache/' . $screenName . '_' . md5($profileImageUrl) . '.png';

				if (file_exists($profileImgTmp)) {
					if(time() - filemtime($profileImgTmp) < IMAGE_CACHE_TIME) {
						return 'http://' . HOST_NAME . ltrim($profileImgTmp, '.');
					}else{
						unlink($profileImgTmp);
					}
				}

				copy($profileImageUrl, $profileImgTmp . '_tmp');

				//create profile image
				$profileImg = new Imagick($profileImgTmp . '_tmp');
				$profileImg->thumbnailImage(23, 23);


				//create info bar image
				$infoBarImg = new Imagick('./data/topBar.png');
				$infoBarImg->compositeImage($profileImg, Imagick::COMPOSITE_DEFAULT, 322, 4);

				//create screen name text
				$draw = new ImagickDraw();
				$pixel = new ImagickPixel( 'gray' );
				$draw->setFillColor(COLOR_USER);
				$draw->setFont('data/TahomaBold.ttf');
				$draw->setFontSize( 11 );
				$infoBarImg->annotateImage($draw, 237, 19, 0, $screenName);
				$infoBarImg->annotateImage($draw, 415, 19, 0, $numFollowing);
				$infoBarImg->annotateImage($draw, 505, 19, 0, $numFollowers);;

				$infoBarImg->writeImage($infoBarName);

				unlink($profileImgTmp . '_tmp');

				return 'http://' . HOST_NAME . ltrim($infoBarName, '.');	
			} else {
				return '';
			}
			
			
		}
	
	
}

?>