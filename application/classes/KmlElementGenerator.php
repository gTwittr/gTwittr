<?php

class KmlElementGenerator {
	
	var $templateContent = '';
	
	function __construct($elementName) {
		$template = BASE_PATH . '/views/kmlTemplates/Kml'.$elementName.'Template.kml';
		
		if (!file_exists($template)) {
			exit ("$template does not exists");
		}
		
		$handle = fopen ($template, "r");
		while (!feof($handle)) {
  			$buffer = fgets($handle);
  			$this->templateContent =  $this->templateContent.$buffer;
		}
		fclose ($handle);
	}

	function setValue($name, $value) {
		$this->templateContent = str_replace("@$name@", $value, $this->templateContent);
	}
	
	function setValues($map) {
		foreach ($map as $key => $value) {
			$this->setValue($key, $value);
		}
	}

	function generate() {
		return $this->templateContent;
	}
	
}

?>