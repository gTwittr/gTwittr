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
			$view->data->$this->twitter_service->verifyCredentials()
			$view->show();
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
		
	}

?>