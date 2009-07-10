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
			$this->cache = new Cache_Lite(array('cacheDir' => CACHE_OPTS_DIR, 'lifeTime' => CACHE_OPTS_LIFETIME, 'automaticSerialization' => true));
		}
		
		public function shortUrlToLocation($shortUrl) {
			if (isset($shortUrl) && !empty($shortUrl)) {
				if (!($result_data = $this->cache->get($shortUrl))) {
					$url = 'http://untiny.me/api/1.0/extract/?url=' . $shortUrl . '&format=json';
					$result = $this->queryShortUrlService($url);
					if (isset($result) && !empty($result)) {
						$result_data = $this->urlToLocation($result);
					}	
					$this->cache->save($result_data,$shortUrl);
				}
				return $result_data;
			}
			return null;
		}
		
		public function urlToLocation($url) {
			if (!isset($url) && empty($url)) {
				return NULL;
			}
			$cache_key = "url2loc_$url";
			if (!($loc = $this->cache->get($cache_key))) {
				preg_match_all('/[-+]?([0-9]{1,3}\.[0-9]+)/i', $url, $geo);
				if ($geo[0][1] == NULL || $geo[0][0] == NULL) {
					$loc = null;
				} else {
					$loc = new Location($geo[0][1], $geo[0][0], 0.0);	
				}
				$this->cache->save($loc, $cache_key);
			}
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