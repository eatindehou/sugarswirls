
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
    $arrListes['nom']=$_GET['nom'];
    $arrListes['id']=$_GET['id'];
    $arrListes['couleur_id']=$_GET['couleur_id'];
    
    
    $strRequete = "UPDATE listes SET ".
                "nom='".$arrListes['nom']."', ".
                "couleur_id='".$arrListes['couleur_id']. 
                "' WHERE id=".$arrListes['id'];
    echo $strRequete;
                $pdosResultat = $objPdo->query($strRequete);
                $strCodeErreur = $objPdo->errorCode();
    
}
else{
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
    <meta charset="UTF-8">
    <title>Modification</title>
	<?php include ($niveau . "liaisons/fragments/headlinks.inc.html");?>
</head>
<body>
<?php include ($niveau . "liaisons/fragments/entete.inc.php");?>
<main class="main">
    <br><br>
<form action="<?php echo $niveau ?>index.php" class="bloc" method="GET">
	
        <input type="hidden" name="id" value="<?php echo $id_liste; ?>">
  
        <input type="hidden" name="couleur_id" value="<?php echo $arrListes[0]['couleur_id']; ?>">
 
        <label for="nom">Nom de la liste:</label>
        <input type="text" id="nom" name="nom" value="<?php echo $arrListes[0]['nom']; ?>">
  

    <section class="couleurs"><?php  for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){ ?>
		<label for="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>"><?php echo $arrCouleur[$cpt]['nom_fr']; ?></label>
		<input type="radio" id="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>" name="hexadecimal" value="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>" <?php if(!isset($_GET['btn_modifier'])){echo ecrireChecked($arrCouleur[$cpt]['hexadecimal'], 'hexadecimal');} ?>><br>
		
        <?php  } ?>
	</section>
	
	<br>
        	<input type="submit" value="Enregistrer" class="bouton" name="btn_modifier">
            <a href="<?php echo $niveau ?>index.php">Annuler</a><br>
	</form>
    <br><br>
    </main>
    <?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>
</body>