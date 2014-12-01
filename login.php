<?php
	require_once("open_db.php");
	
	$login = strtoupper($_GET["login"]);
	
	$result=$db->querySingle("SELECT trigramme,password FROM users WHERE trigramme='$login'",true);
	
	if ( isset($result["trigramme"]) ) {
		if ( $result["password"] == $_GET["pwd"]) {
			echo "OK";
			$_SESSION["login"] = $login;
		} else {
			echo "Mauvais mot de passe.";
		}
	} else {
		echo "Mauvais utilisateur.";
	}

?>
