<?php
require_once "Mail.php";
//require_once("open_db.php");


function av_sendmail($db,$receivers,$id,$header,$footer){
	$result = $db->query("select trigramme,email from users");
	$to ="";
	while($res = $result->fetchArray(SQLITE3_ASSOC)){
		if (!(strpos($receivers,$res["trigramme"])===FALSE)) {
			$to = $to.$res["email"].",";
		}
	}
	
	ob_start();

	$res = $db->querySingle("SELECT rowid, * FROM workshops WHERE rowid = $id",true);
	$date    = strftime('%A %d %B %Y %H:%M',$res["date"]);
	

?>
<html>
<body>
	<?
		if ($id != -1 ) {
	?>
		<h2><?=$header ?></h2>

		<hr>
		<p><b>Date : </b><?=$date?></p> 
		<p><b>Organisteur : </b><?=$res["creator"]?></p>
		<p><b>Emplacement : </b> <?=$res["location"]?></b></p>
		<p><b>Inscrits : </b> <?=$res["persons"]?></p>
		<p><b>Suiveurs : </b> <?=$res["followers"]?></p>
		<p><b>Sujet</b> : <?=$res["topic"]?></p>
		<p><a href="<? echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"]."/atelier/?atelier=". $id ?>" >Lien sur l'atelier</a></p>
		<hr>
<? }
?>
		<div><?=$footer ?></div>
</body>
</html>
<?
	 $body = ob_get_clean();
	 $from = "Atelier AVARAP <michel.slagmulder.pro@gmail.com>";
	 if ($id != -1 ) {
		$subject = "[atelier $id]: " . $header;
	 } else {
		$subject = $header;
	 }
	 $host = "ssl://smtp.gmail.com";
	 $port = "465";
	 $username = "michel.slagmulder.pro";
	 $password = "pxqalgepigphsnnv";


	 $headers = array ('From' => $from,
	   'To' => $to,
	   'Subject' => $subject,
	   'content-type' => "text/html; charset=\"iso-8859-1\"");
	 $smtp = Mail::factory('smtp',
	   array ('host' => $host,
		 'port' => $port,
		 'auth' => true,
		 'username' => $username,
		 'password' => $password));
	 
	 $mail = $smtp->send($to, $headers, $body);
	 
	 if (PEAR::isError($mail)) {
	   return("<p>" . $mail->getMessage() . "</p>");
	  } else {
	   return "OK";
	  }
}

//echo @av_sendmail($db,"MSL,VFE",1,"Creation d'un nouvel atelier","");

?>
