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
	
	define('COLOR_FOLLOWER', "rgb(10,10,10)");
	define('COLOR_FOLLOWING', "rgb(168,168,168)");
	define('COLOR_USER', "rgb(255,124,0)");
	define('IMAGE_CACHE_TIME', 3600);	//in seconds

	include('tokens.php');
	
	if (ENVIRONMENT == DEVELOPMENT) {
		
		include('twitter_test_login.php');
		
	}
	
?>