<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>geWittr - Test Layout</title>
<link href="public/stylesheets/application.css" rel="stylesheet" type="text/css" />
</head>
<body>
<span style="color: #FFF; font-style: italic; font-size: 0.8em;"><?php echo (ENVIRONMENT == DEVELOPMENT ? 'Currently in development mode' : ''); ?></span>
<div id="wrapper">
		<div id="header"><img src="public/images/logo.gif" width="960" height="150" onmousedown="return false;"/></div>
		<div id="navbar">
			<ul class="nav">
				<li>Home</li>
				<li>geWittr starten</li>
			</ul>
		</div>
		<div id="content">
			<?php include $path; ?>
		</div>
		<div id="footer">FOOTER IMPRINT</div>
	</div>
</body>
</html>