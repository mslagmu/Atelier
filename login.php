<?
	require_once("open_db.php");
	
	$login = strtoupper($_GET["login"]);
	
	$result=$db->querySingle("SELECT trigramme,password,name FROM users WHERE trigramme='$login'",true);
	
	$config=$db->querySingle("select * from config",true);
	
	$_SESSION["date_debut"] = $config["date"];
	
	if ($login == "ADMIN" && $config["admpwd"] == $_GET["pwd"] ) {
		echo "OK";
		$name = "Administrateur";
		$_SESSION["login"] = $login;
		$_SESSION["name"] = $name;
		exit;
	}
	
	if ( isset($result["trigramme"]) ) {
		if ( ( $result["password"] == $_GET["pwd"] ) ) {
			echo "OK";
			$name = $result["name"];
			$_SESSION["login"] = $login;
			$_SESSION["name"] = $name;
			exit;
		} else {
			echo "Mauvais mot de passe.";
		}
	} else {
		echo "Mauvais utilisateur.";
	}

?>
