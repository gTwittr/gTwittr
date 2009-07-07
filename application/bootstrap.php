<?php
	//konfigurieren
	include_once 'config.php';
	
	set_time_limit(0); 

	set_include_path(get_include_path() . PATH_SEPARATOR . BASE_PATH);

	require_once 'lib/FirePHPCore/FirePHP.class.php';
	require_once 'lib/Cache/Lite.php';
	require_once 'lib/twitteroauth/twitterOAuth.php';
	
	/**
		Sorgt dafür, dass Dateien, die Klassen enthalten automatisch eingeladen werden.
		Scannt dabei über den include_path und definierte Unterordner.
	**/
	function __autoload($class_name) {
		$paths = explode(PATH_SEPARATOR,get_include_path());
		foreach ($paths as $path) {
			$dirs = array('.','classes','models');
			foreach ($dirs as $dir) {
				$f = $path . '/' . $dir . '/' . $class_name . '.php';
				if (file_exists($f)) {
					return require_once $f;
				}
			}
		}
	}
	
	//cache optionen setzen
	define('CACHE_OPTS_ENABLE',true);
	define('CACHE_OPTS_DIR','./tmp/');
	define('CACHE_OPTS_LIFETIME',3600);
	
	//session einlesen
	$route_parts = explode('/',getValueOrDefault($_GET['route'],'no_session/index/index'));
	//default werte
	$session = 'no_session';
	$controller = 'index';
	$view = 'index';
	
	if (count($route_parts) == 3) {
		$session = $route_parts[0];
		$controller = $route_parts[1];
		$view = $route_parts[2];
	} else {
		throw new Exception('cannot create session context');
	}
	
	session_name(SESSION_NAME);
	session_start();
	
	ob_start();
	
	if (ENVIRONMENT == DEVELOPMENT) {
		if ($session == 'no_session') {
			define('GWS_SESSION_ID','4a50918e20f71');	
		}
	}
	
	if (!defined('GWS_SESSION_ID')) {
		define('GWS_SESSION_ID',$session);
	}
	
	Identity::initIdentity();
	
	//die(Identity::getIdentity()->getTwitterId());
	
	$dispatcher = new Dispatcher(Registry::getInstance());
	$dispatcher->setPath(BASE_PATH);
	$dispatcher->dispatch();
	
	ob_end_flush();

	/**
	 * Hilfsfunktionen
	 */
	
	function getValueOrDefault(&$target, $default) {
		if (isset($target)) {
			return $target;
		}
		return $default;
	}
	
?>