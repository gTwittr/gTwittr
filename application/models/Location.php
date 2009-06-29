<?php

	class Location {
		
		private $name;
		
		private $longitude;
		private $latitude;
		private $altitude;
		
		public static function getRandomLocation() {
			$r_lon = (rand(-9000,9000)/100);
			$r_lat = (rand(-18000,18000)/100);
			$alt = 0;
			$name = 'somewhere';
			return new Location($r_lon,$r_lat,$alt,$name);
		}
		
		public function __construct($lon,$lat,$alt,$name='unnamed') {
			$this->longitude = $lon;
			$this->latitude = $lat;
			$this->altitude = $alt;
			$this->name = $name;
		}
		
		public function getLatitude() {
			return $this->latitude;
		}
		
		public function getLongitude() {
			return $this->longitude;
		}
		
		public function getAltitude() {
			return $this->altitude;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function __toString() {
			return 'Latitude: ' . $this->latitude . ', Longitude: ' . $this->longitude . ', Altitude: ' . $this->altitude . ', Name: ' . $this->name;
		}
		
	}

?>