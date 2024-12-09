<?php $niveau = "../"; ?>
<?php include($niveau . "liaisons/php/config.inc.php"); ?>
<?php
$strFichierTexte = file_get_contents($niveau . 'liaisons/js/messages-erreur.json');
$jsonMessagesErreur = json_decode($strFichierTexte);
$arrChampsErreur = array();
$arrMessagesErreur = array();
$arrMessagesErreur["nom"] = "";
$arrMessagesErreur["completion"] = "";
$arrMessagesErreur["echeance"] = "";
if (isset($_GET['id_item'])) {
    $id_itemSelectionne = $_GET['id_item'];
}
if (isset($_GET['id_liste'])) {
    $idListePrincipale = $_GET['id_liste'];
    // echo $idListePrincipale;
}
if (isset($_GET['nom_liste'])) {
    $nom_itemSelectionne = $_GET['nom_liste'];
}
if (isset($_GET['ajoutSans'])) {
    $estAjouter = $_GET['ajoutSans'];
}

$strCodeOperation = "";
$arrItem = array();
$strCodeErreur = "00000";
$strMessage = "";
$strEnteteH1 = "";
switch (true) {
    case isset($_GET['btn_modifier']);
        $strCodeOperation = 'modifier';
        break;
    case isset($_GET['btn_nouveau']);
        $strCodeOperation = 'nouveau';
        break;
    case isset($_GET['btn_ajouter']);
        $strCodeOperation = 'ajouter';
        break;
    case isset($_GET['btn_supprimer']);
        $strCodeOperation = 'supprimer';
        break;
    default;
        $strCodeOperation = 'afficher';
}
if ($strCodeOperation == 'afficher') {
    // var_dump($strCodeOperation);
    $strRequeteAffichage = 'SELECT id, nom, DAYOFMONTH(echeance)AS jour, MONTH(echeance) AS mois, YEAR(echeance) AS annee, echeance, est_complete FROM items WHERE id=' . $id_itemSelectionne;
    // var_dump($strRequeteAffichage);
    $pdoConnexionAffichage = $objPdo->prepare($strRequeteAffichage);
    $pdoConnexionAffichage->execute();
    $ligne = $pdoConnexionAffichage->fetch();

    $arrItem['id'] = $ligne['id'];
    $arrItem['nom'] = $ligne['nom'];
    $arrItem['jour'] = $ligne['jour'];
    $arrItem['mois'] = $ligne['mois'];
    $arrItem['annee'] = $ligne['annee'];
    $arrItem['echeance'] = $ligne['echeance'];
    // var_dump( $_GET['jour']);
    $arrItem['est_complete'] = $ligne['est_complete'];

    $ligne = $pdoConnexionAffichage->fetch();
    $pdoConnexionAffichage->closeCursor();
    $strEnteteH1 = $arrItem['nom'];

}

