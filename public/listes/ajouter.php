
<?php $niveau="../";?>
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
    <title>Ajout</title>
	<?php include ($niveau . "liaisons/fragments/headlinks.inc.html");?>
</head>
<body>
<?php include ($niveau . "liaisons/fragments/entete.inc.php");?>
<main class="main">
<br>
<br>
<form action="<?php echo $niveau ?>listes/ajouter.php" class="bloc" method="GET">

        <input type="hidden" name="couleur_id" value="<?php echo $arrListes[0]['couleur_id']; ?>">
   
  
        <label for="nom">Nom de la liste:</label>
        <input type="text" id="nom" name="nom" value="<?php echo $arrListes['nom']; ?>">
  
    <br>
    <section><?php  for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){ ?>
		<label for="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>"><?php echo $arrCouleur[$cpt]['nom_fr']; ?></label>
		<input type="radio" id="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>" name="hexadecimal" value="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>"><br>
        <?php  } ?>
	</section>
	<br>
        	<input type="submit" value="Ajouter" class="bouton" name="btn_ajouter">
            <a href="<?php echo $niveau ?>index.php">Annuler</a><br>

	</form>
    <br>
    <br>
    </main>
    <?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>
</body>