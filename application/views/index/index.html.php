test
<?php if($authenticated) { ?>
	<?php echo link_tag('Geotwitter starten','main/main.kml'); ?>
<?php } else { ?>
	<a href="<?php echo $authURL; ?>">Login with Twitter</a>
<?php } ?>