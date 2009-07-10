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
				if (isset($result) && !empty($result)) {
					return $this->urlToLocation($result);
				}
			}
			return null;
		}
		
		public function urlToLocation($url) {
			if (!isset($url) && empty($url)) {
				return NULL;
			}

			preg_match_all('/[-+]?([0-9]{1,3}\.[0-9]+)/i', $url, $geo);
			
			if ($geo[0][1] == NULL || $geo[0][0] == NULL) {
				return NULL;
			}
			
			$loc = new Location($geo[0][1], $geo[0][0], 0.0);
			return $loc;
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