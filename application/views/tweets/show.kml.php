<Document>
	<open>1</open>
	<visibility>1</visibility>
	<name></name>
	<description></description>
	<?php include(BASE_PATH . '/views/kmlTemplates/Schemas.kml'); ?>
	<?php
		//Templatepfade
		$balloonStylePath = BASE_PATH . '/views/kmlTemplates/KmlTweetBalloonStyleInclude.kml';
	?>
	<Style id="default_placemark_style">
		<?php include($balloonStylePath); ?>
		<IconStyle>
			<color>ffffffff</color>
		   <scale>1.0</scale>
		   <Icon>
				<href><?php echo $tweet->icon_url; ?></href>
		   </Icon>
		</IconStyle>
	</Style>
	<Placemark>
		<name><?php echo substr($tweet->text, 0, 15) . '...'; ?></name>
		<LookAt>
			<longitude><?php echo $tweet->location->getLongitude(); ?></longitude>
			<latitude><?php echo $tweet->location->getLatitude(); ?></latitude>
			<range>440.8</range>
			<tilt>8.3</tilt>
			<heading>2.7</heading>
		</LookAt>
		<Point> 
			<coordinates><?php echo $tweet->location->getLongitude() . "," . $tweet->location->getLatitude() . "," . $tweet->location->getAltitude(); ?></coordinates>
		</Point>
		<styleUrl>#default_placemark_style</styleUrl>
		<ExtendedData>
		<SchemaData schemaUrl="#TweetSchema">
			<SimpleData name="screen_name"><?php echo $tweet->screen_name; ?></SimpleData>
			<SimpleData name="text"><?php echo $tweet->text; ?></SimpleData>
		</SchemaData>
	</ExtendedData>
	</Placemark>
</Document>