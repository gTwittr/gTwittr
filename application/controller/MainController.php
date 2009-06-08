<?php

	class MainController extends AbstractController {
		
		public function index() {
		}
		
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
		
		public function callback() {
			$view = $this->getView('callback');
			
			//access key von Twitter in Empfang nehmen
			$this->twitter_service->getAccessToken();
			
	      /* Save the access tokens. Normally these would be saved in a database for future use. */
	      $_SESSION['oauth_access_token'] = $tok['oauth_token'];
	      $_SESSION['oauth_access_token_secret'] = $tok['oauth_token_secret'];
			
			
			$view->show();
		}
	}

?>