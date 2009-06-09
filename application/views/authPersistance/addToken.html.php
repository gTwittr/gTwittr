<script type="text/javascript">
	function intercept(event) {
		//return false;
	}
</script>
<div id="addToken_form">
	<form action="setToken.html" method="post">
		<label for="twitterId" >twitterId</label>
		<input id="twitterId" type="text" name="twitterId" size="20" /><br/>
		<br/>
		<label for="token_field">token</label>
		<input id="token_field" type="text" name="token" size="40" /><br/>
		<br/>
		<label for="secret_field">secret</label>
		<input id="secret_field" type="text" name="secret" size="40" /><br/>
		<br/>
		<input type="submit" value="Speichern"/>
	</form>
</div>
<br/>
<a href="showTokens.html">show Tokens</a><br/>
<a href="index.html">index</a>
