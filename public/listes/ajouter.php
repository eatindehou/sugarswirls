Ajouter un liste!
<?php $niveau="../";?>
<a href="<?php echo $niveau;?>index.php">Retour</a>
<?php include ($niveau . "liaisons/php/config.inc.php");?>

<?php

if(isset($_GET['btn_ajouter'])){
	$strCodeOperation = "ajouter";
}
else{
    $strCodeOperation = "";
}

if($strCodeOperation == "ajouter"){
    $arrListes=array();

    $arrListes['id_liste']= "0";
		$arrListes['nom']= "";
		$arrListes['id']= "";
		$arrListes['couleur_id']= "";

    $strRequeteAjout = "INSERT INTO listes ".
    "(nom, couleur_id)".
    " VALUES ('". 
    $arrListes['nom']."','".
    $arrListes['couleur_id'].")";

    // $pdosResultat = $objPdo->query($strRequeteAjout);
    // $pdosResultat->closeCursor();
}

$arrListes=array();

$arrListes['id_liste']= "0";
		$arrListes['nom']= "";
		$arrListes['id']= "";
		$arrListes['couleur_id']= "";


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


            function ecrireChecked($valeurRadio, $nomRadio){
                $strCocher="";
                global $arrListes;
                if($valeurRadio == $arrListes[0][$nomRadio]){
                    $strCocher = 'checked="checked"';
                }
                return $strCocher;
            }

?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Modification</title>
	
</head>
<body>
<form action="<?php echo $niveau ?>listes/ajouter.php" method="GET">
    <div>
        <input type="hidden" name="couleur_id" value="<?php echo $arrListes['couleur_id']; ?>">
    </div>
    <div>
        <label for="nom">Nom de la liste:</label>
        <input type="text" id="nom" name="nom" value="<?php echo $arrListes['nom']; ?>">
    </div>

    <div><?php  for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){ ?>
		<label for="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>"><?php echo $arrCouleur[$cpt]['nom_fr']; ?></label>
		<input type="radio" id="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>" name="hexadecimal" value="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>"><br>
        <?php  } ?>
	</div>
	<br>
    <div>
        	<input type="submit" value="Ajouter" name="btn_ajouter">
            <a href="<?php echo $niveau ?>index.php">Annuler</a><br>
    </div>

	</form>
</body>