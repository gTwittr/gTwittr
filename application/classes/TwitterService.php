<?php

	class TwitterService {
		
		private $username;
		private $password;
		private $cache;
		
		private static $instance = null;
		
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new TwitterService();
			}
			return self::$instance;
		}
		
		public function __construct() {
			$this->cache = new Cache_Lite(array('cacheDir' => CACHE_OPTS_DIR, 'lifeTime' => CACHE_OPTS_LIFETIME, 'automaticSerialization' => true));
		}
		
		public function login($username, $password) {
			$this->username = $username;
			$this->password = $password;
			return $this->callTwitter('http://twitter.com/account/verify_credentials');
		}
		
		private function callTwitter($url) {
			//json daten erwarten
			$url = $url . '.json';
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_USERPWD, $this->username . ':' . $this->password);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($curl);
			return json_decode($result);
		}
		
		public function getPublicTimelineTweets() {
			$result = $this->callTwitter('http://twitter.com/statuses/public_timeline');
			$rVal = array();
			
			foreach($result as $t) {
				
				$tweet = new Tweet($t->text,LocationService::getInstance()->findLocation($t->user->location));
				
				array_push($rVal, $tweet);
			}
			
			return $rVal;
		}
		
		
	}

?>