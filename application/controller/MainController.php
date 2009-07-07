<?php

	class MainController extends AbstractController {
		
		public function index() {
		
		}
		/*
		public function login() {
			$view = $this->getView('login');
			
			if ($this->requestedKML()) {
				$test = $this->location_service->findLocationByName('Bremen');
				$view->location = $test;
			}
			
			
			
			$view->show();
		}
		
		public function doLogin() {
			
			if (!isset($_POST['username']) || !isset($_POST['password'])) {
				//hier aussteigen, da parameter benötigt werden
				die('username or password not set');
			}
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			$data = $this->twitter_service->login($username,$password);
		}
		
		public function logout() {
			
		}
		*/
		
		/**
			*
			* Wird als Callback von Twitter am Ende des Authorisierungsvorgangs aufgerufen,
			* die AccessTokens werden aus der Session ausgelesen.
			*
			**/
		public function callback() {
			$view = $this->getView('callback');
			//access key von Twitter in Empfang nehmen
			$view->authenticated = $this->twitter_service->getAccessToken();

			if ($view->authenticated) {
				$view->user_data = $this->twitter_service->verifyCredentials();	
			}
			
			/*
			$testData = '{		"time_zone":"Berlin",
									"profile_link_color":"333",
									"description":"",
									"profile_background_tile":false,
									"utc_offset":3600,
									"created_at":"Sun Dec 21 23:03:32 +0000 2008",
									"followers_count":13,
									"profile_image_url":"http:\/\/s3.amazonaws.com\/twitter_production\/profile_images\/69546449\/2793452349_794ae79227_mini_studi_normal.jpg",
									"profile_background_color":"ffffff",
									"friends_count":26,
									"screen_name":"karl_in",
									"profile_sidebar_fill_color":"FFF",
									"url":null,
									"name":"Karl",
									"following":false,
									"favourites_count":0,
									"protected":false,
									"status":{	"truncated":false,
													"created_at":"Sun Jun 07 14:09:26 +0000 2009",
													"in_reply_to_status_id":2064760168,
													"in_reply_to_user_id":40841262,
													"text":"@Hogi_de hmmm, somehow i\'ve heard that already sometime, but good resolution, i\'m going with you...",
													"favorited":false,
													"in_reply_to_screen_name":"Hogi_de",
													"id":2064782493,
													"source":"Nambu<\/a>"},
									"profile_sidebar_border_color":"333",
									"notifications":false,
									"profile_text_color":"000",
									"location":"Bremen, Germany",
									"id":18294924,
									"statuses_count":63,
									"profile_background_image_url":"http:\/\/s3.amazonaws.com\/twitter_production\/profile_background_images\/15751079\/twitter-bg.png"
							}';
			
			$profile_data = json_decode($testData);
			
			$view->user_data = $profile_data;
			*/
			
			$view->show();
			die($view->authenticated);
		}
		
		public function mockAccessTokens() {
			$view = $this->getView('mockAccessTokens');
			
			
			
			$view->show();
		}
		
		public function doMockAccessTokens() {
			$accessToken = $_GET['accessToken'];
			$accessTokenSecret = $_GET['accessTokenSecret'];
			$this->twitter_service->setTokens($accessToken, $accessTokenSecret);
			echo '<pre>' . var_dump($this->twitter_service->verifyCredentials()) . '</pre>'; 
		}
		
		public function start() {
			if (!$this->requestedKML()) {
				return;
			}
			$view = $this->getView('start');
			$view->authenticated = false;
			
			if ($this->twitter_service->isAuthenticated()) {
				$view->authenticated = true;
				
				$user_data = $this->twitter_service->getUserData();
				
				$myLocation = $user_data->location;
				$following = $this->twitter_service->getFollowing($user_data->twitter_id);
				$followers = $this->twitter_service->getFollowers($user_data->twitter_id);
				
				$view->location = $myLocation;
				
				//following
				$followingCount = $user_data->following_count;
				$followerCount = $user_data->followers_count;
				$screen_name = $user_data->screen_name;
				$view->screen_name = $screen_name;
				
				if (!($view->web_url = $user_data->url)) {
					$view->web_url = 'http://www.twitter.com';
				}
				
				if (!($view->description = $user_data->description)) {
					$view->description = '';
				}
				
				$view->tweets = $user_data->tweets;
				
				$iconUrl = $user_data->icon_url;
				$view->icon_url_plain = $iconUrl;
				$view->icon_url = GraphicService::getInstance()->generateProfileImage($iconUrl,COLOR_USER);
				
				$view->followers_count = $followerCount;
				$view->followers_link = link_tag('Followers','main/followers.kml#start;balloonFlyto',true);
				
				$view->following_count = $followingCount;
				$view->following_link = link_tag('Following','main/following.kml#start;balloonFlyto',true);
				$view->overlay_path = GraphicService::getInstance()->generateInfoBarImage($iconUrl,$screen_name,$followingCount,$followerCount);
			} else {
				$view->location = $this->location_service->findLocationByName('Wildeshausen');
			}
			
			$view->show();
		}
		
		public function followers() {
			//scope evtl. auf einen anderen user setzen
			$user_id = getValueOrDefault($_GET['user_id'],-1);
			$view = $this->getView('friends');
			$view->icon_url = $this->twitter_service->getIconURL($user_id);
			$userLocation = $this->twitter_service->getLocation($user_id);
			$view->location = $userLocation;
			$followers = $this->twitter_service->getFollowers($user_id);
			//locations der followers anpassen, damit nicht alle auf einem haufen
			LocationService::reArrangeLocation($userLocation,$followers);
			$view->friends = $followers;
			
			$view->iconBaseColor = COLOR_FOLLOWERS;
			$view->header = "Followers";
			
			$view->lineStyleColor = 'ffff0000';
			$view->polyStyleColor = 'ffffff00';
			
			$view->show();
		}
		
		public function following() {
			$user_id = getValueOrDefault($_GET['user_id'],-1);
			
			$view = $this->getView('friends');
			
			$view->icon_url = $this->twitter_service->getIconURL($user_id);
			
			$userLocation = $this->twitter_service->getLocation($user_id);
			$view->location = $userLocation;
			
			$following = $this->twitter_service->getFollowing($user_id);
			
			LocationService::reArrangeLocation($userLocation,$following);
			
			$view->friends = $following;
			$view->iconBaseColor = COLOR_FOLLOWING;
			$view->header = "Following";
			
			$view->lineStyleColor = 'ff00ffff';
			$view->polyStyleColor = 'ff0000ff';
			
			$view->show();
		}
		
		public function friends() {
			
		}
		
		public function main() {
			$view = $this->getView('main');
			
			$view->show();
		}
		
	}

?>