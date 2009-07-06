<?php

	class Tweet {
		
		private $message;
		private $location;
		private $created_at;
		
		public function __construct($message,$location) {
			$this->message = $message;
			$this->location = $location;
		}
		
		public function getMessage() {
			return $this->message;
		}
		
		public function getLocation() {
			return $this->location;
		}
		
		public function __toString() {
			return $this->message;
		}
	}
	
?>