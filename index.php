<?
	require_once("open_db.php");
	if (!isset($_SESSION["login"])) {
		$_SESSION["URI"]=$_SERVER["REQUEST_URI"];
		header('Location: /atelier/connection.php');
		exit;
	}
	
	if ( isset($_SESSION["URI"] ) ) {
		$uri = $_SESSION["URI"];
		unset($_SESSION["URI"]);
		header("Location: " . $uri);
		exit;
	}
?>
<!DOCTYPE html> 
<html>
<head>
	<title>Page Title</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	<link rel="stylesheet" href="jqm/jquery.mobile-1.4.5.min.css" />
	<link rel="stylesheet" href="jqm-datebox-1.4.5.min.css" />
	<script src="jquery-1.11.1.min.js"></script>
	<script src="jqm/jquery.mobile-1.4.5.min.js"></script>
	<script src="date.js"></script>
	<script src="main.js"></script>
	<script >
var login = ""

</script>
</head>

<body>

<!-- Start of first page -->



<div data-role="page" id="home">
	<div data-role="header">
		<h1>Menu : <?=$login  ?></span></h1>
	</div>
	<div role="main" class="ui-content">	
		<ul data-role="listview">
		    <li><a href="#list" id="menu_atelier">Futurs Ateliers</a></li>
		    <li><a href="#list" id="menu_toutatelier">Historique des Ateliers</a></li>
		    <li><a href="#add"  id="menu_add">Créer un atelier</a></li>
		    <li><a href="#list" id="perso_atelier">Ateliers qui vous concernent</a></li>
		    <li><a href="#list" id="no_cr">Ateliers passés sans CR</a></li>
		    <li><a href="#profil"  id="menu_add">Profil</a></li>
		    <li><a href="reporting.php">Bilan du groupe</a></li>
		    <li><a href="#" id="logout">Déconnexion</a></li>
		</ul>
	</div>
	<div data-role="footer">
		<h4>home</h4>
	</div>
</div><!-- /page -->

<!-- Start list-->
<div data-role="page" id="list">

	<div data-role="header">
		<h1>Ateliers : <?=$login  ?></span></h1>
		<a href="/atelier/#home">retour</a> <a href="#add">Nouvel Atelier</a>
	</div><!-- /header -->

	<div role="main" class="ui-content" id="tablist">
		
		<ul data-role="listview" data-inset="true" id="atListView">
		</ul>
	</div><!-- /content -->
	<div data-role="footer">
		<a href="/atelier/#home">retour</a>
	</div>
</div><!-- /page -->


<div data-role="page" id="add">

	<div data-role="header">
		<h1>Ajouter un atelier : <?=$login  ?> </h1>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<div id="newMessage"></div>
			<label for="newDate">Date de l'atelier</label>
			<input name="date" id="newDate" type="text" data-role="datebox" data-options='{"mode":"calbox"}' required />
			<label for="newTime">Heure de l'atelier</label>
			<input name="time" id="newTime" type="text" data-role="datebox" data-options='{"mode":"timebox"}' required />
			<label for="newLcation">Lieu de l'atelier</label>
			<input name="location" id="newLoc" type="text"  required />
			<label for="newTopic">Sujet de l'atelier</label>
			<input name="topic" id="newTopic" type="text"  required />
			<label for="newTopic">Commentaire:</label>
			<input name="topic" id="newComment" type="text" />
    <fieldset data-role="controlgroup" >
        <legend>Sélectionnez des participants:</legend>
<?
		$result = $db->query("select * from users;");
		while($res = $result->fetchArray(SQLITE3_ASSOC)){
			$l = $res["trigramme"];
			if ( $l == $login ) continue;
			$idb = "cb_" . $l;
?>
		<input class="person" name="<?=$l ?>" id="<?=$idb ?>" type="checkbox">
        <label for="<?=$idb ?>"><?=$l?></label>
<? } ?>
	</fieldset>
			<button id="newbut">Envoyer</button>
	</div><!-- /content -->
	<div data-role="footer">
		<a href="#home">retour</a>
	</div>
</div><!-- /page -->
<div data-role="page" id="modify">
	<div data-role="header">
		<h1>Modifier l'atelier <span id="mid"> : <?=$login  ?></span></h1>
	</div><!-- /header -->
	<div role="main" class="ui-content">
		<div id="mMessage"></div>
		<form>
			<label for="mDate">Date de l'atelier</label>
			<input name="date" id="mDate" type="text" data-role="datebox" data-options='{"mode":"calbox"}' required />
			<label for="mTime">Heure de l'atelier</label>
			<input name="time" id="mTime" type="text" data-role="datebox" data-options='{"mode":"timebox"}' required />
			<label for="mLcation">Lieu de l'atelier</label>
			<input name="location" id="mLoc" type="text" required />
			<label for="mTopic">Sujet de l'atelier</label>
			<input name="topic" id="mTopic" type="text" required />
			<button id="modifybut">Envoyer</button>
		</form>
	</div><!-- /content -->
	<div data-role="footer">
		<a href="#home">retour</a>
	</div>
</div><!-- /page -->
<div data-role="page" id="logout">
	<div data-role="header">
		<h1>Déconnexion</span></h1>
	</div><!-- /header -->
	<div role="main" class="ui-content">
	Vous êtes déconnecté.
	</div><!-- /content -->
	<div data-role="footer">
		<a href="#login">Reconnexion</a>
	</div>
</div><!-- /page -->
<div data-role="page" id="profil">
	<div data-role="header">
		<h1>Changer de mot de passe : <?=$login  ?></h1>
	</div><!-- /header -->
	<div role="main" class="ui-content">
			<div id="npMessage"></div>
			<label for="mDate">Nouveau mot de passe</label>
			<input  id="npwd" type="text" />
			<button id="npassword">Envoyer</button>

	</div><!-- /content -->
	<div data-role="footer">
		<a href="#home">Retour</a>
	</div>
</div><!-- /page -->
</body>
</html>
