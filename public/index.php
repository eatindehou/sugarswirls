
<?php $niveau="./";?>
<?php include ($niveau . "liaisons/php/config.inc.php");?>


<?php

$strCodeOperation = "";

if(isset($_GET['btn_suppression'])){
	$arrIDListes=$_GET['id_listes'];
	$strCodeOperation = "suppression";
}
else{
	$arrIDListes;
}

if($strCodeOperation == "suppression"){
	$strRequete = "DELETE FROM listes WHERE id IN (". implode(',' , $arrIDListes).")";

	$pdosResultat = $objPdo->query($strRequete);
}

	$strRequeteListe = "SELECT id, nom, couleur_id FROM listes";

	$pdosResultatListe = $objPdo->prepare($strRequeteListe);
	$pdosResultatListe->execute();

	$arrListes=array();
	$ligne=$pdosResultatListe->fetchAll();
	for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){
		$arrListes[$cpt]['id']=$ligne[$cpt]['id'];
		$arrListes[$cpt]['nom']=$ligne[$cpt]['nom'];
		$arrListes[$cpt]['couleur_id']=$ligne[$cpt]['couleur_id'];
	}

	$pdosResultatListe->closeCursor();


	$strRequeteCouleur = "SELECT id, nom_fr, rgb FROM couleurs";

	$pdosResultatListe = $objPdo->prepare($strRequeteCouleur);
	$pdosResultatListe->execute();

	$arrCouleurs=array();
	$ligne=$pdosResultatListe->fetchAll();
	for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){
		$arrCouleurs[$cpt]['id']=$ligne[$cpt]['id'];
		$arrCouleurs[$cpt]['nom_fr']=$ligne[$cpt]['nom_fr'];
		$arrCouleurs[$cpt]['rgb']=$ligne[$cpt]['rgb'];
	}

	$pdosResultatListe->closeCursor();


	$strRequeteItems = "SELECT id, nom, echeance FROM items ORDER BY echeance";

	$pdosResultatListe = $objPdo->prepare($strRequeteItems);
	$pdosResultatListe->execute();

	$arrItems=array();
	$ligne=$pdosResultatListe->fetchAll();
	for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){
		$arrItems[$cpt]['id']=$ligne[$cpt]['id'];
		$arrItems[$cpt]['nom']=$ligne[$cpt]['nom'];
		$arrItems[$cpt]['echeance']=$ligne[$cpt]['echeance'];
	}

	$pdosResultatListe->closeCursor();


?>


<!DOCTYPE html>
<html lang="fr">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keyword" content="">
	<meta name="author" content="">
	<meta charset="utf-8">
	<title>Un beau titre ici!</title>
	<?php include ($niveau . "liaisons/fragments/headlinks.inc.html");?>
</head>
<style>
	.item,
	.liste{
		background-color: beige;
	}
</style>
<body>

	<?php include ($niveau . "liaisons/fragments/entete.inc.php");?>

	<main class="main">
		<div id="contenu" class="conteneur">
			<br>
			<aside class="intro">
				<ul>
					<li>
						<img src="liaisons/svg/cloche.svg" alt="">
						<p>3 items à échéance</p>
					</li>
					<li>
						<img src="liaisons/svg/liste.svg" alt="">
						<p><?php echo count($arrListes);?> listes</p>
					</li>
					<li>
						<img src="liaisons/svg/drapeau.svg" alt="">
						<p><?php echo count($arrCouleurs);?> thèmes</p>
					</li>
				</ul>
				
				<label for="filtre">Filtrer par:</label>
					<select name="filtre" id="filtre">
					<option value="">Choisir un filtre</option>
					<option value="date">Date</option>
					<option value="theme">Thème</option>
					</select>
					<form id="form"> 
  						<input type="search" id="query" name="search" placeholder="Rechercher...">
  						<button>Rechercher</button>
					</form>
			</aside>
		

		<h2 class="accueil__titre">Éléments à échéance</h2>
		<?php for($cpt=0;$cpt<count($arrItems);$cpt++){?>
		<?php if($arrItems[$cpt]["echeance"] != NULL){?>
			<li class="item">
				<h3 class="item__titre"><?php echo $arrItems[$cpt]["nom"];?></h3>
				<p><?php echo $arrItems[$cpt]["echeance"];?></p>
				<a href=""><img src="<?php echo $niveau;?>liaisons/svg/crayon.svg" alt=""></a>
				<a href="">Supprimer</a>
			</li>
		<?php } ?>
		<?php } ?>
		<h2 class="accueil__titre">Mes listes</h2>

		<form action="<?php echo $niveau ?>index.php" method="GET">

		<ul class="listes">

		<?php for($cpt=0;$cpt<count($arrListes);$cpt++){?>
			<li class="liste">
			<h3><?php echo $arrListes[$cpt]["nom"];?></h3>
			
			
			<input type="checkbox" name = "id_listes[]" value ="<?php echo $arrListes[$cpt]["id"]; ?>">

			<a href="<?php echo $niveau ?>listes/modifier.php?id_liste=<?php echo $arrListes[$cpt]["id"]; ?>">Modifier</a><br><br>
			</li>
		<?php } ?>
		</ul>
		<input type="submit" name="btn_suppression" value="Supprimer">
		</form>
			

			<form action="<?php echo $niveau ?>listes/ajouter.php" method="GET">
			<input type="submit" name="btn_nouveau" value="Nouveau">
		</form>
		</div>

	
	<?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>

</body>
</html>