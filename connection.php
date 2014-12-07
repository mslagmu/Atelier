<!DOCTYPE html> 
<html>
<head>
	<title>Page Title</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	<link rel="stylesheet" href="jqm/jquery.mobile-1.4.5.min.css" />
	<script src="jquery-1.11.1.min.js"></script>
	<script src="jqm/jquery.mobile-1.4.5.min.js"></script>
<script>
function sendpasswd_validate(data,status,x){
	if (data == "OK") {
		$("#logingMessage").html("email evoyé");
	} else {
		$("#logingMessage").html(data);
	}
	$.mobile.loading("hide");
}

function sendpwd(){
		$.mobile.loading("show");
		var data = { action : "send", login : $("#trigramme").val() };
		$.get("password.php",data,sendpasswd_validate,"html");
};


function log_validate(data,status,x){
	if ( data == "OK" ) {
		location = "/atelier/";
		//$(divID).parent().hide();
	} else {
		$("#logingMessage").html(data);
	}
	$.mobile.loading("hide");
}


$("document").ready(function() {
	$("#loginbut").click(function(){
		var data = { login : $("#trigramme").val(), pwd : $("#pwd").val() };
		$.get("login.php",data,log_validate,"html");
		$.mobile.loading("show");
	});
});

</script>
</head>
<body>

<!-- Start of first page -->

<div data-role="page" id="login">
	<div data-role="header">
		<h1>Connectez vous</h1>
	</div>
	<div role="main" class="ui-content">	
		<div id="logingMessage"></div>
		<label for="login">Trigramme:</label>
		<input name="login" type="text" id="trigramme" required>
		<label for="pwd" >Mot de Passe:</label>
		<input name="pwd" id="pwd" type="password">
		<button id="loginbut" onclick("login();") >LOGIN</button>
		Si vous avez perdu votre mot de passe, tapez votre trigramme dans le champs si dessus et 
		<a href="#" onclick="sendpwd();">cliquez ici</a>. Un mail vous sera envoyé.
	</div>
</div><!-- /page -->
</body>
</html>
