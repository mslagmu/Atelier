<?php
require_once("open_db.php");
?>
<!DOCTYPE html> 
<html>
<head>
	<title>Reporting</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	<link rel="stylesheet" href="jqm/jquery.mobile-1.4.5.min.css" />
	<link rel="stylesheet" href="jqm-datebox-1.4.5.min.css" />
	<script src="jquery-1.11.1.min.js"></script>
	<script src="jqm/jquery.mobile-1.4.5.min.js"></script>
</head>

<body>
<div data-role="page" id="login">
	<div data-role="header">
		<h1>Bilan du groupe</h1>
	</div>
	<div role="main" class="ui-content">	
		<table border="1">
		   <caption>
			  <p><strong>Nombre d'ateliers faits par personne</strong></p>
		   </caption>
		<tr>
			  <th>Trigramme</th>
			  <th>Nombre</th>
		   </tr>
		<?
		$now = time();
		$result = $db->query("
			select u.trigramme, count(w.rowid) as c from workshops w ,users u
			where recorded(u.trigramme,w.persons) = 1 and date < ". $now . "
			group by u.trigramme ;
		");

		while($res = $result->fetchArray(SQLITE3_ASSOC)){ 
			echo "<tr><td>".$res["trigramme"] . " </td><td align='right'> " . $res["c"] . "</td></tr>" ;
		}
		?>
		</table>

		<table border="1">
		   <caption>
			  <p><strong>Nombre d'ateliers pour chaque mois</strong></p>
		   </caption>
		<tr>
			  <th>Mois</th>
			  <th>Nombre</th>
		   </tr>
		<?
		$result = $db->query("
			select month(date) m, count(1) c from workshops where date < ". $now . "
			group by m;
		");

		while($res = $result->fetchArray(SQLITE3_ASSOC)){ 
			echo "<tr><td>".$res["m"] . " </td><td align='right'> " . $res["c"] . "</td></tr>" ;
		}
		?>
		</table>


		<table border="1">
		   <caption>
			  <p><strong>Nombre d'ateliers pour une semaine</strong></p>
		   </caption>
		<tr>
			  <th>Semaines</th>
			  <th>Nombre</th>
		   </tr>
		<?
		$result = $db->query("
			select week(date) w, count(1) c from workshops where date < ". $now . "
			group by w;
		");

		while($res = $result->fetchArray(SQLITE3_ASSOC)){ 
			echo "<tr><td>Semaine : ".$res["w"] . " </td><td align='right'> " . $res["c"] . "</td></tr>" ;
		}
		?>
		</table>
</div>

</body>
</html>
