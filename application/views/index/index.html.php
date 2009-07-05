<img src="<?php echo GraphicService::getInstance()->generateProfileImage('http:\/\/s3.amazonaws.com\/twitter_production\/profile_images\/69546449\/2793452349_794ae79227_mini_studi_normal.jpg','#FF0000'); ?>" alt="bla" />

<?php if($authenticated) { ?>
	<?php echo link_tag('Geotwitter starten','main/start.kml'); ?>
<?php } else { ?>
	<a href="<?php echo $authURL; ?>">Login with Twitter</a>
<?php } ?>