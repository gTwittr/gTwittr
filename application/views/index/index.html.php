<?php if($authenticated) { ?>
	<a href="index.kml?gt_session=17">GeoTwitter starten</a>
<?php } else { ?>
	<a href="<?php echo $authURL; ?>">Login with Twitter</a>
<?php } ?>