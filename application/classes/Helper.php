<?php

	class Helper {
		
		public static function get_value($value, $default) {
			if (isset($value)) {
				return $value;
			} else {
				return $default;
			}
		}
		
		public static function get_from_session($name, $default) {
			
		}
		
	}

?>