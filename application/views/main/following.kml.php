<Document>
	<open>1</open>
	<visibility>1</visibility>
	<name></name>
	<description></description>
	<?php include(BASE_PATH . '/views/kmlTemplates/Schemas.kml'); ?>
	<?php
		//Templatepfade
		$balloonStylePath = BASE_PATH . '/views/kmlTemplates/KmlBalloonStyleInclude.kml';
		$balloonFriendListStylePath = BASE_PATH . '/views/kmlTemplates/KmlBalloonFriendsListStyleInclude.kml';
		
		/*
		 *	Icon-Styles anlegen
		 *
		 *	Die Iconstyles werden über eine Stylemap gesetzt, so dass weniger redundates Markup entsteht
		 *
		 */
		foreach($following as $friend) {
	?>
		<Style id="friend_placemark_style_<?php echo $friend->twitter_id; ?>">
			<IconStyle>
				<color>ffffffff</color>
		   	<scale>1.0</scale>
		   	<Icon>
					<href><?php echo $friend->icon_url; ?></href>
		   	</Icon>
			</IconStyle>	
		</Style>
	<?php
		}
	?>
	<?php
		//Style für eine Seite eines Twitterers
	?>
	<Style id="default_friend_style">
		<?php include($balloonStylePath); ?>
	</Style>
	<?php
		//Style für eine Übersichtsseite
	?>
	<Style id="default_placemark">
		<IconStyle>
			<color>ffffffff</color>
		   <scale>1.0</scale>
		   <Icon>
				<href><?php echo $icon_url; ?></href>
		   </Icon>
		</IconStyle>
		<?php include($balloonFriendListStylePath); ?>
	</Style>
	
	<?php
		//Standardstyle für eine Linie, hier muss die Stärke noch entsprechend angepasst werden
	?>
	<Style id="geWittrStandardLineStyle">
      <LineStyle>
        <color>7fff00ff</color>
        <width>4</width>
      </LineStyle>
      <PolyStyle>
        <color>7f00ff00</color>
      </PolyStyle>
    </Style>
	
	<?php
		//Übersichtsseite
	?>
	<Placemark id="start">
		<name>following</name>
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
		<ExtendedData>
			<SchemaData schemaUrl="#OverviewSchema">
				<SimpleData name="header">Your following</SimpleData>
				<SimpleData name="list">
					<![CDATA[
						<?php
						foreach($following as $friend) {
						?>
						<div class="list_item">
							<?php echo link_tag($friend->screen_name,"#friend_placemark_$friend->twitter_id;balloonFlyto",false,false); ?>
						</div>
						<?php
						}
						?>
					]]>
				</SimpleData>
			</SchemaData>
		</ExtendedData>
	</Placemark>
	<?php 
	foreach ($following as $friend) {
	?>
	<Placemark>
		<name>Line to <?php echo $friend->screen_name; ?></name>
		<LineString>
			<extrude>1</extrude>
			<tessellate>1</tessellate>
			<altitudeMode>clampToGround</altitudeMode>
			<coordinates>
				<?php echo $location->getLongitude(); ?>,<?php echo $location->getLatitude(); ?>,0 <?php echo $friend->location->getLongitude(); ?>,<?php echo $friend->location->getLatitude(); ?>,0 
			</coordinates>
		</LineString>
		<styleUrl>#geWittrStandardLineStyle</styleUrl>
	</Placemark>
	<?php
	}
	?>
	
	<?php 
	foreach($following as $friend) {
	?>
		<Placemark id="friend_placemark_<?php echo $friend->twitter_id; ?>">
			<name><?php echo $friend->screen_name; ?></name>
			<LookAt>
				<longitude><?php echo $friend->location->getLongitude(); ?></longitude>
				<latitude><?php echo $friend->location->getLatitude(); ?></latitude>
				<range>440.8</range>
				<tilt>8.3</tilt>
				<heading>2.7</heading>
			</LookAt>
			<Point>
				<coordinates><?php echo $friend->location->getLongitude(); ?>,<?php echo $friend->location->getLatitude(); ?>,0</coordinates>
			</Point>
			<StyleMap> 
    			<Pair> 
      				<key>normal</key> 
      				<styleUrl>#friend_placemark_style_<?php echo $friend->twitter_id; ?></styleUrl> 
    			</Pair> 
    			<Pair> 
      				<key>highlight</key> 
      				<styleUrl>#friend_placemark_style_<?php echo $friend->twitter_id; ?></styleUrl> 
    			</Pair> 
  			</StyleMap> 
			<styleUrl>#default_friend_style</styleUrl>
			<ExtendedData>
			<SchemaData schemaUrl="#UserSchema">
				<SimpleData name="screen_name"><?php echo $friend->screen_name; ?></SimpleData>
				<SimpleData name="followers_count"><?php echo $friend->followers_count; ?></SimpleData>
				<SimpleData name="following_count"><?php echo $friend->following_count; ?></SimpleData>
			</SchemaData>
		</ExtendedData>
		</Placemark>
	<?php	
	}
	?>
</Document>