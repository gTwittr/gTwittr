<?php if ($authenticated) { ?>
	<?php die('the end'); ?>
	<h3>Hallo <?php echo $user_data->name; ?>(<?php echo $user_data->screen_name; ?>)</h3>
	<img src="<?php echo $user_data->profile_image_url; ?>" alt="Profilbild" />
	<a href="/main/start.kml">geWittr in Google Earth starten</a>
<?php } else { ?>
	Du konntest leider nicht authentisiert werden...
<?php } ?>