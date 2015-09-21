<?php
	session_start();
	
	if ( ! isset($_SESSION["database_name"]) ) {
		if ( isset ( $_GET["groupe"] ) ) {
			$_SESSION["database_name"]= $_GET["groupe"]. ".db";
		} else {
			echo "votre groupe n'est pas identifié";
			session_destroy();
			exit;
		}
	} 

	if ( !file_exists($_SESSION["database_name"] )) {
		echo "Groupe inconnu";
		session_destroy();
		exit;
	}

	setlocale(LC_TIME, 'fr','fr-FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');

	if (isset ( $_SESSION["login"]) ) {
		$login = $_SESSION["login"];
		$name = $_SESSION["name"];
	}
	$db=new SQLite3($_SESSION["database_name"]);

	function concerned($login,$creator,$persons,$followers) {
		if ( $login == $creator ) return 1;
		if ( !( strpos($followers,$login) === FALSE ) ) return 1;
		if ( !( strpos($persons,$login) === FALSE ) ) return 1;
		return 0;
	}
	
	function recorded($login,$persons) {
		if ( !( strpos($persons,$login) === FALSE ) ) return 1;
		return 0;
	}
	
	function month($time) {
		return utf8_encode(strftime('%B %Y',$time));
	}
	
	function week ($time) {
		$t2 = $time - $_SESSION["date_debut"]; //timestamp du 2 octobre 2014
		return floor($t2/604800) ; //Correspond au nombre de seconde pour 7 jours
	}

	$db->createFunction('concerned', 'concerned', 4);
	$db->createFunction('recorded', 'recorded', 2);
	$db->createFunction('month', 'month', 1);
	$db->createFunction('week', 'week', 1);
?>
