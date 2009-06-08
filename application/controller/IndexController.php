<?php

	class IndexController extends AbstractController {
		
		public function index() {
			$view = $this->getView();
			
			TwitterService::getInstance()->getPublicTimelineTweets();
			
			$view->setName('index');
			$view->show();
		}
		
	}

?>