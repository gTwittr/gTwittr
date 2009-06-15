<?php

	class Identity {
		
		private static $instance = null;
		
		public static function initIdentity() {
			if (self::$instance == null) {
				$ps = PersistanceService::getInstance();
				$user_data = $ps->getTokens(GWS_SESSION_ID);
				$user_data = $user_data[0];
				self::$instance = new Identity(GWS_SESSION_ID, $user_data['twitterId'],$user_data['token'],$user_data['secret']);
			}
			return self::$instance;
		}
		
		public static function getIdentity() {
			return self::$instance;
		}
		
		private $session_id;
		private $twitter_id;
		private $access_token;
		private $access_token_secret;
		
		public function __construct($session_id, $twitter_id, $access_token, $access_token_secret) {
			$this->session_id = $session_id;
			$this->twitter_id = $twitter_id;
			$this->access_token = $access_token;
			$this->access_token_secret = $access_token_secret;
		}
		
		public function getSessionId() {
			return $this->session_id;
		}
		
		public function getTwitterId() {
			return $this->twitter_id;
		}
		
		public function getAccessToken() {
			return $this->access_token;
		}
		
		public function getAccessTokenSecret() {
			return $this->access_token_secret;
		}
		
	}

?>