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
	
	/**
	 * Generiert einen Link
	 * 
	 * @param $title
	 * @param $href
	 * @param $absolute
	 * @return unknown_type
	 */
	function link_tag($title,$href,$absolute=false,$prepend_session=true) {
		//eingegebene url autrennen
		$parts = preg_split('/\?/',$href);
		//Session-Parameter bauen
		$session_param_name = GEWITTR_SESSION_PARAM_NAME;
		$session_param_value = Identity::getIdentity()->getSessionId();
		$session_param = "$session_param_name=$session_param_value";
		
		if (!empty($parts) && count($parts) == 2) {
			//es wurden paramter anegfügt
			$base_path = $parts[0];
			$parameters = $parts[1];
			//parameter wiederum einzeln aufsplitten
			$params = preg_split('/&/',$parameters);
			//array_push($params,$session_param);
			$parameters = implode('&',$params);
			$href = "$base_path?$parameters";
		} else if (count($parts) == 1) {
			$href = $parts[0];//"$parts[0]?$session_param";
		}
		
		//session parameter vorn anhägen
		if ($prepend_session) {
			$href = $session_param_value . "/" . $href;	
		}
		//wenn der Pfad absulote sein soll, dann die baseurl anhängen
		if ($absolute) {
			$href = 'http://' . HOST_NAME . '/' . $href;
		}
		//link zurückgeben
		return "<a href=\"$href\">$title</a>";
	}
	
	function absolute_url($url) {
		return 'http://' . HOST_NAME . '/' . $url;
	}
	
	function absolute_full_url($url) {
		return 'http://' . HOST_NAME . '/' . Identity::getIdentity()->getSessionId() . '/' . $url;
	}

?>