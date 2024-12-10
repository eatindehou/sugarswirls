
<?php $niveau="./";?>
<?php include ($niveau . "liaisons/php/config.inc.php");
$strFichierTexte = file_get_contents($niveau . 'liaisons/js/messages-erreur.json');
$jsonMessagesErreur = json_decode($strFichierTexte); ?>


<?php

$strCodeOperation = "";

if(isset($_GET['btn_suppression'])){
	$strCodeOperation = "suppression";
	$id_liste = $_GET['id_liste'];
}

if($strCodeOperation == "suppression"){
	$strRequete = "DELETE FROM listes WHERE id =".$id_liste ;

	$pdosResultat = $objPdo->query($strRequete);
}

$strRequete = "SELECT id, nom, couleur_id FROM listes ORDER BY nom";

	$pdosResultatListe = $objPdo->prepare($strRequete);
	$pdosResultatListe->execute();

	$arrListes=array();
	$ligne=$pdosResultatListe->fetch();
	for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){
		$arrListes[$cpt]['id']=$ligne['id'];
		$arrListes[$cpt]['nom']=$ligne['nom'];
		$arrListes[$cpt]['couleur_id']=$ligne['couleur_id'];

		$strRequete =  'SELECT hexadecimal FROM couleurs
		INNER JOIN listes ON couleurs.id = listes.couleur_id
		WHERE couleurs.id='. $ligne['couleur_id'];

		$pdosSousResultat = $objPdo->prepare($strRequete);
		$pdosSousResultat->execute();

		$ligneStyle = $pdosSousResultat->fetch();
		$strStyles="";

		for($intCptSport=0;$intCptSport<$pdosSousResultat->rowCount();$intCptSport++){
			if($strStyles != ""){
				$strStyles = $strStyles . ", ";    
			}
			$strStyles = $strStyles . $ligneStyle['hexadecimal'];
			$ligneStyle = $pdosSousResultat->fetch();
		}
	   
		$pdosSousResultat->closeCursor();
	   
   $arrListes[$cpt]['hexadecimal'] = $strStyles;

   $ligne=$pdosResultatListe->fetch();

	}

    $pdosResultatListe ->closecursor();


	$strRequeteItems = "SELECT id, nom, echeance FROM items WHERE echeance IS NOT NULL ORDER BY echeance LIMIT 3";

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


	$strRequeteCouleur = "SELECT id FROM couleurs";

	$pdosResultatCouleur = $objPdo->prepare($strRequeteCouleur);
	$pdosResultatCouleur->execute();

	$arrCouleurs=array();
	$ligne=$pdosResultatCouleur->fetchAll();
	for($cpt=0;$cpt<$pdosResultatCouleur->rowCount();$cpt++){
		$arrCouleurs[$cpt]['id']=$ligne[$cpt]['id'];
	}

	$pdosResultatCouleur->closeCursor();


?>


<!DOCTYPE html>
<html lang="fr">
<?php if (isset($_GET["strCodeOperation"])) {
	echo $jsonMessagesErreur->{$_GET["strCodeOperation"]};
} ?>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Vous retrouverez ici toutes vos listes avec les items à échéance. Il est ainsi très facile de les modifier, de les supprimer et d'en ajouter des nouvelles.">
	<meta name="keyword" content="listes, items, supprimer, ajouter, modifier, thèmes, échéance">
	<meta name="author" content="Jade Mayrand">
	<title>Gestion de listes</title>
	<?php include ("liaisons/fragments/headlinks.inc.php");?>
</head>

<body>
	

	<?php include ($niveau . "liaisons/fragments/entete.inc.php");?>

	<main class="main">
		<div id="contenu" class="conteneur">
			<br>
			<aside class="intro">
				<ul class="infos">
					<li>
						<img src="liaisons/svg/cloche.svg" alt="">
						<p><?php echo count($arrItems);?> items à échéance</p>
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
					<form class="recherche" id="form"> 
  						<input type="search" id="query" name="search" placeholder="Rechercher...">
  						<button><img src="liaisons/svg/search.svg" alt=""></button>
					</form>
			</aside>
		

		<h2 class="accueil__titre">Éléments à échéance</h2>
		<?php for($cpt=0;$cpt<count($arrItems);$cpt++){?>
			<ul>
			<li class="item">
				<h3 class="item__titre"><?php echo $arrItems[$cpt]["nom"];?></h3>
				<div class="echeance">
					<p><?php echo $arrItems[$cpt]["echeance"];?></p>
					<a href=""><img src="<?php echo $niveau;?>liaisons/svg/crayon.svg" alt=""></a>
					<a href=""><img src="<?php echo $niveau;?>liaisons/svg/poubelle.svg" alt=""></a>
				</div>
			</li>
			</ul>
		<?php } ?>
		<h2 class="accueil__titre">Mes listes</h2>

		<form action="<?php echo $niveau ?>index.php" method="GET">

		<ul class="listes">
		<?php for($cpt=0;$cpt<count($arrListes);$cpt++){?>
			
			<li class="liste" style="border-left: 5px solid <?php echo "#". $arrListes[$cpt]["hexadecimal"];?>;">
			<a class="liste__lien" href="<?php echo $niveau ?>items/index.php?id_liste=<?php echo $arrListes[$cpt]["id"]; ?>"><h3 class="liste__titre" style="background-color:<?php echo "#". $arrListes[$cpt]["hexadecimal"];?>;"><?php echo $arrListes[$cpt]["nom"];?></h3></a>
			<a href="<?php echo $niveau ?>listes/modifier.php?id_liste=<?php echo $arrListes[$cpt]["id"]; ?>"><img src="<?php echo $niveau;?>liaisons/svg/crayon.svg" alt=""></a><br><br>
			<input type="hidden" name="id_liste" value="<?php echo $arrListes[$cpt]["id"]; ?>">
			<input type="submit" class="annuler" name="btn_suppression" value="Supprimer">
			</li>
		<?php } ?>
		</ul>
		</form>
			

			<form action="<?php echo $niveau ?>listes/ajouter.php" method="GET">
			<input type="submit" class="bouton" name="btn_nouveau" value="Ajouter une liste">
			</form>
			<br><br>
		</div>
	</main>
	
	<?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>

</body>
</html>