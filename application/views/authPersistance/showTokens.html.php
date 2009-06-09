<script type="text/javascript">
	function intercept(event) {
		//return false;
	}
</script>
<?php
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
			<td><h4>twitterId</h4></td>
			<td><h4>token</h4></td>
			<td><h4>secret</h4></td>
		</tr>
		<?php
			foreach ( $authTokens as $userAuthToken ) {
		?>
		<tr>
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
