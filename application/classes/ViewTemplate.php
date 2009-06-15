<?php

	class ViewTemplate {
		
		const HTML_FILE = 'html';
		const JSON_FILE = 'json';
		const KML_FILE = 'kml';
		
		private $registry;
		private $vars = array();
		
		private $controller;
		private $name;
		private $ctype;
		
		public function __construct($registry, $controller, $name, $ctype) {
			$this->registry = $registry;
			$this->controller = $controller;
			$this->name = $name;
			$this->ctype = $ctype;
		}
		
		public function __set($name, $value) {
			$this->vars[$name] = $value;
		}
		
		public function __get($name) {
			return $this->vars[$name];
		}
		
		public function show() {
			
			$firephp = FirePHP::getInstance(true);
			$firephp->group('ViewTemplate->show');
			
			$templatePath = BASE_PATH . '/views/template.' . $this->ctype . '.php';
			$firephp->log($templatePath, 'TemplatePath');
			
			if (!file_exists($templatePath) || !is_readable($templatePath)) {
				throw new Exception('Cannot read template');
			} 
		
			$path = BASE_PATH . '/views/' . $this->controller . '/' . $this->name . '.' . $this->ctype . '.php';

			if (!file_exists($path) || !is_readable($path)) {
				throw new Exception('View with name ' . $this->name . ' and content-type ' . $this->ctype . ' cannot be used');
			}
			$this->generateHeader();
			
			$firephp->log('Path: ' . $path);
			
			$firephp->log('copy vars');
			foreach ($this->vars as $key => $value) {
				$$key = $value;
				$firephp->log($value,$key);
			}
			$firephp->log('finished copy vars');
			include $templatePath;
			//include BASE_PATH . '/views/template.' . $this->ctype . '.php';
			
			$firephp->groupEnd();
		}
		
		public function setName($name) {
			$this->name = $name;
		}
		
		public function setCType($ctype) {
			$this->ctype = $ctype;
		}
		
		private function generateHeader() {
			$content_type = 'text/html';
			if ($this->isKML()) {
				//header('Content-Disposition: attachment; filename="' . $this->name . '.kml"');
				$content_type = 'application/vnd.google-earth.kml+xml';
			}
			header('Content-Type: ' . $content_type);
		}
		
		public function isHTML() {
			return strcmp($this->ctype,self::HTML_FILE) == 0;
		}
		
		public function isKML() {
			return strcmp($this->ctype,self::KML_FILE) == 0;
		}
		
		public function isJSON() {
			return strcmp($this->ctype,self::JSON_FILE) == 0;
		}
	}

?>