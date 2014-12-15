<?
	require_once("open_db.php");
	require_once("sendmail.php");


	function record($id,$db,$login) {
		$res = $db->querySingle("SELECT rowid, * FROM workshops WHERE rowid = $id",true);
		$res["persons"] = $res["persons"] . ",$login";
		$db->exec("UPDATE workshops SET persons = '".$res["persons"]."' WHERE rowid = $id");
		if (!(strpos($res["followers"],$login)===FALSE)) unfollow($id,$db,$login);
		@av_sendmail($db,$res["persons"] .",".$res["followers"] ,$res["rowid"],"$login vient de rejoindre l'atelier","");
	}

	function crpublish($id,$db,$login) {
		$db->exec("update workshops set cr=1 where rowid = $id");
	}
	
	function crunpublish($id,$db,$login) {
		$db->exec("update workshops set cr=0 where rowid = $id");
	}


	function add_comment($id,$db,$login) {
		$resfull = $db->querySingle("SELECT rowid,* FROM workshops WHERE rowid = $id",true);
		$res = $resfull["comments"];
		$add = $login."§".$_GET["text"];
		if ( $res == "" ) {
			$res = $add;
		} else {
			$res = $res."§".$add;
		}
		$stmt = $db->prepare("UPDATE workshops SET comments = :res WHERE rowid = :id");
		$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
		$stmt->bindValue(':res', $res, SQLITE3_TEXT);
		$stmt->execute();
		$comlist = "<hr><p><b>Commentaires</b></p>";
		if ($res != "") {
				$tabcom = explode("§",$res);
				for ($i=0; $i< count($tabcom) ; $i= $i+2) {
					$comlist = $comlist . "<p><b>".$tabcom[$i]." : </b>". $tabcom[$i+1] ."</p><hr>";
				}
			}
		@av_sendmail($db,$resfull["persons"] .",".$resfull["followers"] ,$id,"Nouveau commentaire de la part de $login",$comlist);
	}

	function destroy($id,$db,$login) {
		$res = $db->querySingle("SELECT rowid, * FROM workshops WHERE rowid = $id",true);
		@av_sendmail($db,$res["persons"] .",".$res["followers"] . "," . $login ,$res["rowid"],"Annulation d'un atelier","");
		$res = $db->exec("DELETE FROM workshops WHERE rowid = $id");
	}
	
	function unrecord($id,$db,$login) {
		$res = $db->querySingle("SELECT rowid, * FROM workshops WHERE rowid = $id",true);
		$tab = explode(",",$res["persons"]);
		unset($tab[array_search($login,$tab)]);
		$res["persons"] = join(",",$tab);
		$db->exec("UPDATE workshops SET persons = '".$res["persons"]."' WHERE rowid = $id");
		@av_sendmail($db,$res["persons"] .",".$res["followers"] . "," . $login ,$res["rowid"],"$login vient de se désinscrire de l'atelier","");
	}
	
	function follow($id,$db,$login) {
		$res = $db->querySingle("SELECT followers FROM workshops WHERE rowid = $id");
		if ($res == "") {
			$res = $login;
		} else {
			$res = "$res,$login";
		}
		$db->exec("UPDATE workshops SET followers = '$res' WHERE rowid = $id");
	}

	function unfollow($id,$db,$login) {
		$res = $db->querySingle("SELECT followers FROM workshops WHERE rowid = $id");
		$tab = explode(",",$res);
		unset($tab[array_search($login,$tab)]);
		$res = join(",",$tab);
		$db->exec("UPDATE workshops SET followers = '$res' WHERE rowid = $id");
	}
	
	if ( isset($_GET["action"] )) {
		$_GET["action"]($_GET["id"],$db,$login);
	}
	
	$time = time();


	if ($_GET["filter"] == 'future' ) {
		$query = "select rowid,* from workshops
					where date > $time
					order by date;";
	} 
	
	if ($_GET["filter"] == 'all' ) {
		$query = "select rowid,* from workshops
					order by date;";
	}
	
	if ($_GET["filter"] == 'nocr' ) {
		$query = "select rowid,* from workshops where
					cr = 0 and date < $time
					order by date;";
	}
	
	if ($_GET["filter"] == 'private' ) {
		$query = "select rowid, * from workshops where
				concerned('$login',creator,persons,followers)=1 
				order by date;";
	}
	
	if (isset($_GET["id"])) {
		$id=$_GET["id"];
		$query = "select rowid,* from workshops where rowid=$id ;";
	}
	
	$result = $db->query($query);
	
	while($res = $result->fetchArray(SQLITE3_ASSOC)){ 
		$id = $res["rowid"];
		$creator = $res["creator"];
		$date    = utf8_encode(strftime('%a %d/%m/%Y %H:%M',$res["date"]));
		$isfollower = strpos($res["followers"],$login);
		$isperson   = strpos($res["persons"],$login);
		
		
		if ( $res["cr"] == 1 ) { 
			$cr = "<font color= 'green'>CR disponible </font>";
		} else {
			$cr = "<font color= 'red'>CR non disponible</font>";
		}
		
		$isFuture = $res["date"] > $time;
	?>

	<li>
		<p><b>Date: </b><?=$date?> <b>Organisteur : </b><?=$res["creator"]?> <b>No: </b><?=$id?></p>
		<p><b>Lieu</b> : <?=$res["location"]?>  </p>
		<p><b>Inscrits : </b> <?=$res["persons"]?>  <b>Suiveurs : </b> <?=$res["followers"]?></p>
		<p><b>Sujet</b> : <?=$res["topic"]?></p>
		<p><?=$cr?></p>
		<div class="commentblock" id="c_<?=$id?>">
		<hr>
		<p><b>Commentaires:</b></p>
		<?
			$com = $db->querySingle("SELECT comments FROM workshops WHERE rowid=$id");
			if ($com != "") {
				$tabcom = explode("§",$com);
				for ($i=0; $i< count($tabcom) ; $i= $i+2) {
					echo "<p><b>".$tabcom[$i]." : </b>". $tabcom[$i+1] ."</p><hr>";
				}
			}
		if (!( $isfollower===FALSE && $isperson===FALSE)) {
		?>
		<input id="add_<?=$id?>"" type="text" />
		<button onclick="add_comment(<?=$id?>);">Envoyer</button>
		<?
		}
		?>
		</div>
		
		<button class="ui-btn ui-btn-inline ui-mini" onclick="commentaire(<?=$id?>);">Commentaire</button>
		<?
		ob_start();
		if ( $login == "ADMIN") { ?>
				<option value="modify">Modifier l'atelier</option>
				<option value="destroy">Détruire l'atelier</option>
			<?if ( $res["cr"] == 0 )  {?>
					<option value="crpublish">Publier le CR</option>
			<?} else { ?>
					<option value="crunpublish">Retirer le CR</option>
			<?}?>
		<?
		} else {
			if ( $isfollower===FALSE && $isperson===FALSE && $isFuture ) {
			?>
				<option value="follow">Suivre l'atelier</option>
			<? }?>
			<?
			if ( !( $isfollower===FALSE ) && $isperson===FALSE && $isFuture) {
			?>
				<option value="unfollow">Ne plus suivre l'atelier</option>
			<? } ?>
			<? if ( (!($isperson===FALSE)) && ( $res["cr"] == 0 ) && !$isFuture ) {
			?>
				<option value="crpublish">Publier le CR</option>
			<? } ?>
			<?
				if ($login == $creator && $isFuture ) {
			?>
				<option value="modify">Modifier l'atelier</option>
				<option value="destroy">Détruire l'atelier</option>
			<? } ?>
			<?
			if ( $isFuture )
				if ( $isperson === FALSE  ) {
			?>
				<option value="record">Participer à l'atelier</option>

			<?
			} else { ?>
				<option value="unrecord">Se désinscrire</option>
			<? }
		}
		$options = ob_get_clean();
		if (trim($options) != "" ) {
		 ?> 
			<select id="select_<?=$id?>" onchange="action(<?=$id?>);">
			<option value="aaa">Choisir</option>
				<?= $options ?>
			</select>
		<?}?>
    </li>

<?
}
?>
