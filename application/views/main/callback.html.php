<?php if ($authenticated) { ?>
	<h3>Hallo <?php echo $user_data->name; ?>(<?php echo $user_data->screen_name; ?>)</h3>
	<img src="<?php echo $user_data->profile_image_url; ?>" alt="Profilbild" />
	<?php echo link_tag('Geotwitter starten','main/start.kml'); ?>
<?php } else { ?>
	Du konntest leider nicht authentisiert werden...
<?php } ?>