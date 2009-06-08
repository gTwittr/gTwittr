<script type="text/javascript">
	function intercept(event) {
		//return false;
	}
</script>
<div id="login_form">
	<form action="doLogin.html" method="post">
		<label for="username_field" >Benutzername</label>
		<input id="username_field" type="text" name="username" onkeydown="return intercept(event);"/>
		<label for="password_field">Passwort</label>
		<input id="password_field" type="password" name="password" />
		<input type="submit" value="Einloggen"/>
	</form>
</div>
<a href="index.html">index</a>