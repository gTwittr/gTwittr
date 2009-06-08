<?php

	/**
		Abstrakte Controller-Klasse. Jeder Controller muss mind. die index Methode 
		implementieren und erhält die Registry, eine View-Instanz sowie den Content-Type
		und den Namen der auszuführenden Aktion.
	**/
	abstract class AbstractController {
		
		protected $registry;
		protected $ctype;
		protected $route;
		protected $view;
		
		protected $twitter_service;
		protected $location_service;
		protected $cache;
		
		public function __construct($registry) {
			$this->registry = $registry;
			$this->ctype = Helper::get_value($_GET['ctype'],'html');
			$this->route = Helper::get_value($_GET['route'],'');
			//Namen des Controller (Subklasse) extrahieren und an View übergeben
			$this->view = new ViewTemplate($registry, preg_replace('/^(.*)Controller$/', '$1', get_class($this)),'',$this->ctype);
			$this->twitter_service = TwitterService::getInstance();
			$this->location_service = LocationService::getInstance();
			$this->cache = new Cache_Lite($options = array('cacheDir' => CACHE_OPTS_DIR,'lifeTime' => CACHE_OPTS_LIFETIME));
		}
		
		/**
			Jeder Controller muss mind. die index-Methode implementieren.
		**/
		public abstract function index();
		
		public function getView($name='') {
			$rVal = $this->view;
			if (!empty($name)) {
				$rVal->setName($name);
			}
			return $rVal;
		}
		
		public function requestedKML() {
			return strcmp($this->ctype,'kml') == 0;
		}
	}

?>