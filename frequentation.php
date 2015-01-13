<?
	require_once("open_db.php");
	
	$users = [];
	
	$freq = [];
	
	$max = [];
	$mix = [];
	
	$result = $db->query("select trigramme from users order by trigramme;");
	while($res = $result->fetchArray(SQLITE3_ASSOC)){
		$users[] = $res["trigramme"];
	}
	
	$nbusers = count($users);
	
	foreach ( $users as $a) {
		$freq[$a] = [];
		foreach ( $users as $b) {
			$freq[$a][$b]=0;
		}
		unset($freq[$a][$a]);
	}

	$now = time();

	$result = $db->query("select persons from workshops where date < ". $now . ";");
	while($res = $result->fetchArray(SQLITE3_ASSOC)){
		$persons = explode(",",$res["persons"]);
		$l = count($persons);
		for($i=0;$i<$l;$i++) {
			$a = $persons[$i];
			for($j=$i+1;$j<$l;$j++) {
				$b = $persons[$j];
				$freq[$a][$b] ++;
				$freq[$b][$a] ++;
			}
		}
	}


	foreach ( $users as $a) {
		$values = array_values($freq[$a]);
		sort($values);
		$max[$a]= $values[$nbusers-2];
		$min[$a]= $values[1];
	};

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
		<h1>Bilan des fréquentations</h1>
	</div>
	<div role="main" class="ui-content">
		<table border="1">
		   <caption>
			  <p><strong>Rapport de fréquentation</strong></p>
		   </caption>
		<tr> <th></th>
		<?
		foreach ( $users as $a) {
			echo "<th>$a</th>";
		}
		?>
		</tr>
		<?
		foreach ( $users as $a) {
			echo "<tr><th>$a</th>";
			foreach ( $users as $b) {
				$color = "";
				if ( $a == $b )
				{
					$r="X";
				} else {
					$r = $freq[$a][$b];
					if ($r <= $min[$a]) $color="color='red'";
					if ($r >= $max[$a]) $color="color='green'";
				}
				echo "<td  align='center'><font $color >$r</font></td>";
			}
			echo "</tr>";
		}
		?>
		</table>
</div>

</body>
</html>

