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
		
	}

?>