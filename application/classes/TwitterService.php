<?php

	class TwitterService {
		
		private $to; //twitteroauth
		private $token;
		private $cache;
		
		private $access_token;
		private $access_token_secret;
		
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
		}
		
		public function getAuthorizeURL() {
			$firephp = FirePHP::getInstance(true);
			$firephp->group('TwitterService->getAuthorizeURL');
			//RequestToken anfordern
			$tok = $this->to->getRequestToken();
			$firephp->log($tok, 'RequestToken');
			//RequestToken und RequestTokenSecret in Session Speichern
			$_SESSION['oauth_request_token'] = $token = $tok['oauth_token'];
		  $_SESSION['oauth_request_token_secret'] = $tok['oauth_token_secret'];
			$rVal = '';
			if (isset($_SESSION['oauth_request_token'])) {
				$token = $_SESSION['oauth_request_token'];
				$firephp->log($token,'request_token');
				//AuthorizeURL anfordern
				$rVal = $this->to->getAuthorizeURL($token);
				$firephp->log($rVal,'authorize_url');
			}
			$firephp->groupEnd();
			return $rVal;
		}
		
		public function getAccessToken() {
			$firephp = FirePHP::getInstance(true);
			$firephp->group('TwitterService->getAccessToken');
			$firephp->log($_SESSION['oauth_request_token'], 'RequestToken');
			$firephp->log($_SESSION['oauth_request_token_secret'], 'RequestTokenSecret');		
			$this->to = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_SESSION['oauth_request_token'], $_SESSION['oauth_request_token_secret']);
			$this->token = $this->to->getAccessToken();
			$firephp->log($this->token, 'getAccessToken - Response');
			$this->access_token = $_SESSION['oauth_access_token'] = $this->token['oauth_token'];
	    $this->access_token_secret = $_SESSION['oauth_access_token_secret'] = $this->token['oauth_token_secret'];
			$this->to = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $this->access_token, $this->access_token_secret);
			$firephp->log($_SESSION['oauth_access_token'], 'AccessToken');
			$firephp->log($_SESSION['oauth_access_token_secret'], 'AccessTokenSecret');
			$firephp->groupEnd();
		}
		
		public function setTokens($access_token, $access_token_secret) {
			if (!empty($access_token) && !empty($access_token_secret)) {
				$this->access_token = $access_token;
				$this->access_token_secret = $access_token_secret;
				$this->to = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_KEY, $this->access_token, $this->access_token_secret);
			}
		}
		
		/*
		public function login($username, $password) {
			$this->username = $username;
			$this->password = $password;
			return $this->callTwitter('http://twitter.com/account/verify_credentials');
		}
		*/
		
		public function verifyCredentials() {
			return $this->to->OAuthRequest('https://twitter.com/account/verify_credentials.json', array(), 'GET');
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
		
		public function isAuthenticated() {
			return $this->access_token != null && $this->access_token_secret != null;
		}
		
		
	}

?>