<?php

	class IndexController extends AbstractController {
		
		public function index() {
			$view = $this->getView('index');
			//TwitterService::getInstance()->getPublicTimelineTweets();
			
			$authenticated = $this->twitter_service->isAuthenticated();
			
			if (!$authenticated) {
				$view->authURL = $this->twitter_service->getAuthorizeURL();
			}
			
			$view->authenticated = $authenticated;
			$view->show();
		}
		
	}

?>