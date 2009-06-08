<?php
	
	define('BASE_PATH','./application');
	define('CONTROLLER_PATH',BASE_PATH . '/controller');
	define('VIEWS_PATH',BASE_PATH . '/views');
	
	define('DEVELOPMENT','env_dev');
	define('PRODUCTION','env_prod');
	
	define('ENVIRONMENT',DEVELOPMENT);
	
	define('SESSION_NAME','geotwitter_session');
	
	define('HOST_NAME',$_SERVER['HTTP_HOST']);
	
	//oauth
	define('TWITTER_CONSUMER_KEY','pGSROjv5erXNwwNByH5G1w');
	define('TWITTER_CONSUMER_SECRET','QGi2YqdCXCx0AWXzFV9RA84kdJuhhKuLD2bTxApHWo');
	
?>