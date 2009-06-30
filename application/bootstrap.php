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
	
	define('CACHE_OPTS_ENABLE',true);
	define('CACHE_OPTS_DIR','./tmp/');
	define('CACHE_OPTS_LIFETIME',3600);
	
	if (ENVIRONMENT == DEVELOPMENT) {
		define('GWS_SESSION_ID','4a36337484b7a');	
	} else {
		define('GWS_SESSION_ID',$_GET[GEWITTR_SESSION_PARAM_NAME]);
	}
	
	
	session_name(SESSION_NAME);
	session_start();
	
	ob_start();

	Identity::initIdentity();
	
	$dispatcher = new Dispatcher(Registry::getInstance());
	$dispatcher->setPath(BASE_PATH);
	$dispatcher->dispatch();
	
	ob_end_flush();

?>