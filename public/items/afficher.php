Afficher les items!
<?php $niveau="../";?>
<a href="<?php echo $niveau;?>index.php">Retour</a>
<?php include ($niveau . "liaisons/php/config.inc.php");?>
<?php
$strCodeOperation = "";
if (isset($_GET['btn_suppressionItem'])) {
    $strCodeOperation = 'suppressionItem';
    $arrIDListe = $_GET['id'];
}
if ($strCodeOperation == 'suppressionItem') {
    $strRequeteDelete = 'DELETE FROM items WHERE id IN (' . implode(',', $arrIDListe) . ')';
    $pdoConnexionDelete = $objPdo->prepare($strRequeteDelete);
    $pdoConnexionDelete->execute();
}


if (isset($_GET['id_liste']) == false) {
    $strChampIdListe = 2;
    $strRequete = 'SELECT id, nom, echeance, est_complete FROM items WHERE liste_id=' . $strChampIdListe;
}
// else {
//     $strChampIdListe = 0;
//     $strRequete = 'SELECT id, nom, echeance, est_complete FROM items ORDER BY echeance DESC';
// }

$pdosResultat = $objPdo->prepare($strRequete);
$pdosResultat->execute();

$arrListe = array();
$ligne = $pdosResultat->fetch();
for ($cpt = 0; $cpt < $pdosResultat->rowCount(); $cpt++) {
    $arrListe[$cpt]['id'] = $ligne['id'];
    $arrListe[$cpt]['nom'] = $ligne['nom'];
    $arrListe[$cpt]['echeance'] = $ligne['echeance'];
    $arrListe[$cpt]['est_complete'] = $ligne['est_complete'];
    var_dump( $arrListe[$cpt]['est_complete']);

    if($arrListe[$cpt]['est_complete'] == 0){
        $estcomplete = false;
    }
    else{
        $estcomplete = true;
    }
    $ligne = $pdosResultat->fetch();
}
// AFFICHAGE DES SPORTS

$pdosResultat->closeCursor();

$complete = false 
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Document sans titre</title>
    <?php include($niveau . "liaisons/fragments/headlinks.inc.html") ?>
</head>

<body>
    <br action="index.php" action="GET">
    <h1>Items de la liste</h1>
    <main>
        <ul>
            <?php for ($cpt = 0; $cpt < count($arrListe); $cpt++) { ?>
                <li>
                    <?php echo $arrListe[$cpt]["id"]; ?>
                </li>
                <li>
                    <a href='fiche/index.php?id_items=<?php echo $arrListe[$cpt]["id"]; ?>'>
                        <?php echo $arrListe[$cpt]["nom"] ?></a>
                </li>
                <li>
                    <a href='fiche/index.php?id_items=<?php echo $arrListe[$cpt]["id"]; ?>'>
                        <?php echo $arrListe[$cpt]["echeance"]; ?></a>
                </li>
                <li>
                    <a href='fiche/index.php?id_items=<?php echo $arrListe[$cpt]["id"]; ?>'>
                        <?php echo $arrListe[$cpt]["est_complete"]; ?></a>
                </li>

                <form action="index.php" method="GET">
                    <input type="hidden" name="id[]" value="<?php echo $arrListe[$cpt]["id"]; ?>">

                    <?php if ($estcomplete == false) { ?>
                        <?php $estcomplete = true ?>
                        <button type="submit" name="btn_completion" value="<?php echo $arrListe[$cpt]["est_complete"]; ?>">À Compléter</button>
                        <?php } else { ?>
                            <?php $estcomplete = false ?>
                            <button type="submit" name="btn_completion" value="<?php echo $arrListe[$cpt]["est_complete"]; ?>">Complété</button>
                    <?php } ?>
                    <?php ?>

                </form>
                <form action="index.php" method="GET">
                    <input type="hidden" name="id[]" value="<?php echo $arrListe[$cpt]["id"]; ?>">
                    <button type="submit" name="btn_suppressionItem" value="btn_suppressionItem">Supprimer l'item</button>
                </form>
                <a
                    href='maj/index.php?id_participant=<?php echo $arrListe[$cpt]["id_participant"]; ?>&nom_participant=<?php echo $arrListe[$cpt]["nom_participant"]; ?>&prenom_participant=<?php echo $arrListe[$cpt]["prenom_participant"]; ?>&jour_naissance_participant=<?php echo $arrListe[$cpt]["jour"]; ?>&mois_naissance_participant=<?php echo $arrListe[$cpt]["mois"]; ?>&annee_naissance_participant=<?php echo $arrListe[$cpt]["annee"]; ?>&naissance_participant=<?php echo $arrListe[$cpt]["naissance_participant"]; ?>&sexe_participant=<?php echo $arrListe[$cpt]["sexe_participant"]; ?>&id_categorie=<?php echo $arrListe[$cpt]["id_categorie"]; ?>&id_sport=<?php echo $arrListe[$cpt]["id_sport"]; ?>'>
                    Éditer l'item</a><br><br>

            <?php } ?>
        </ul>
        <a href='#'> Ajouter un nouvel items</a>
        <br></br>
        <a href="<?php echo $niveau; ?>index.php">Retour temporaire</a>
    </main>

    <!-- <form action="index.php" method="GET">
            <input type="submit" name="btn_suppression" value="suppression">
        </form>
        <form action="maj/index.php" method="GET">
            <input type="submit" name="btn_nouveau" value="nouveau">
        </form>
    </form> -->
    <!-- php include($niveau . "liaisons/inc/fragements/balises_script.html") ?>-->
</body>

</html>

