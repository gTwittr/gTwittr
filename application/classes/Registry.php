<?php

	class Registry {
		
		private $vars = array();
		private static $instance = null;
		
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new Registry();
			}
			return self::$instance;
		}
		
		public function __construct() {
			
		}
		
		public function __set($name, $value) {
			$this->vars[$name] = $value;
		}
		
		public function __get($name) {
			return $this->vars[$name];
		}
		
	}

?>