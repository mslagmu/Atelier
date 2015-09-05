<html >
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<?
	function getGet($name,$default="") {
		if ( isset($_GET[$name])) {
			return $_GET[$name]; 
		} else{
			return $default;
		}
	}
	
	$status=true;
	$message="";
	$numero = getGet("numero");
	$date   = getGet("date");
	$list   = getGet("list");
	
	if ($numero == "" ) {
		$message = "<p>Le numéro n'est pas renseigné</p>";
		$status=false;
	}
	
	if ($date == "" ) {
		$message = $message . "<p>La date n'est pas renseignée</p>";
		$status=false;
	}
	
	if ($list == "" ) {
		$message = $message . "<p>La liste n'est pas renseignée</p>";
		$status=false;
	}
	
?>
<div>
<?=$message?>
</div>
<form>
	<p>Numéro du groupe        :  <input type="text" name="numero" /> </p>
	<p>Date de début du groupe :  <input type="text" name="date"   /> </p>
	<p>Liste du goupe</p>
	<textarea name="list" cols="150" rows="25">
1588362;M.;COUETOUX DU TERTRE;Tristan;tristancouetoux@gmail.com;21/01/2015;1009
1588360;Mme;ANTAO;Christine;christineantao@hotmail.com;20/01/2015;1009
1744809;M.;D'ESTAINTOT;Jacques;jacquesdestaintot@gmail.com;18/05/2015;1047
1703974;M.;PONCET;Antoine;antponcet@hotmail.com;25/03/2015;1035
1703973;Mme;DESPLATZ;Rozenn;rozenndesplatz@yahoo.fr;26/03/2015;1035
1703981;Mme;BOUKRIS;Elisabeth;Boukriselisabeth@gmail.com;26/03/2015;1035
1588361;M.;GASNIER;Jean-Christophe;jeanchristophegasnier@gmail.com;21/01/2015;1009
1703972;Mme;DE LA LAURENCIE;Florence;f-v-delalaurencie@orange.fr;26/03/2015;1035
1703977;Mme;LUMIERE;Line;l_lumiere@yahoo.fr;26/03/2015;1035
1735704;M.;HENNEGUELLE;Alexandre;hennegue@hotmail.com;08/04/2015;1040
1703975;M.;GAYET;Henry;henry.gayet@me.com;26/03/2015;1035
1551132;Mme;DELMAS;Nathalie;nthl.delmas@gmail.com;28/10/2014;1003
1584742;Mme;VENDRAND;Nathalie;nathalie@nvconseil.com;27/01/2015;1015
1522075;Mme;MORIN;Laurence;laurence-morin@sfr.fr;04/09/2014;978
	</textarea>
	<p><input type="submit" name="go" value="sauver"></p>
</form>
</body>
</html>
