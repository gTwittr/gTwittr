<?php

	class GeoUrlService {
		
		private static $instance = null;
		private $cache;
		
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new GeoUrlService();
			}
			return self::$instance;
		}
		
		public function __construct() {
	
		}
		
		public function shortUrlToLocation($shortUrl) {
			if (isset($shortUrl) && !empty($shortUrl)) {
				$url = 'http://untiny.me/api/1.0/extract/?url=' . $shortUrl . '&format=json';
				$result = $this->queryShortUrlService($url);
				
				$firephp = FirePHP::getInstance(true);
				$firephp->group('GeoUrlService->shortUrlToLocation');
		
				if (isset($result) && !empty($result)) {
					preg_match_all('/[-+]?([0-9]{1,3}\.[0-9]+)/i', $result, $geo);
					$firephp->log($geo[0]);
					
					$loc = new Location($geo[0][1], $geo[0][0], 0.0);
					$firephp->log($loc);
			
					$firephp->groupEnd();	
					
					return $loc;
				}
			}
			return null;
		}
		
		private function queryShortUrlService($url) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($curl);
			return json_decode($result)->org_url;
		}
	}

?>