if ($strCodeOperation == 'nouveau') {
    // var_dump($strCodeOperation);
    $arrItem['id'] = "0";
    $arrItem['nom'] = "";
    $arrItem['jour'] = "";
    $arrItem['mois'] = "";
    $arrItem['annee'] = "";
    //$arrItem['echeance'] = "NULL";
    $arrItem['est_complete'] = "0";
    $strEnteteH1 = "Ajout de la liste " . $nom_itemSelectionne;
}
if ($strCodeOperation == 'modifier' || $strCodeOperation == 'ajouter' || $strCodeOperation == 'supprimer') {
    $arrItem['id'] = $_GET['id_item'];
    $arrItem['nom'] = $_GET['nom_item'];
    // $arrItem['est_complete'] = $_GET['completion'];
    $arrItem['jour'] = $_GET['jour'];
    $arrItem['mois'] = $_GET['mois'];
    $arrItem['annee'] = $_GET['annee'];

    $strJour = $_GET['jour'];
    $strMois = $_GET['mois'];
    $strAnnee = $_GET['annee'];
    $intJour = intval($strJour);
    $intMois = intval($strMois);
    $intAnnee = intval($strAnnee);
    $blnResultat = checkdate($intMois, $intJour, $intAnnee);
    // var_dump($blnResultat);
    if ($blnResultat) {
        if ($estAjouter == 'on') {
            $strDateValide = $intAnnee . "-" . $intMois . "-" . $intJour;
        } else {
            $strDateValide = NULL;
        }
        if ($strCodeOperation == 'modifier') {
            $strRequeteUpdate = "UPDATE items SET nom =:nom,
				echeance =:echeance
				WHERE id =:id";

            $pdoConnexionUpdate = $objPdo->prepare($strRequeteUpdate);

            $pdoConnexionUpdate->bindValue(':nom', $arrItem['nom']);
            // $pdoConnexionUpdate->bindValue(':completion', $arrItem['est_complete']);
            $pdoConnexionUpdate->bindValue(':echeance', $strDateValide);
            $pdoConnexionUpdate->bindValue(':id', $id_itemSelectionne);

            $arrItem['id'] = $_GET['id_item'];
            $arrItem['nom'] = $_GET['nom_item'];
            // $arrItem['est_complete'] = $_GET['completion'];
            $strDateValide = $intAnnee . "-" . $intMois . "-" . $intJour;
            // $arrItem['id_categorie'] = $_GET['id_categorie'];
            // $arrItem['id_sport'] = $_GET['id_sport'];
            // $arrItem['telephone_participant'] = $_GET['telephone_participant'];
            // $arrItem['codepostal_participant'] = $_GET['codepostal_participant'];

            $pdoConnexionUpdate->execute();

            $strCodeErreur = $pdoConnexionUpdate->errorCode();
            $strCodeErreurInfo = $pdoConnexionUpdate->errorInfo();
            $strMessage = $jsonMessagesErreur->{"modifier"};
            $strEnteteH1 = "Modification " . $arrItem['nom'];
            // var_dump($strCodeErreurInfo);
        } else {
            if ($strCodeOperation == 'ajouter') {
                if ($estAjouter == 'on') {
                    $strDateValide = $intAnnee . "-" . $intMois . "-" . $intJour;
                } else {
                    $strDateValide = NULL;
                }
                $strRequeteInsertion = " INSERT INTO items" .
                    " (nom, echeance, liste_id)" .
                    "VALUES (:nom, :echeance, :liste_id)";

                $pdoConnexionInsertion = $objPdo->prepare($strRequeteInsertion);
                $pdoConnexionInsertion->bindValue(':nom', $arrItem['nom']);
                // $pdoConnexionInsertion->bindValue(':completion', $arrItem['est_complete']);
                $pdoConnexionInsertion->bindValue(':liste_id', $idListePrincipale);
                $pdoConnexionInsertion->bindValue(':echeance', $strDateValide);


                $pdoConnexionInsertion->execute();
                $strCodeErreur = $pdoConnexionInsertion->errorCode();
                $strCodeErreurInfo = $pdoConnexionInsertion->errorInfo();
                $strMessage = $jsonMessagesErreur->{"ajouter"};
                $strEnteteH1 = "Ajout de la liste " . $arrItem['nom'];
                var_dump($strCodeErreurInfo);
            } else {
                $strRequeteDelete = 'DELETE FROM items WHERE id IN (:idParticipant)';
                $pdoConnexionDelete = $objPdo->prepare($strRequeteDelete);
                $pdoConnexionDelete->bindValue(':idParticipant', $id_itemSelectionne);

                // $arrItem['id_participant'] = $_GET['id_participant'];

                $pdoConnexionDelete->execute();
                $strCodeErreur = $pdoConnexionDelete->errorCode();
                $strMessage = $jsonMessagesErreur->{"supprimer"};
                $strEnteteH1 = "Suppression de la liste " . $arrItem['nom'];
            }
        }
    } else {
        // Si la date est invalide
        if ($estAjouter == 'on') {
            $strCodeErreur = -1;
            array_push($arrChampsErreur, "echeance");
        }
    }
}
// Validation du nom
if ($arrItem['nom'] == "" || strlen($arrItem['nom']) > 55) {
    // Si le nom du participant est invalide
    $strCodeErreur = -1;
    array_push($arrChampsErreur, "nom");
}
// Validation de l'échéance
// if ($arrItem['est_complete'] == "" || strlen($arrItem['est_complete']) > 2) {
//     // Si le prénom du participant est invalide
//     $strCodeErreur = -1;
//     array_push($arrChampsErreur, "completion");
// }

function ecrireChecked($valeurRadio, $nomRadio)
{
    $strCocher = "";
    global $arrItem;
    if ($valeurRadio == $arrItem[$nomRadio]) {
        $strCocher = 'checked = "checked"';
    }
    return $strCocher;
}

function ecrireSelected($valeurOption, $nomSelection)
{
    $strSelection = "";
    global $arrItem;
    if ($valeurOption == $arrItem["id_" . $nomSelection]) {
        $strSelection = 'selected="selected"';
    }
    return $strSelection;
}

$arrJour = array(
    "Dimanche",
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi"
);
$arrMois = array(
    "Janvier",
    "Février",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juillet",
    "Août",
    "Septembre",
    "Octobre",
    "Novembre",
    "Décembre"
);

