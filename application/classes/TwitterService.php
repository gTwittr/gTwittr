<?php

	class TwitterService {
		
		private $to;
		private $token;
		private $cache;
		
		private $access_token;
		private $access_token_secret;
		
		private static $instance = null;
		
		public static function getInstance() {
			if (self::$instance == null) {
				$access_token = Identity::getIdentity()->getAccessToken();
				$access_token_secret = Identity::getIdentity()->getAccessToken();
				if (ENVIRONMENT == DEVELOPMENT) {
					$access_token = TEST_USER;
					$access_token_secret = TEST_PASS;
				}
				self::$instance = new TwitterService($access_token,$access_token_secret);
			}
			return self::$instance;
		}
		
		public function __construct($access_token = '', $access_token_secret = '') {
			$this->cache = new Cache_Lite(array('cacheDir' => CACHE_OPTS_DIR, 'lifeTime' => CACHE_OPTS_LIFETIME, 'automaticSerialization' => true));
			$this->to = new TwitterOAuth(TWITTER_CONSUMER_KEY,TWITTER_CONSUMER_SECRET);
			if (!empty($access_token) && !empty($access_token_secret)) {
				$this->access_token = $access_token;
				$this->access_token_secret = $access_token_secret;
			}
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
				//AuthorizeURL mit Requesttoken anfordern
				$rVal = $this->to->getAuthorizeURL($token);
				$firephp->log($rVal,'authorize_url');
				$firephp->log('test');
			}
			$firephp->groupEnd();
			return $rVal;
		}
		
		public function getAccessToken() {
			$firephp = FirePHP::getInstance(true);
			$firephp->group('TwitterService->getAccessToken');
			$firephp->log($_SESSION['oauth_request_token'], 'RequestToken');
			$firephp->log($_SESSION['oauth_request_token_secret'], 'RequestTokenSecret');		
			//neuer TwitterOAtuh mit RequestToken und -secret
			$this->to = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_SESSION['oauth_request_token'], $_SESSION['oauth_request_token_secret']);
			//AccessToken anfordern
			$this->token = $this->to->getAccessToken();
			$firephp->log($this->token, 'getAccessToken - Response');
			//accessToken und accessTokenSecret auslesen und speichern
			$this->access_token = $_SESSION['oauth_access_token'] = $this->token['oauth_token'];
	    $this->access_token_secret = $_SESSION['oauth_access_token_secret'] = $this->token['oauth_token_secret'];
			//twitterOAuth mit access_token und access_token_secret anlegen
			$this->to = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $this->access_token, $this->access_token_secret);
			$firephp->log($_SESSION['oauth_access_token'], 'AccessToken');
			$firephp->log($_SESSION['oauth_access_token_secret'], 'AccessTokenSecret');
			$firephp->groupEnd();
			return $this->isAuthenticated();
		}
		
		public function setTokens($access_token, $access_token_secret) {
			if (!empty($access_token) && !empty($access_token_secret)) {
				$this->access_token = $access_token;
				$this->access_token_secret = $access_token_secret;
				$this->to = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $this->access_token, $this->access_token_secret);
			}
		}
		
		public function verifyCredentials() {
			return $this->callTwitter('https://twitter.com/account/verify_credentials.json');
		}
		
		public function callTwitter($url, $method = 'GET') {
			if (ENVIRONMENT == DEVELOPMENT) {
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_USERPWD, $this->access_token . ':' . $this->access_token_secret);
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($curl);
				return json_decode($result);
			}
			return json_decode($this->to->OAuthRequest($url, array(), $method));
		}
		
		public function getPublicTimelineTweets() {
			$result = $this->callTwitter('https://twitter.com/statuses/public_timeline.json');
			$rVal = array();
			
			foreach($result as $t) {
				
				$tweet = new Tweet($t->text,LocationService::getInstance()->findLocation($t->user->location));
				
				array_push($rVal, $tweet);
			}
			return $rVal;
		}
		
		public function isAuthenticated() {
			return $this->access_token !== NULL && $this->access_token_secret !== NULL;
		}
		
		
	}

?>