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
		foreach($friends as $friend) {
	?>
		<Style id="friend_placemark_style_<?php echo $friend->twitter_id; ?>">
			<IconStyle>
				<color>ffffffff</color>
		   	<scale>1.0</scale>
		   	<Icon>
					<href><?php echo GraphicService::getInstance()->generateProfileImage($friend->icon_url,$iconBaseColor); ?></href>
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
				<href><?php echo GraphicService::getInstance()->generateProfileImage($icon_url,COLOR_USER); ?></href>
		   </Icon>
		</IconStyle>
		<?php include($balloonFriendListStylePath); ?>
	</Style>
	
	<?php
		function getLineStyleName($strength) {
			$strengthStripped = str_replace('.','',$strength);
			return "lineStyle_$strengthStripped";
		}
	
		foreach(array(0.0,0.3,0.5,0.6,1.0) as $strength) {
	?>
		<Style id="<?php echo getLineStyleName($strength); ?>">
	      <LineStyle>
	        <color><?php echo $lineStyleColor; ?></color>
	        <width><?php echo (0.5 + ($strength * 5)); ?></width>
	      </LineStyle>
	      <PolyStyle>
	        <color><?php echo $polyStyleColor; ?></color>
	      </PolyStyle>
	    </Style>	
	<?php
		}
	?>
	<?php
		//Übersichtsseite
	?>
	<Placemark id="start">
		<name><?php echo $header; ?></name>
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
				<SimpleData name="header"><?php echo $header; ?></SimpleData>
				<SimpleData name="list">
					<![CDATA[
						<?php
						foreach($friends as $f) {
						?>
							<div class="avatarContainer">
								<div class="avatar" style="background:url('<?php echo $f->icon_url; ?>'); width:73px; height:73px; background-repeat: none;">
									<a class="nameOverlay" href="#"><?php echo $f->screen_name; ?></a>
								</div>
								<?php echo link_tag('view &raquo;',"#friend_placemark_$f->twitter_id;balloonFlyto",false,false); ?>
							</div>
						<?php	
						}
						?>
						<div class="clearfix"></div>
					]]>
				</SimpleData>
			</SchemaData>
		</ExtendedData>
	</Placemark>
	<?php 
	foreach ($friends as $friend) {
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
		<styleUrl><?php echo getLineStyleName($friend->strength); ?></styleUrl>
	</Placemark>
	<?php
	}
	?>
	
	<?php 
	foreach($friends as $friend) {
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
				<SimpleData name="location_name"><?php echo $friend->location_name; ?></SimpleData>
				<SimpleData name="web_url"><?php echo $friend->url; ?></SimpleData>
				<SimpleData name="bio"><?php echo $friend->description; ?></SimpleData>
				<SimpleData name="followers_link"><![CDATA[<?php echo link_tag('Followers',"main/followers.kml?user_id=$friend->twitter_id#start;balloonFlyto",true); ?>]]></SimpleData>
				<SimpleData name="following_link"><![CDATA[<?php echo link_tag('Following',"main/following.kml?user_id=$friend->twitter_id#start;balloonFlyto",true); ?>]]></SimpleData>
				<SimpleData name="followers_count"><?php echo $friend->followers_count; ?></SimpleData>
				<SimpleData name="following_count"><?php echo $friend->following_count; ?></SimpleData>
				<SimpleData name="tweets_list">
					<![CDATA[
						<?php
							foreach($friend->tweets as $tweet) {
						?>
							<div class="tweet">
								<div class="messageBox">
									<div>
										<img src="<?php print public_resource_url('images/tweets/sc-tweet_bubbleTop.gif'); ?>" width="307" height="12" />
									</div>
									<div class="messageText">
										<?php echo $tweet->text; ?><br />
										<?php echo link_tag('&raquo; view',"tweets/show.kml?tid=$tweet->id",true); ?>
									</div>
									<div>
										<img src="<?php print public_resource_url('images/tweets/sc-tweet_bubbleBottom.gif'); ?>" width="307" height="10" />
									</div>
								</div>
								<div class="flag">
									<img src="<?php print public_resource_url('images/tweets/sc-tweet_flag.gif'); ?>" width="15" height="11" />
								</div>
								<div class="clearfix"></div>
							</div>
						<?php
							}
						?>
					]]>
				</SimpleData>
				<SimpleData name="icon_url"><?php echo $friend->icon_url; ?></SimpleData>
			</SchemaData>
		</ExtendedData>
		</Placemark>
	<?php	
	}
	?>
</Document>