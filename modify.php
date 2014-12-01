<?
	require_once("open_db.php");
	$id = $_GET["id"];
	$action = $_GET["action"];
	
	if ( $action == "data" ) {
		$res = $db->querySingle("SELECT rowid, * FROM workshops WHERE rowid = $id",true);
		$timestamp = $res["date"];
		$res["date"] = date("d/m/Y",$timestamp);
		$res["time"] = date("h:i A",$timestamp);
		echo json_encode($res);
	} else {
	
	}
?>