// Gestions de erreurs
if ($strCodeErreur !== "00000" && $strCodeErreur !== "-1") {
    $strMessage = $jsonMessagesErreur->{"echouer"};
    // var_dump($strCodeErreur);
    for ($cpt = 0; $cpt < count($arrChampsErreur); $cpt++) {
        $champ = $arrChampsErreur[$cpt];
        $arrMessagesErreur[$champ] = $jsonMessagesErreur->{$champ};
    }
    // var_dump($strCodeErreur);
} else {
    if ($strCodeOperation != "afficher" && $strCodeOperation != "nouveau") {
        header("Location:../index.php?strCodeOperation=" . $strCodeOperation);
    }
}

// REQUETE DE SELECTION DE TOUTES LES LISTES
$strRequeteToutesLesListes = 'SELECT listes.id, listes.nom, couleurs.hexadecimal FROM listes INNER JOIN couleurs ON listes.couleur_id = couleurs.id ORDER BY listes.nom ';
$pdoConnexionToutesLesListes = $objPdo->prepare($strRequeteToutesLesListes);
$pdoConnexionToutesLesListes->execute();

$arrNomToutesLesListes = array();
$ligneToutesLesListes = $pdoConnexionToutesLesListes->fetch();
for ($cptListe = 0; $cptListe < $pdoConnexionToutesLesListes->rowcount(); $cptListe++) {
    $arrNomToutesLesListes[$cptListe]['id'] = $ligneToutesLesListes['id'];
    $arrNomToutesLesListes[$cptListe]['nom'] = $ligneToutesLesListes['nom'];
    $arrNomToutesLesListes[$cptListe]['couleurs'] = $ligneToutesLesListes['hexadecimal'];
    $ligneToutesLesListes = $pdoConnexionToutesLesListes->fetch();
}
$pdoConnexionToutesLesListes->closeCursor();


$strRequeteListePrincipale = 'SELECT listes.id, listes.nom, couleurs.hexadecimal FROM listes INNER JOIN couleurs ON listes.couleur_id = couleurs.id WHERE listes.id=' . $idListePrincipale;
$pdoConnexionListePrincipale = $objPdo->prepare($strRequeteListePrincipale);
$pdoConnexionListePrincipale->execute();


$arrNomListe = array();
$ligne = $pdoConnexionListePrincipale->fetch();

$arrNomListe['id'] = $ligne['id'];
$arrNomListe['nom'] = $ligne['nom'];
$arrNomListe['couleurs'] = $ligne['hexadecimal'];
$ligne = $pdoConnexionListePrincipale->fetch();
$pdoConnexionListePrincipale->closeCursor();
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Modification participants</title>
    <?php include($niveau . "liaisons/fragments/headlinks.inc.php"); ?>
</head>

