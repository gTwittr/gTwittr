<Document>
	<open>1</open>
	<visibility>1</visibility>
	<name></name>
	<description></description>
	<?php
		$balloonStylePath = BASE_PATH . '/views/kmlTemplates/KmlBalloonStyleInclude.kml';
		
		foreach($followers as $follower) {
	?>
		<Style id="follower_placemark_style_<?php echo $follower->twitter_id; ?>">
			<IconStyle>
				<color>ffffffff</color>
		   	<scale>1.0</scale>
		   	<Icon>
					<href><?php echo $follower->icon_url; ?></href>
		   	</Icon>
			</IconStyle>
			<?php include($balloonStylePath); ?>	
		</Style>
	<?php
		}
	?>
	<Style id="default_placemark">
		<IconStyle>
			<color>ffffffff</color>
		   <scale>1.0</scale>
		   <Icon>
				<href><?php echo $icon_url; ?></href>
		   </Icon>
		</IconStyle>
		<?php include($balloonStylePath); ?>
	</Style>
	<!-- MainPlacemark, Navigation -->
	<Placemark id="followers_start">
		<description>
			<![CDATA[
				<div>
					<?php
						foreach($followers as $follower) {
					?>
						<?php echo $follower->screen_name; ?>
					<?php
						}
					?>
				</div>
			]]>
		</description>
		<name></name>
		<LookAt>
			<longitude><?php echo $location->getLongitude(); ?></longitude>
			<latitude><?php echo $location->getLatitude(); ?></latitude>
			<range>440.8</range>
			<tilt>8.3</tilt>
			<heading>2.7</heading>
		</LookAt>
		<Point>
			<coordinates><?php echo $location->getLongitude(); ?>,<?php echo $location->getLatitude(); ?>,0</coordinates>
		</Point>
		<styleUrl>#default_placemark</styleUrl>
	</Placemark>
	<?php 
	foreach($followers as $follower) {
	?>
		<Placemark id="follower_placemark_<?php echo $follower->twitter_id; ?>">
			<description>
				<![CDATA[
					
				]]>	
			</description>
			<name></name>
			<LookAt>
				<longitude><?php echo $follower->location->getLongitude(); ?></longitude>
				<latitude><?php echo $follower->location->getLatitude(); ?></latitude>
				<range>440.8</range>
				<tilt>8.3</tilt>
				<heading>2.7</heading>
			</LookAt>
			<Point>
				<coordinates><?php echo $follower->location->getLongitude(); ?>,<?php echo $follower->location->getLatitude(); ?>,0</coordinates>
			</Point>
			<styleUrl>#follower_placemark_style_<?php echo $follower->twitter_id; ?></styleUrl>
		</Placemark>
	<?php	
	}
	?>
</Document>