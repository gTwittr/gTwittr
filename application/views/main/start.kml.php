<Document>
	<Placemark>
		<name>Start</name>
		<description>
			<![CDATA[
				<?php if ($authenticated) { ?>	
					Willkommen
					<div class="tweets">
						<div class="tweet"></tweet>
					</div>
				<?php } else { ?>
					Bitte erst einloggen
				<?php } ?>
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