	<Document>
		<Schema name="StartOverviewSchmema" id="OverviewSchema">
			<SimpleField type="string" id="screen_name">
				<displayName>
					<![CDATA[Name:]]>
				</displayName>
			</SimpleField>
			<SimpleField type="string" id="location_name">
				<displayName>
					<![CDATA[<b>Location:</b>]]>
				</displayName>
			</SimpleField>
			<SimpleField type="string" id="web_url">
				<displayName>
					<![CDATA[Web:]]>
				</displayName>
			</SimpleField>
			<SimpleField type="string" id="bio">
				<displayName>
					<![CDATA[Bio:]]>
				</displayName>
			</SimpleField>
			<SimpleField type="string" name="tweets_list">
				<displayName>
					<![CDATA[Recent Tweets]]>
				</displayName>
			</SimpleField>
			<SimpleField type="string" id="following_link">
				<displayName>
					<![CDATA[Following]]>
				</displayName>
			</SimpleField>
			<SimpleField type="string" id="follower_link">
				<displayName>
					<![CDATA[Follower]]>
				</displayName>
			</SimpleField>
		</Schema>
		<open>1</open>
		<visibility>1</visibility>
		<name></name>
		<description></description>
		<ScreenOverlay>
			<open>0</open>
			<name>Personal Overlay</name>
			<Icon>
			    <href><?php echo $overlay_path; ?></href>
			</Icon>
			<overlayXY x="0.5" y="1" xunits="fraction" yunits="fraction"/>
			<screenXY x="0.5" y="1" xunits="fraction" yunits="fraction"/>
			<rotation>0.0</rotation>
			<size x="0" y="0" xunits="pixels" yunits="pixels"/>
		</ScreenOverlay>
		<Style id="default_placemark">
			<BalloonStyle>
			  <!-- a background color for the balloon -->
			  <bgColor>2d1E1E1E</bgColor>
			  <!-- styling of the balloon text -->
			  <text><![CDATA[

			<style type="text/css">

				html,body {
					min-width: 400px;
					min-height: 400px;
					overflow: hidden;
					background-color: #1e1e1e;
					margin: 0;
					padding: 0;
				}

				body {	
					margin:0px; padding:0px;
				}

				ul
				{
				margin: 0;
				padding: 0;
				}

				li
				{
					list-style: none;
				}

				.clearfix
				{
				 clear: both;
				}

				div.container {
					width: 480px;
					margin-left: auto;
					margin-right: auto;
					font-family: Georgia, "Times New Roman", Times, serif;
					font-size: 24px;
					font-weight: normal;
					color: #000;
				}
				
				.footer {
					height: 56 px;
					text-align:center;
					/*margin-top: -30px;*/
				}

				.profile
				{
					background-color: #FFF;
					font-size: 14px;
					float: left;
					padding:0 0 10px 10px;
				}

				.profile div 
				{
					float: left;
				}

				.profile div ul
				{
					width: 309px;
					padding: 0 10px;
				}
				
				div.recent_tweets {
					width: 412px;
					background-color:#FFF;
					float: left;
				}
				
				div.recent_tweets > div {
					text-align: center;
					border-top-width: 1px;
					border-top-style: solid;
					border-top-color: #e3e3e3;
				}
				
				div.tweet {
					display: block;
				}
				
				div.menuOptions {
					width: 412px;
					background-color:#FFF;
					float: left;
				}

				.menuOptions li {
				height: 49px;
				text-transform: uppercase;
				text-align: center;
				line-height: 48px;
				border-top-width: 1px;
				border-top-style: solid;
				border-top-color: #e3e3e3;	
				display: block;
				}

				.menuOptions li a {
					display: block;
				}

				.menuOptions li a:hover {
					display: block;
					background-color: #f5f5f5;
				}

				.text_reply{
					color:#504c4c
				}

				.text_sender{
					color:#ffba0f;
					font-weight:bold;
				}

				a {
					color: #ffba0f;
					text-decoration: none;
				}
				
				div.footer {
					width: 412px;
					float: left;
				}

				</style>

						<div class="container">

							<div class="header">
								<img src="<?php print public_resource_url('images/overview/sc-over_01.gif'); ?>" width="412" height="82" />
							</div>

							<div class="profile">
								<div><img src="<?php print $icon_url_plain; ?>" width="73" height="73" />
								</div>
								<div>
									<ul>
										<li><b>Name:</b> $[StartOverviewSchmema/screen_name]</li>
										<li><b>Location:</b> $[StartOverviewSchmema/location_name]</li>
										<li><b>Web:</b> <a href="$[StartOverviewSchmema/web_url]">$[StartOverviewSchmema/web_url]</a></li>
										<li><b>Bio:</b> $[StartOverviewSchmema/bio] </li>
									</ul>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="recent_tweets">
								$[StartOverviewSchmema/tweets_list]
							</div>
							<div class="menuOptions">
								<ul>
									<li>$[StartOverviewSchmema/followers_link]</li>
									<li>$[StartOverviewSchmema/following_link]</li>
								</ul>
							</div>
							<div class="footer">
								<img src="<?php print public_resource_url('images/overview/sc-over_02.gif'); ?>" width="412" height="10" /><br />
								<img src="<?php print public_resource_url('images/overview/sc-over_03.gif'); ?>" width="124" height="46" />
							</div>

						</div>

			  		]]>
				</text>
			</BalloonStyle>
			<IconStyle>
				<color>ffffffff</color>
			   <scale>1.0</scale>
			   <Icon>
					<href><?php print $icon_url; ?></href>
			   </Icon>
			</IconStyle>
		</Style>
		<Placemark id="start_mark">
			<name>Start</name>
			<LookAt>
				<longitude><?php echo $location->getLongitude(); ?></longitude>
				<latitude><?php echo $location->getLatitude(); ?></latitude>
				<altitude><?php echo $location->getAltitude(); ?></altitude>
				<range>440.8</range>
				<tilt>8.3</tilt>
				<heading>2.7</heading>
			</LookAt>
			<Point>
				<coordinates><?php echo $location->getLongitude(); ?>,<?php echo $location->getLatitude(); ?>,<?php echo $location->getAltitude(); ?></coordinates>
			</Point>
			<styleUrl>#default_placemark</styleUrl>
			<ExtendedData>
			<SchemaData schemaUrl="#OverviewSchema">
				<SimpleData name="screen_name"><?php echo $screen_name; ?></SimpleData>
				<SimpleData name="location_name"><?php echo $location_name; ?></SimpleData>
				<SimpleData name="web_url"><?php echo $web_url; ?></SimpleData>
				<SimpleData name="bio"><?php echo $bio; ?></SimpleData>
				<SimpleData name="followers_link"><![CDATA[<?php echo $followers_link; ?>]]></SimpleData>
				<SimpleData name="following_link"><![CDATA[<?php echo $following_link; ?>]]></SimpleData>
				<SimpleData name="tweets_list">
					<![CDATA[
						<?php
							foreach($tweets as $tweet) {
						?>
							<div class="tweet">
								<div class="text">
									<?php echo $tweet->text; ?>
								</div>
								<?php echo link_tag('&raquo;',"tweets/show.kml?id=$tweet->id"); ?>
							</div>
						<?php
							}
						?>
					]]>
				</SimpleData>
			</SchemaData>
		</ExtendedData>
		</Placemark>
</Document>