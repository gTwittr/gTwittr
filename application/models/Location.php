<?php

	class Location {
		
		private $name;
		
		private $longitude;
		private $latitude;
		private $altitude;
		
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