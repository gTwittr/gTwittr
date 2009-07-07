<?php

	class TweetsController extends AbstractController {
		
		public function index() {
			$this->latest();
		}
		
		/**
			Liefert Tweets aus dem 'latest' Tweet
		**/
		public function latest() {
			$view = $this->getView('latest');
			$view->show();
		}
		
		public function show() {
			
			$id = getValueOrDefault($_GET['tid'],-1);
			$view = $this->getView('show');
			$view->tweet = TwitterService::getInstance()->getTweet($id);
			$view->show();
			
		}
		
	}

?>