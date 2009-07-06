<?php

	class AuthPersistanceController extends AbstractController {
		
		public function index() {
			$view = $this->getView("showTokens");
			$view->show();
		}
		
		public function addToken() {
			$view = $this->getView("addToken");

			//$view->setName('authPer');
			$view->show();
		}
		
		public function setToken() {
			$view = $this->getView("showTokens");
			
			PersistanceService::getInstance()->setToken($username = $_POST['twitterId'], $username = $_POST['token'], $username = $_POST['secret']);
			
			$view->show();
		}
			

		
	}

?>