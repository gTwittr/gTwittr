<script type="text/javascript">
	function intercept(event) {
		//return false;
	}
</script>
<?php
	
	
	$placeMap = array (
					"name" => "Testplace",
					"description" => "Test description",
					"longitude" => "8.802581",
					"latitude" => "53.075465",
					"altitude" => "0"
					);
					
	$placemark = new KmlElementGenerator('Placemark');
	$placemark->setValues($placeMap);
	
	$overlay = new KmlElementGenerator('ScreenOverlay');
	$overlay->setValue('name', 'testOverlay');
	$overlay->setValue('icon', 'http://www.panten.org/files/testOverlay.jpg');
	
	$line = new KmlElementGenerator('Line');
	$line->setValue('name', 'TestLine');
	$line->setValue('description', 'Test Description');
	$line->setValue('coordinates', '8.802581, 53.075465, 10
		8.902581, 53.175465, 10');
	
	$kmlDocument = new KmlElementGenerator('Document');
	$kmlDocument->setValue('content', $placemark->generate() . $overlay->generate() . $line->generate());
	
	echo $kmlDocument->generate();
	
	$json = PersistanceService::getInstance()->getTokens();
	$authTokens = json_decode($json);
	
	$firephp = FirePHP::getInstance(true);
	$firephp->log('hallo');
	
	$firephp->log($json);
	$firephp->log($authTokens);
?>

<div id="addToken_form">
	<table cellpadding=10>
		<tr>
			<td><h4>sessionId</h4></td>
			<td><h4>twitterId</h4></td>
			<td><h4>token</h4></td>
			<td><h4>secret</h4></td>
		</tr>
		<?php
			foreach ( $authTokens as $userAuthToken ) {
		?>
		<tr>
			<td><?php echo $userAuthToken->sessionId; ?></td>
			<td><?php echo $userAuthToken->twitterId; ?></td>
			<td><?php echo $userAuthToken->token; ?></td>
			<td><?php echo $userAuthToken->secret; ?></td>
		</tr>
		<?php
			}
		?>
	</table>
</div>
<br/>
<a href="addToken.html">set Token</a><br/>
<a href="index.html">index</a>
