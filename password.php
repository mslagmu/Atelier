<?
	require_once("open_db.php");
	require_once("sendmail.php");
	$action = $_GET["action"];

	
	if ($action=="change") {
		$pwd    = $_GET["pwd"];
		$stmt = $db->prepare("update users set password= :pwd where trigramme = :login;");
		$stmt->bindValue(':pwd', $pwd, SQLITE3_TEXT);
		$stmt->bindValue(':login', $login, SQLITE3_TEXT);
		$stmt->execute();
		echo "OK";
	} else  {
		$login = strtoupper($_GET["login"]);
		$rest = $db->querySingle("select * from users where trigramme = '$login';",true);
		if ( count($rest) != 0 ) {
			@av_sendmail($db,$login,-1,"Votre mot de passe atelier avarap","Votre mot de passe est :" . $rest["password"]);
			echo "OK";
		} else {
			echo "Utilisateur inconnu" ;
		}
	}

?>
