<?php

	class Dispatcher {
		
 		const CONTENT_TYPE_HTML = 'HTML';
		const CONTENT_TYPE_KML = 'KML';
		
		private $registry;
		
		private $path;
		private $args = array();
		private $file = CONTROLLER_PATH;
		private $controller;
		private $action;
		
		function __construct($registry) {
			$this->registry = $registry;
		}
		
		function __destruct() {
			
		}
		
		public function setPath($path) {
			if (!is_dir($path)) {
				throw new Exception($path . 'is not a valid dir');
			}
			$this->path = $path;
		}
		
		public function dispatch() {
			
			$firephp = FirePHP::getInstance(true);
			$firephp->log('start dispatching');
			
			$this->getController();
			//Ist der Controller vorhanden ?
			if (!is_readable($this->file)) {
				echo $this->file;
				die('Not Found');
			}
			//Neue Instanz des Controllers
			include $this->file;
			$class = $this->controller . 'Controller';
			$controller = new $class($this->registry);
			//kann die Action auch ausgeführt werden?
			if (!is_callable(array($controller, $this->action))) {
				//Standardaction
				$action = 'index';
			} else {
				$action = $this->action;
			}
			//action ausführen
			$controller->$action();
		}
		
		private function getController() {
			
			$route = empty($_GET['route']) ? '' : $_GET['route'];
			
			if (empty($route)) {
				$route = 'index';
			} else {
				$route_parts = explode('/',$route);
				$this->controller = $route_parts[0];
				if (isset($route_parts[1])) {
					$this->action = $route_parts[1];
				}
			}
			
			if (empty($this->controller)) {
				$this->controller = 'index';
			}
			
			if (empty($this->action)) {
				$this->action = 'index';
			}
			
			$this->file = $this->file . '/' . $this->controller . 'Controller.php';
			
		}
		
	}

?>