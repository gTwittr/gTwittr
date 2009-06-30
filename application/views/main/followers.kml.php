<Document>
	<open>1</open>
	<visibility>1</visibility>
	<name></name>
	<description></description>
	<?php
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
	</Style>
	<Style id="geWittrStandardLineStyle">
      <LineStyle>
        <color>7fff00ff</color>
        <width>4</width>
      </LineStyle>
      <PolyStyle>
        <color>7f00ff00</color>
      </PolyStyle>
    </Style>
	<!-- MainPlacemark, Navigation -->
	<Placemark id="followers_start">
		<description>
			<![CDATA[
				<div id="followers_list">
					<?php
						foreach($followers as $follower) {
					?>
						<div class="list_item">
							<?php echo link_tag($follower->screen_name,"#follower_placemark_$follower->twitter_id;balloonFlyto",false,false); ?>
						</div>
					<?php
						}
					?>
					<?php echo link_tag('Zurück','main/start.kml#start_mark;balloonFlyto',true); ?>
				</div>
			]]>
		</description>
		<name>Followers</name>
		<open>1</open>
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
	foreach ($followers as $follower) {
	?>
	<Placemark>
		<name>Line to <?php echo $follower->screen_name; ?></name>
		<LineString>
			<extrude>1</extrude>
			<tessellate>1</tessellate>
			<altitudeMode>clampToGround</altitudeMode>
			<coordinates>
				<?php echo $location->getLongitude(); ?>,<?php echo $location->getLatitude(); ?>,0 <?php echo $follower->location->getLongitude(); ?>,<?php echo $follower->location->getLatitude(); ?>,0 
			</coordinates>
		</LineString>
		<styleUrl>#geWittrStandardLineStyle</styleUrl>
	</Placemark>
	<?php
	}
	?>
	
	<?php 
	foreach($followers as $follower) {
	?>
		<Placemark id="follower_placemark_<?php echo $follower->twitter_id; ?>">
			<description>
				<![CDATA[
					<div class="follower">
						<div class="follower_header">
							<?php echo $follower->screen_name; ?>
						</div>
						<?php echo link_tag('Zurück','#followers_start;balloonFlyto',false,false); ?>
					</div>
				]]>	
			</description>
			<name><?php echo $follower->screen_name; ?></name>
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