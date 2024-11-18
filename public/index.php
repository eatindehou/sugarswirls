
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

	$strRequete = "SELECT id, nom, couleur_id FROM listes";

	$pdosResultat = $objPdo->prepare($strRequete);
	$pdosResultat->execute();

	$arrListes=array();
	$ligne=$pdosResultat->fetch();
	for($cpt=0;$cpt<$pdosResultat->rowCount();$cpt++){
		$arrListes[$cpt]['id']=$ligne['id'];
		$arrListes[$cpt]['nom']=$ligne['nom'];
		$arrListes[$cpt]['couleur_id']=$ligne['couleur_id'];
	}

	$pdosResultat->closeCursor();

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

<body>

	<?php include ($niveau . "liaisons/fragments/entete.inc.php");?>

	<main>
		<div id="contenu" class="conteneur">
		<h2>Éléments à échéance</h2>

			<h2>Mes listes</h2>

			<form action="<?php echo $niveau ?>index.php" method="GET">
			<section class="liste">
			<?php
			var_dump($arrListes);
			for($cpt=0;$cpt<count($arrListes);$cpt++){?>

				<?php echo $arrListes[$cpt]["nom"];?>
				
				<!-- <input type="checkbox" name = "id_listes[]" value ="<?php echo $arrListes[$cpt]["id"]; ?>"> -->

				<!-- <a href="<?php echo $niveau ?>listes/modifier.php?id_liste=<?php echo $arrListes[$cpt]["id"]; ?>">Modifier</a><br><br>
			<?php } ?> -->

			<input type="submit" name="btn_suppression" value="Supprimer">
			</form>
				
			</section>

			<form action="<?php echo $niveau ?>listes/ajouter.php" method="GET">
			<input type="submit" name="btn_nouveau" value="Nouveau">
		</form>
		</div>
	




		
	

		


		

   
        
     <!-- <a href="#" class="hyperlien">lien test!</a>
	</main>
	
	<aside>
            <h3>Barre latérale</h3>
            <p>Lorem ipsum dolor nunc aut nunquam sit amet, consectetur adipiscing elit. Vivamus at est eros, vel fringilla urna. Pellentesque odio rhoncus</p>
	</aside>
	 -->
	
	<?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>

</body>
</html>