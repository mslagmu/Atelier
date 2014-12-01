<?

	$date = $_GET["date"];
	$time = $_GET["time"];
	$loc = $_GET["loc"];
	$topic = $_GET["topic"];

	$action = $_GET["action"];

	
	if ($date == "" ) {
		echo "La date doit être renseignée";
		exit;
	}
	if ($time == "" ) {
		echo "L'heure doit être renseignée";
		exit;
	}
	if ($loc == "" ) {
		echo "Le lieu doit être renseigné";
		exit;
	}
	if ($topic == "" ) {
		echo "Le sujet doit être renseigné";
		exit;
	}
	
	require_once("open_db.php");
	require_once("sendmail.php");
	

	
	$dtime = DateTime::createFromFormat("d/m/Y h:i A", "$date $time");
	$timestamp = $dtime->getTimestamp();
	
	if ($action == "create") {
		$comment = $_GET["comment"];
		$persons = $_GET["persons"];
			if  ( $persons == "" ) {
				$persons = $login ;
			} else {
				$persons = $login .",". $persons ;
			}
		$stmt = $db->prepare("insert into workshops(creator,date,location,topic,persons,comments) values( :login, :date ,:loc,:topic,:persons,:comment);");
		$stmt->bindValue(':date', $timestamp, SQLITE3_INTEGER);
		$stmt->bindValue(':loc', $loc, SQLITE3_TEXT);
		$stmt->bindValue(':topic', $topic, SQLITE3_TEXT);
		$stmt->bindValue(':login', $login, SQLITE3_TEXT);
		$stmt->bindValue(':persons', $persons, SQLITE3_TEXT);
		$stmt->bindValue(':comment', $login."§".$comment, SQLITE3_TEXT);
		$stmt->execute();
		
		$id = $db->lastInsertRowID ();
		
		$result = $db->query("select * from users;");
		$to="";
		
		while($res = $result->fetchArray(SQLITE3_ASSOC)){
			$to = $to.$res["trigramme"].",";
		}
		
		@av_sendmail($db,$to,$id,"Creation d'un nouvel atelier","");
		echo "OK";
		
	} else {
		
		$id= $_GET["id"];
		$res = $db->querySingle("SELECT rowid, * FROM workshops WHERE rowid = $id",true);
		$stmt = $db->prepare("UPDATE workshops SET date= :date, location= :loc, topic= :topic WHERE rowid = :id");
		$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
		$stmt->bindValue(':date', $timestamp, SQLITE3_INTEGER);
		$stmt->bindValue(':loc', $loc, SQLITE3_TEXT);
		$stmt->bindValue(':topic', $topic, SQLITE3_TEXT);
		$stmt->execute();
		$to = $res["persons"] ."," . $res["followers"];
		@av_sendmail($db,$to,$id,"Modification d'un nouvel atelier","");
		echo "OK";
	}
?>
