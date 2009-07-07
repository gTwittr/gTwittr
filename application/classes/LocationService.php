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
			$rVal = new Location(0,0,0);
			if ($text && !empty($text)) {
				/*$coords = preg_split('/^.*(-?[0-9]\.[0-9]),(-?[0-9]\.[0-9]).*$/', $text);
				$lat = $coords[0];
				$lon = $coords[1];
				$rVal = new Location($lng,$lat,0);
				*/
				
				preg_match_all('/[-+]?([0-9]{1,3}\.[0-9]+)/i', $text, $geo);
				$rVal = new Location($geo[0][1], $geo[0][0], 0.0);
			}
			return $rVal;
		}
		
	
		//returns NULL if there is no location - returns the first location if there is more than one
		public function extractLocation($text, $fallback_location) {
			
			$urls = $this->extractUrls($text);
			
			$count = count($urls);
			for ($i=0; $i < $count; $i++) {
				$url = $urls[$i];
				$loc = GeoUrlService::getInstance()->shortUrlToLocation($url);
				if ($loc == NULL) {
					$loc = GeoUrlService::getInstance()->urlToLocation($url);
					if ($loc != NULL) {
						return $loc;
					}
				}else{
					return $loc;
				}
			}
			
			return $this->findLocationByName($fallback_location);
		}
		
		private function extractUrls($text) {
			preg_match_all('/\b(?:(?:https?):\/\/|www\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $text, $result);
			return $result[0];
		}
		
		private function queryGeonamesService($url) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($curl);
			return json_decode($result)->geonames[0];
		}
		
		public static function reArrangeLocation($reference, &$locationContainer) {
			if ($reference != null && is_a($reference,"Location")) {
				$rVal = array($reference);
				if ($locationContainer != null && is_array($locationContainer)) {
					foreach ($locationContainer as $locationHolder) {
						$location = $locationHolder->location;
						if ($location != null && is_a($location,"Location")) {
							while (self::arrayHoldsLocation($rVal,$location)) {
								$lon = $location->getLongitude();
								$lat = $location->getLatitude();
								$lon += (rand(-10,10)/1000);
								$lat += (rand(-10,10)/1000);
								$location->setLongitude($lon);
								$location->setLatitude($lat);
							}
							array_push($rVal,$location);
							$locationHolder->location = $location;
						}
					}
				}
				$rVal = array_diff($rVal,array($reference));
				return $rVal;	
			} 
			return array();
		}
		
		private static function arrayHoldsLocation($array, $location) {
			if ($location != null && is_a($location, "Location") && $array != null && is_array($array)) {
				foreach($array as $testLoc) {
					if ($testLoc->isEqualTo($location)) {
						return true;
					}
				}
			}
			return false;
		}
		
	}

?>