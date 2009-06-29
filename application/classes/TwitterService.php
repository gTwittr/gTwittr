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
		
		public function getLocation() {
			$user_data = $this->verifyCredentials();
			$rVal = new Location(0,0,0,'');
			if ($user_data) {
				$rVal = LocationService::getInstance()->findLocationByName($user_data->location);
			}
			return $rVal;
		}
		
		public function getTwitterName() {
			$user_data = $this->verifyCredentials();
			$rVal = "";
			if ($user_data) {
				$rVal = $user_data->name;
			}
			return $rVal;
		}
		
		public function getIconURL() {
			$user_data = $this->verifyCredentials();
			$rVal = "";
			if ($user_data) {
				$rVal = $user_data->profile_image_url;
			}
			return $rVal;
		}
		
		public function callTwitter($url, $method = 'GET', $authenticate = true, $caching = true) {
			if ($caching) {
				if ($json_data = $this->cache->get($url)) {
					return json_decode($json_data);
				}
			}
			if (ENVIRONMENT == DEVELOPMENT) {
				$curl = curl_init();
				if ($authenticate) {
					curl_setopt($curl, CURLOPT_USERPWD, "$this->access_token:$this->access_token_secret");	
				}
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$json_data = curl_exec($curl);
			} else {
				$json_data = $this->to->OAuthRequest($url, array(), $method);	
			}
			if ($caching) {
				$this->cache->save($json_data,$url);	
			}
			return json_decode($json_data);
		}
		
		public function getUserData($user_id=-1) {
			$url = "http://twitter.com/users/show/$user_id.json";
			return $this->callTwitter($url);
		}
		
		public function getFollowers() {
			$url = "http://twitter.com/followers/ids.json";
			$result = $this->callTwitter($url,'GET',true,false);
			$rVal = array();
			foreach ($result as $follower_id) {
				$user_data = $this->getUserData($follower_id);
				if ($user_data->error) {
					continue;
				}
				//die(var_dump($user_data));
				$user_object = new stdClass();
				$user_object->twitter_id = $follower_id;
				
				if (empty($user_data->location)) {
					//random
					$user_location = Location::getRandomLocation();
				} else {
					$user_location = LocationService::getInstance()->findLocationByName($user_data->location);
				}
				$user_object->location = $user_location;
				
				$user_object->icon_url = $user_data->profile_image_url;
				$user_object->screen_name = $user_data->screen_name;
				$user_object->verified = $user_data->verified;
				array_push($rVal, $user_object);
			}
			return $rVal;
		}
		
		public function getFollowing() {
			$user_id = Identity::getIdentity()->getTwitterId();
			$url = "https://twitter.com/friends/ids.json";
			$result = $this->callTwitter($url,'GET',true,false);
			$rVal = array();
			foreach($result as $following_id) {
				array_push($rVal, $following_id);
			}
			return $rVal;
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