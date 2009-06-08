<?php

	class TwitterService {
		
		private $to; //twitteroauth
		private $token;
		private $cache;
		
		private $request_token;
		private $request_token_secret;
		
		private static $instance = null;
		
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new TwitterService();
			}
			return self::$instance;
		}
		
		public function __construct() {
			$this->cache = new Cache_Lite(array('cacheDir' => CACHE_OPTS_DIR, 'lifeTime' => CACHE_OPTS_LIFETIME, 'automaticSerialization' => true));
			$this->to = new TwitterOAuth(TWITTER_CONSUMER_KEY,TWITTER_CONSUMER_SECRET);
			$tok = $this->to->getRequestToken();
			$_SESSION['oauth_request_token'] = $token = $tok['oauth_token'];
		   $_SESSION['oauth_request_token_secret'] = $tok['oauth_token_secret'];
		}
		
		public function getAuthorizeURL() {
			$rVal = '';
			if (isset($_SESSION['oauth_request_token'])) {
				$token = $_SESSION['oauth_request_token'];
				$rVal = $this->to->getAuthorizeURL($token);
			}
			return $rVal;
		}
		
		public function getAccessToken() {
			$this->to = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_SESSION['oauth_request_token'], $_SESSION['oauth_request_token_secret']);
	      $this->token = $this->to->getAccessToken();
			echo '<pre>' . var_dump($this->token) . '</pre>';
		}
		
		public function setTokens($request_token, $request_token_secret) {
			if (!empty($request_token) && !empty($request_token_secret)) {
				$this->request_token = $request_token;
				$this->request_token_secret = $request_token_secret;
			}
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