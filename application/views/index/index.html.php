<?php if($authenticated) { ?>
	<?php echo link_tag('Geotwitter starten','index.kml?sdhjas=23'); ?>
<?php } else { ?>
	<a href="<?php echo $authURL; ?>">Login with Twitter</a>
<?php } ?>