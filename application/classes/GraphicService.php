<?php

	class GraphicService {
		
		private static $instance = null;
		private $cache;
		
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new GeoUrlService();
			}
			return self::$instance;
		}
		
		public function __construct() {
	
		}
		
}

?>