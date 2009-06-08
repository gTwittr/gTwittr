<?php

	class IndexController extends AbstractController {
		
		public function index() {
			$view = $this->getView('index');
			//TwitterService::getInstance()->getPublicTimelineTweets();
			
			$view->authURL = $this->twitter_service->getAuthorizeURL();
			
			$view->show();
		}
		
	}

?>