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
			$twitter_data = $this->callTwitter('https://twitter.com/account/verify_credentials.json');
			if ($twitter_data && !$twitter_data->error && !Identity::getIdentity()->getTwitterId()) {
				Identity::getIdentity()->setTwitterId($twitter_data->id);
			}
		}
		
		public function getUserInfo($user_id = -1) {
			if ($user_id == -1) {
				$user_id = $this->ownTwitterId();
			}
			$url = "http://twitter.com/users/show/$user_id.json";
			return $this->callTwitter($url);
		}
		
		public function getLocation($user_id = -1) {
			$user_data = $this->getUserInfo($user_id);
			$rVal = new Location(0,0,0,'');
			if ($user_data && !$user_data->error) {
				$rVal = LocationService::getInstance()->findLocationByName($user_data->location);
			}
			return $rVal;
		}
		
		public function getTwitterName($user_id = -1) {
			$user_data = $this->getUserInfo($user_id);
			
			$rVal = "";
			if ($user_data && !$user_data->error) {
				$rVal = $user_data->screen_name;
			}
			return $rVal;
		}
		
		public function getIconURL($user_id = -1) {
			$user_data = $this->getUserInfo($user_id);
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
			
			// Zur Entwicklung wird nicht OAuth genutzt, da der Mechanismus lokal nicht funktioniert
			if (ENVIRONMENT == DEVELOPMENT) {
				$curl = curl_init();
				
				curl_setopt($curl, CURLOPT_GET, 1);
				
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				
				if ($authenticate) {
					if (preg_match('/followers\/ids\.json\?user_id=\d+$/',$url) > 0) {
						echo "authenticate : $url " . "$this->access_token:$this->access_token_secret";
					}
					curl_setopt($curl, CURLOPT_USERPWD, "$this->access_token:$this->access_token_secret");	
				}
				
				curl_setopt($curl, CURLOPT_URL, $url);
				
				$json_data = curl_exec($curl);
			} else {
				$json_data = $this->to->OAuthRequest($url, array(), $method);	
			}
			if ($caching) {
				$this->cache->save($json_data,$url);	
			}
			return json_decode($json_data);
		}
		
		public function getUserTimelineTweets($user_id=-1) {
			$rVal = array();
			if ($user_id == -1) {
				$user_id = $this->ownTwitterId();
			}
			$url = "http://twitter.com/statuses/user_timeline/$user_id.json";
			$twitter_data = $this->callTwitter($url);
			if ($twitter_data && !$twitter_data->error) {
				foreach($twitter_data as $status) {
					$tweet = new stdClass();
					$tweet->id = $status->id;
					$tweet->text = $status->text;
					$tweet->created_at = $status->created_at;
					$tweet->location = LocationService::getInstance()->extractLocation($tweet->text, $twitter_data->user->location);
					array_push($rVal,$tweet);
				}
			}
			return $rVal;
		}
		
		public function buildUser($twitter_data) {
			$rVal = null;
			if ($twitter_data && !$twitter_data->error) {
				$rVal = new stdClass();
				$rVal->twitter_id = $twitter_data->id;
				if (empty($twitter_data->location)) {
					//random
					$user_location = Location::getRandomLocation();
				} else {
					$user_location = LocationService::getInstance()->findLocationByName($twitter_data->location);
				}
				$rVal->location = $user_location;
				$rVal->location_name = $twitter_data->location;
				$rVal->icon_url = $twitter_data->profile_image_url;
				$rVal->screen_name = $twitter_data->screen_name;
				$rVal->verified = $twitter_data->verified;
				$rVal->description = $twitter_data->description;
				$rVal->following_count = $twitter_data->friends_count;
				$rVal->followers_count = $twitter_data->followers_count;
				$rVal->url = $twitter_data->url;
				$rVal->tweets = $this->getUserTimelineTweets($rVal->twitter_id);
			}
			return $rVal;
		}
		
		public function getUserData($user_id=-1) {
			$twitter_data = $this->getUserInfo($user_id);
			$rVal = $this->buildUser($twitter_data);
			return $rVal;
		}
		
		public function getTweet($tweet_id) {
			$rVal = array();
			$url = "http://twitter.com/statuses/show/$tweet_id.json";
			$twitter_data = $this->callTwitter($url);
			
			$tweet = new stdClass();
			$tweet->id = $twitter_data->id;
			$tweet->text = $twitter_data->text;
			$tweet->created_at = $twitter_data->created_at;
			$tweet->location = LocationService::getInstance()->extractLocation($tweet->text, $twitter_data->user->location);
			$tweet->screen_name = $twitter_data->user->screen_name;
			
			return $tweet;
		}
		
		public function getBlockedUserIds() {
			$url = "http://twitter.com/blocks/blocking/ids.json";
			$twitter_data = $this->callTwitter($url,'GET',true,true);
			if (!$twitter_data->error) {
				return $twitter_data;
			}
			return array();
		}
		
		private function ownTwitterId() {
			return Identity::getIdentity()->getTwitterId();
		}
		
		private function isMe($user_id) {
			if ($user_id && $user_id != -1) {
				return $this->ownTwitterId() == $user_id;
			}
			return false;
		}
		
		public function getFollowers($user_id = -1) {
			if ($user_id == -1) {
				$url = "http://twitter.com/followers/ids.json";
			} else {
				$url = "http://twitter.com/followers/ids/$user_id.json";	
			}
			$result = $this->callTwitter($url,'GET',true,false);
			$rVal = array();
			//die(var_dump($result));
			if (!$result->error) {
				$result = (array) $result;
				//blocked users entfernen
				if ($user_id == -1) {
					$result = array_diff($result, $this->getBlockedUserIds());		
				}
				foreach ($result as $follower_id) {
					$user_object = $this->getUserData($follower_id);
					if ($user_object != null) {
						array_push($rVal, $user_object);	
					}
				}	
			}
			return $rVal;
		}
		
		public function getFollowing($user_id = -1) {
			if ($user_id == -1) {
				$url = "http://twitter.com/friends/ids.json";
			} else {
				$url = "http://twitter.com/friends/ids/$user_id.json";	
			}
			$result = $this->callTwitter($url,'GET',true,false);
			$rVal = array();
			if (!$result->error) {
				$result = (array) $result;
				//blocked users entfernen
				if ($user_id == -1) {
					$result = array_diff($result, $this->getBlockedUserIds());		
				}
				foreach($result as $following_id) {
					$user_object = $this->getUserData($following_id);
					if ($user_object != null) {
						array_push($rVal, $user_object);	
					}
				}
			}
			return $rVal;
		}
		
		public function isAuthenticated() {
			return $this->access_token !== NULL && $this->access_token_secret !== NULL;
		}
		
		private function resultToArray($object) {
			$rVal = array();
			if (is_a($object, "stdClass") && !$object->error) {
				$rVal = (array) $object;
			}
			return $rVal;
		}
		
		
	}

?>