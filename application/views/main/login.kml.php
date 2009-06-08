<Document>
	<Placemark>
		<name>Login (<?php echo $_COOKIE['oauth_token']; ?>)</name>
		<description>
			<![CDATA[
				<style type="text/css">
					body {
						margin: 0;
						padding: 0;
					}
					iframe {
						width: 400px;
						height: 300px;
						border: 0;
					}
				</style>
				<iframe src="http://www.geotwitter.local/main/index.kml">
					<i>this does not work</i>
				</iframe>
			]]>
		</description>
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
	</Placemark>
</Document>
