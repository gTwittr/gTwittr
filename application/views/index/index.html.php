test
<?php if($authenticated) { ?>
	<?php echo link_tag('Geotwitter starten','main/start.kml'); ?>
<?php } else { ?>
	<a href="<?php echo $authURL; ?>">Login with Twitter</a>
<?php } ?>