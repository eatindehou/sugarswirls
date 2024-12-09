
<?php $niveau="../";?>
<?php include ($niveau . "liaisons/php/config.inc.php");?>

<?php

$strMessage = "";

if(isset($_GET['btn_ajouter'])){
	$strCodeOperation = "ajouter";
}
else{
    $strCodeOperation = "";
}
if(isset($_GET['couleur_id']) && isset($_GET['nom'])){

    if($strCodeOperation == "ajouter"){
        $arrListes=array();

            $arrListes['nom']= $_GET['nom'];
            $arrListes['couleur_id']= $_GET['couleur_id'];
            $arrListes['utilisateur_id']= "1";

        

            $strRequeteAjout = "INSERT INTO listes (nom, couleur_id, utilisateur_id) VALUES ('". $arrListes['nom']. "','" . $arrListes['couleur_id']."','".$arrListes['utilisateur_id']."')";
            $pdosResultat = $objPdo->query($strRequeteAjout);
            $pdosResultat->closeCursor();
            header("Location:".$niveau."index.php");
    }
}
else{
    if(!isset($_GET['btn_nouveau'])){
        $strMessage = "Erreur! Veuillez remplir tous les champs.";
    }
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
    <title>Ajout</title>
	<?php include ($niveau . "liaisons/fragments/headlinks.inc.php");?>
</head>
<body>
<?php include ($niveau . "liaisons/fragments/entete.inc.php");?>
<main class="main">
<br>
<br>
<form action="<?php echo $niveau ?>listes/ajouter.php" class="bloc" method="GET">
   
        <p class="error"><?php echo $strMessage; ?></p>
        <h2><label for="nom">Nom de la liste:</label></h2>
        <input type="text" size="50" id="nom" name="nom" value="">
  
    <br>
    <h2>Choisir une couleur:</h2>
    <section class="couleurs">
        
    <?php  for($cpt=0;$cpt<$pdosResultatListe->rowCount();$cpt++){ ?>

		<label class="couleurs__nom" for="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>"><?php echo $arrCouleur[$cpt]['nom_fr']; ?></label>
		<input type="radio" id="<?php echo $arrCouleur[$cpt]['hexadecimal']; ?>" name="couleur_id" value="<?php echo $arrCouleur[$cpt]['id']; ?>"><br>
        <?php  } ?>
	</section>
	<br>
        	<input type="submit" value="Ajouter" class="bouton" name="btn_ajouter">
            <a class="bouton__annuler" href="<?php echo $niveau ?>index.php">Annuler <img src="<?php echo $niveau;?>liaisons/svg/cancel.svg" alt=""></a><br>

	</form>
    <br>
    <br>
    </main>
    <?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>
</body>