<body>
    <header class="entetePage">
        <?php include($niveau . "liaisons/fragments/entete.inc.php") ?>
    </header>
    <main class="mainListe">
        <br action="index.php" action="GET">
        <div class="pAutreTaches">
            <a class="textegGrandeTaille">Mes autres tâches</a>
            <a href="<?php echo $niveau;?>listes/ajouter.php" class="texteMoyenneTaille ajoutListePrincipale hyperlien">Ajouter une liste
            </a>
        </div>
        <nav class="menuListe">
            <ul class="menuListe__listes">
                <?php for ($cptListe = 0; $cptListe < count($arrNomToutesLesListes); $cptListe++) { ?>
                    <li style="border-left: .4rem solid black;" class=" menuListe__items"
                        onmouseover="this.style.borderLeftColor='<?php echo "#" . $arrNomToutesLesListes[$cptListe]["couleurs"]; ?>';"
                        onmouseout="this.style.borderLeftColor='black';"
                        onfocus="this.style.borderTop = '0.4rem solid <?php echo "#" . $arrNomToutesLesListes[$cptListe]["couleurs"] ?>' ;this.style.borderLeft = '0.4rem solid <?php echo "#" . $arrNomToutesLesListes[$cptListe]["couleurs"]; ?>' ;"
                        onblur="this.style.borderTop = '0';" tabindex="0">
                        <a href="index.php?id_liste=<?php echo $arrNomToutesLesListes[$cptListe]['id'];?>" class=" menuListe__lien"
                            style="color: black; text-decoration: none; transition: color 0.3s;"
                            onmouseover="this.style.color='black';" onmouseout="this.style.color='black';">
                            <?php echo $arrNomToutesLesListes[$cptListe]['nom']; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <div class="itemsDeLaListe divMofification">
        <div class="conteneurTitreListe">
        <h1 style="border-bottom: .7rem solid <?php echo "#" . $arrNomListe["couleurs"]; ?>" class="titreNiveau1">
                <?php echo $strEnteteH1; ?>
            </h1>
				<a class="hyperlien ajoutListe" href="modifier.php?id_liste=<?php echo $idListePrincipale; ?>&btn_nouveau=nouveau" class="textePetiteTaille">Ajouter un item
				</a>
			</div>
            <?php if (($strCodeErreur !== "00000" && $strCodeErreur !== "-1") || $strCodeOperation != "modifier" && $strCodeOperation != "ajouter" && $strCodeOperation != "supprimer") { ?>
                <form class="formulireModifAjout" action="modifier.php" method="GET">
                    <div>
                        <?php if ($strCodeOperation == "afficher" || $strCodeOperation == "modifier") { ?>
                            <input type="hidden" name="id_item" value="<?php echo $arrItem['id']; ?>">
                        <?php } else { ?>
                            <input type="text" name="id_item" value="<?php echo $arrItem['id']; ?>">
                        <?php } ?>

                    </div>
                    <div>
                        <label for="nom_item">Nom :</label>
                        <input type="text" id="nom_item" name="nom_item" value="<?php echo $arrItem['nom']; ?>">
                        <span><?php echo $arrMessagesErreur["nom"] ?></span>
                    </div>

                    <h2 class="titreNiveau2">Ajout d'une date d'échéance</h2>
                    <div class="divEcheance">
                        <?php if ($strCodeOperation == 'nouveau' || $strCodeOperation == 'afficher') { ?>

                            <label for="ajoutSans">Ajouter une échéance ?
                                <input type="hidden" value="checked" name="ajoutSans">
                                <input type="checkbox" name="ajoutSans" id="ajoutSans" checked>
                                <span class="boutonCheckbox"></span></label>

                        <?php } ?>
                    </div>
                    <div class="ajoutEcheance">
                        <?php ?>
                        <label hidden for="echeance">Échéance :</label>
                        <select class="selectionDate" name="mois" id="mois">
                            <option value="0"></option>
                            <?php for ($cpt = 1; $cpt <= 12; $cpt++) { ?>
                                <option value="<?php echo $cpt ?>" <?php if ($arrItem['mois'] == $cpt) {
                                       echo 'selected ="selected"';
                                   } ?>>
                                    <?php echo $arrMois[$cpt - 1] ?>
                                </option>
                            <?php } ?>
                        </select>

                        <select class="selectionDate" name="jour" id="jour">
                            <option value="0"></option>
                            <?php for ($cpt = 1; $cpt <= 31; $cpt++) { ?>
                                <option value="<?php echo $cpt ?>" <?php if ($arrItem['jour'] == $cpt) {
                                       echo 'selected ="selected"';
                                   } ?>>
                                    <?php echo $cpt ?>
                                </option>
                            <?php } ?>
                        </select>

                        <select class="selectionDate" name="annee" id="annee">
                            <option value="0"></option>

                            <?php for ($cpt = date("Y"); $cpt >= 1923; $cpt--) { ?>
                                <option value="<?php echo $cpt ?>" <?php if ($arrItem['annee'] == $cpt) {
                                       echo 'selected ="selected"';
                                   } ?>>
                                    <?php echo $cpt ?>
                                </option>
                            <?php } ?>
                        </select>
                        <span><?php echo $arrMessagesErreur["echeance"] ?></span>
                    </div>

                    <h2 class="titreNiveau2">Ajouter un rappel</h2>
                    <div class="conteneurRadio">

                        <label for="oui">Oui
                            <input type="radio" name="rappel" value="oui" id="oui">
                            <span class="boutonRadio"></span></label>


                        <label for="non">Non
                            <input type="radio" name="rappel" value="non" id="non" checked>
                            <span class="boutonRadio"></span></label>

                    </div>
                    <a class="hyperlien" href="index.php">Annuler</a><br><br>
                            <a class="hyperlien lienConsulter" href="index.php">Consulter toutes mes listes</a>
                     <div class="conteneurBoutons">
            <?php if ($strCodeOperation == 'afficher' || $strCodeOperation == 'modifier') { ?>

                <input class="bouton" type="submit" value="Éditer l'item" name="btn_modifier">
                <input class="bouton" type="submit" value="Supprimer" name="btn_supprimer">

            <?php } else { ?>
                <input type="hidden" name="id_liste" value="<?php echo $idListePrincipale ?>">
                <input class="bouton" type="submit" value="Ajouter" id="btn_ajouter" name="btn_ajouter">

            <?php } ?>
        </div>
                </form>

            <?php } else { ?>
                <p>
                    <?php echo $strMessage; ?>
                </p>
            <?php } ?>
        </div>

    </main>
    <?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>

    <script src="<?php echo $niveau; ?>liaisons/js/ajout_echeance.js"></script>
</body>

</html>
<?php ?>