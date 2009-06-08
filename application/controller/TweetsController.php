<?php

	class TweetsController extends AbstractController {
		
		public function index() {
			$this->latest();
		}
		
		/**
			Liefert Tweets aus dem 'latest' Tweet
		**/
		public function latest() {
			echo 'test';
		}
		
	}

?>