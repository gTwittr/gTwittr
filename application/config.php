<?php
	
	define('BASE_PATH','./application');
	define('CONTROLLER_PATH',BASE_PATH . '/controller');
	define('VIEWS_PATH',BASE_PATH . '/views');
	
	define('DEVELOPMENT','env_dev');
	define('PRODUCTION','env_prod');
	
	define('ENVIRONMENT',DEVELOPMENT);
	
	define('SESSION_NAME','geotwitter_session');
	define('GEWITTR_SESSION_PARAM_NAME','gws');
	
	define('HOST_NAME',$_SERVER['HTTP_HOST']);

	include('tokens.php');
	
	if (ENVIRONMENT == DEVELOPMENT) {
		
		include('twitter_test_login.php');
		
	}
	
?>