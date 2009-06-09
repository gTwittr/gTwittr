<?php

	class LocationService {
		
		private static $instance = null;
		private $cache;
		
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new LocationService();
			}
			return self::$instance;
		}
		
		public function __construct() {
			$this->cache = new Cache_Lite(array('cacheDir' => CACHE_OPTS_DIR, 'lifeTime' => CACHE_OPTS_LIFETIME, 'automaticSerialization' => true));
		}
		
		public function findLocationByName($name) {
			if (isset($name) && !empty($name)) {
				$id = urlencode($name);
				if (!($location = $this->cache->get($id))) {
					$url = 'http://ws.geonames.org/searchJSON?' . 'q=' . $id . '&maxRows=1';
					$result = $this->queryGeonamesService($url);
					$location = new Location($result->lng, $result->lat, 0, $name);
					$this->cache->save($location);
				}
				return $location;
			}
			return null;
		}
		
		public function findLocation($text) {
			$coords = preg_split('/^.*(-?[0-9]\.[0-9]),(-?[0-9]\.[0-9]).*$/', $text);
		}
		
		private function queryGeonamesService($url) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($curl);
			return json_decode($result)->geonames[0];
		}
		
	}

?>