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
			
			$kmlValues = array();
			$kmlValues['name'] = 'Test';
			
			if ($this->twitter_service->isAuthenticated()) {
				$view->authenticated = true;
				
				$myLocation = $this->twitter_service->getLocation();
				$following = $this->twitter_service->getFollowing();
				$followers = $this->twitter_service->getFollowers();
				
				addLocationToMap($myLocation,$kmlValues);
				
				//following
				$followingCount = 0;
				if ($following) {
					$followingCount = count($following);
				}
				//followers
				$followerCount = 0;
				if ($followers) {
					$followerCount = count($followers);
				}
				$twitter_name = $this->twitter_service->getTwitterName();
				//
				$kmlValues['description'] = "Willkommen $twitter_name";
				$iconUrl = $this->twitter_service->getIconUrl();
				$kmlValues['icon'] = GraphicService::getInstance()->generateProfileImage($iconUrl,COLOR_USER);
				$kmlValues['followers'] = $followerCount;
				$kmlValues['followersLink'] = link_tag('Link','main/followers.kml#start;balloonFlyto',true);
				$kmlValues['following'] = $followingCount;
				$kmlValues['followingLink'] = link_tag('Link','main/following.kml#start;balloonFlyto',true);
				$kmlValues['overlay_path'] = GraphicService::getInstance()->generateInfoBarImage($iconUrl,$twitter_name,$followingCount,$followerCount);
			} else {
				addLocationToMap($this->location_service->findLocationByName('Wildeshausen'),$kmlValues);
			}
			
			
			
			$view->kmlValues = $kmlValues;
			
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