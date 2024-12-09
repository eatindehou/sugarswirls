
<?php $niveau="../";?>
<?php include ($niveau . "liaisons/php/config.inc.php");?>

<?php 

$strCodeOperation = "modifier";

if(isset($_GET['id_liste'])){
    $id_liste=$_GET['id_liste'];
}
else{
    $id_liste = 0;
}

if(isset($_GET['btn_modifier'])){
    if(isset($_GET['couleur_id'])){
    $arrListes['nom']=$_GET['nom'];
    $arrListes['id']=$_GET['id_liste'];
    $arrListes['couleur_id']=$_GET['couleur_id'];
    
    
    $strRequete = "UPDATE listes SET ".
                "nom='".$arrListes['nom']."', ".
                "couleur_id='".$arrListes['couleur_id']. 
                "' WHERE id=".$arrListes['id'];

                $pdosResultat = $objPdo->query($strRequete);

    header("Location:".$niveau."index.php");
    }
    else{
            $strMessage = "Erreur! Veuillez remplir tous les champs.";
    }
}
else{
    $strMessage = ""; 

    $strRequete = "SELECT id, nom, couleur_id FROM listes WHERE id=".$id_liste. " ORDER BY nom ";

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

		//Initialisation de l'objet PDOStatement et exécution de la requête
		$pdosSousResultat = $objPdo->prepare($strRequete);
		$pdosSousResultat->execute();

		$ligneStyle = $pdosSousResultat->fetch();
		$strStyles="";
		//Extraction des noms de Sports de la sous requête
		for($intCptSport=0;$intCptSport<$pdosSousResultat->rowCount();$intCptSport++){
			if($strStyles != ""){
				$strStyles = $strStyles . ", ";    //ajout d'une virgule lorsque nécessaire
			}
			$strStyles = $strStyles . $ligneStyle['hexadecimal'];
			$ligneStyle = $pdosSousResultat->fetch();
		}
	   
		//On libère la sous requête
		$pdosSousResultat->closeCursor();
	   
   //ajout d'un propriété pour afficher les sports
   $arrListes[$cpt]['hexadecimal'] = $strStyles;

   //On passe à l'autre participant
   $ligne=$pdosResultatListe->fetch();

	}

    $pdosResultatListe ->closecursor();

}



function ecrireChecked($valeurRadio, $nomRadio){
    $strCocher="";
    global $arrListes;
    if($valeurRadio == $arrListes[0][$nomRadio]){
        $strCocher = 'checked="checked"';
    }
    return $strCocher;
}

$arrCouleur=array();

$strRequete = "SELECT id, nom_fr, hexadecimal FROM couleurs ORDER BY nom_fr";

	$pdosResultatListe = $objPdo->prepare($strRequete);
	$pdosResultatListe->execute();

	$arrCouleur=array();
	$ligne=$pdosResultatListe->fetch();
	for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){
		$arrCouleur[$cpt]['id']=$ligne['id'];
		$arrCouleur[$cpt]['nom_fr']=$ligne['nom_fr'];
		$arrCouleur[$cpt]['hexadecimal']=$ligne['hexadecimal'];
			
        $ligne=$pdosResultatListe->fetch();

	}

    $pdosResultatListe ->closecursor();

?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Modification</title>
	<?php include ($niveau . "liaisons/fragments/headlinks.inc.php");?>
</head>
<body>
<?php include ($niveau . "liaisons/fragments/entete.inc.php");?>
<main class="main">
    <br><br>
<form action="<?php echo $niveau ?>listes/modifier.php" class="bloc" method="GET">
	
        <p class="error"><?php echo $strMessage; ?></p>
        <h2><label for="nom">Nom de la liste:</label></h2>
        <input type="text" size="50" id="nom" name="nom" value="<?php echo $arrListes[0]['nom']; ?>">
        <input type="hidden" id="liste" name="id_liste" value="<?php echo $arrListes[0]['id']; ?>">     
    <br>
        <h2>Choisir une couleur:</h2>
    <section class="couleurs">
        
    <?php  for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){ ?>
		<label class="couleurs__nom" for="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>"><?php echo $arrCouleur[$cpt]['nom_fr']; ?></label>
        <br>
		<input type="radio" id="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>" name="couleur_id" value="<?php echo $arrCouleur[$cpt]['id']; ?>" 
        <?php if(!isset($_GET['btn_modifier']))
        {
            echo ecrireChecked($arrCouleur[$cpt]['hexadecimal'], 'hexadecimal');} ?>><br>
        <?php  } ?>
	</section>
	
	        <br>
        	<input type="submit" value="Enregistrer" class="bouton" name="btn_modifier">
            <a class="bouton__annuler" href="<?php echo $niveau ?>index.php">Annuler <img src="<?php echo $niveau;?>liaisons/svg/cancel.svg" alt=""></a><br>
	</form>
    <br><br>
    </main>
    <?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>
</body>