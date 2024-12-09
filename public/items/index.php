<?php $niveau = "../"; ?>
<?php include($niveau . "liaisons/php/config.inc.php");
$strFichierTexte = file_get_contents($niveau . 'liaisons/js/messages-erreur.json');
$jsonMessagesErreur = json_decode($strFichierTexte); ?>
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
	$strRequete = 'SELECT id, nom, echeance, est_complete FROM items ORDER BY echeance DESC';
} else {
	$strChampIdListe = $_GET['id_liste'];
	$strRequete = 'SELECT id, nom, echeance, est_complete FROM items WHERE liste_id=' . $strChampIdListe;
}
if (isset($_GET['btn_completion']) && isset($_GET['id'])) {
	$arrCompletion = array();
	$idItemUnique = $_GET['id'];
	$arrCompletion[$idItemUnique]['completion'] = $_GET['btn_completion'];

	if ($arrCompletion[$idItemUnique]['completion'] == 1) {
		$arrCompletion[$idItemUnique]['completion'] = 0;
	} else if ($arrCompletion[$idItemUnique]['completion'] == 0) {
		$arrCompletion[$idItemUnique]['completion'] = 1;
	}
	$strRequeteUpdate = "UPDATE items SET est_complete =:completion
                    WHERE id =:id";

	$pdoConnexionUpdate = $objPdo->prepare($strRequeteUpdate);

	$pdoConnexionUpdate->bindValue(':completion', $arrCompletion[$idItemUnique]['completion']);
	$pdoConnexionUpdate->bindValue(':id', $idItemUnique);

	$pdoConnexionUpdate->execute();
	// var_dump($estComplete);
}
$strRequete = 'SELECT id, nom,DAYOFMONTH(echeance)AS jour, MONTH(echeance) AS mois, YEAR(echeance) AS annee, echeance, est_complete FROM items WHERE liste_id=' . $strChampIdListe;

$pdosResultat = $objPdo->prepare($strRequete);
$pdosResultat->execute();

$arrEstComplete = array();
$arrListe = array();

$ligne = $pdosResultat->fetch();

for ($cpt = 0; $cpt < $pdosResultat->rowCount(); $cpt++) {
	$arrListe[$cpt]['id'] = $ligne['id'];
	$arrListe[$cpt]['nom'] = $ligne['nom'];
	$arrListe[$cpt]['jour'] = $ligne['jour'];
	$arrListe[$cpt]['mois'] = $ligne['mois'];
	$arrListe[$cpt]['annee'] = $ligne['annee'];
	$arrListe[$cpt]['echeance'] = $ligne['echeance'];

	$arrListe[$cpt]['est_complete'] = $ligne['est_complete'];
	$ligne = $pdosResultat->fetch();

	if ($arrListe[$cpt]['est_complete'] == 1) {
		$estComplete = true;
	} else if ($arrListe[$cpt]['est_complete'] == 0) {
		$estComplete = false;
	}

	if ($arrListe[$cpt]['echeance'] != NULL) {
		$strDate[$cpt] = $arrListe[$cpt]['echeance'];
		$timestamp[$cpt] = strtotime($strDate[$cpt]);
		$formatDate[$cpt]= date(' M d  ', $timestamp[$cpt]);

		echo $formatDate[$cpt];
	}
	// $strFormat = "M";
	// var_dump(date(	$strFormat));

	// $strFormat = "d";
	// $arrLis = date($strFormat);
	// var_dump($arrListe[$cpt]['jour']);

}

$pdosResultat->closeCursor();

$strRequeteListePrincipale = 'SELECT listes.id, listes.nom, couleurs.hexadecimal FROM listes INNER JOIN couleurs ON listes.couleur_id = couleurs.id WHERE listes.id=' . $strChampIdListe;
$pdoConnexionListePrincipale = $objPdo->prepare($strRequeteListePrincipale);
$pdoConnexionListePrincipale->execute();


$arrNomListe = array();
$ligne = $pdoConnexionListePrincipale->fetch();

$arrNomListe['id'] = $ligne['id'];
$arrNomListe['nom'] = $ligne['nom'];
$arrNomListe['couleurs'] = $ligne['hexadecimal'];
$ligne = $pdoConnexionListePrincipale->fetch();
$pdoConnexionListePrincipale->closeCursor();

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

?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Items de la liste Devoirs à faire</title>
	<?php include($niveau . "liaisons/fragments/headlinks.inc.php"); ?>
</head>

<body>
	<header class=" entetePage">
		<?php include($niveau . "liaisons/fragments/entete.inc.php") ?>
	</header>
	<main class=" mainListe">
		<br action="index.php" action="GET">
		<div class="pAutreTaches">
			<p class="textegGrandeTaille">Mes autres tâches</p>
			<a href="<?php echo $niveau; ?>listes/ajouter.php"
				class="texteMoyenneTaille ajoutListePrincipale hyperlien">Ajouter une liste
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
						<a href="index.php?id_liste=<?php echo $arrNomToutesLesListes[$cptListe]['id']; ?>"
							class=" menuListe__lien" style="color: black; text-decoration: none; transition: color 0.3s;"
							onmouseover="this.style.color='black';" onmouseout="this.style.color='black';">
							<?php echo $arrNomToutesLesListes[$cptListe]['nom']; ?></a>
					</li>
				<?php } ?>
			</ul>
		</nav>
		<ul class="itemsDeLaListe">
			<div class="conteneurTitreListe">
				<h1 style="border-bottom: .7rem solid <?php echo "#" . $arrNomListe["couleurs"]; ?>"
					class="titreNiveau1">
					<?php echo $arrNomListe['nom'] ?>
				</h1>
				<a class="hyperlien ajoutListe"
					href="modifier.php?id_liste=<?php echo $strChampIdListe;?>&btn_nouveau=nouveau&nom_liste=<?php echo $arrNomListe["nom"];?>"
					class="textePetiteTaille">Ajouter un item
				</a>
			</div>
			<?php for ($cpt = 0; $cpt < count($arrListe); $cpt++) { ?>
				<li style="border-top: .5rem solid <?php echo "#" . $arrNomListe["couleurs"]; ?>"
					class="itemsDeLaListe__item">
					<p style="background-color:<?php echo "#" . $arrNomListe["couleurs"] . "15"; ?>"
						class="texteMoyenneTaille"><?php echo $arrListe[$cpt]["nom"] ?></p>

					<p class="descriptionItems">
					<p><?php echo $arrListe[$cpt]["nom"] ?></p>

					<?php if ($arrListe[$cpt]['echeance'] == NULL) { ?>
						<p>
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M6.99984 11.083C7.11521 11.083 7.22799 11.0488 7.32392 10.9847C7.41985 10.9206 7.49462 10.8295 7.53877 10.7229C7.58292 10.6163 7.59447 10.499 7.57196 10.3859C7.54945 10.2727 7.4939 10.1688 7.41232 10.0872C7.33074 10.0056 7.2268 9.95006 7.11364 9.92755C7.00048 9.90504 6.88319 9.91659 6.7766 9.96075C6.67001 10.0049 6.57891 10.0797 6.51481 10.1756C6.45072 10.2715 6.4165 10.3843 6.4165 10.4997C6.4165 10.6544 6.47796 10.8028 6.58736 10.9122C6.69675 11.0216 6.84513 11.083 6.99984 11.083ZM9.9165 11.083C10.0319 11.083 10.1447 11.0488 10.2406 10.9847C10.3365 10.9206 10.4113 10.8295 10.4554 10.7229C10.4996 10.6163 10.5111 10.499 10.4886 10.3859C10.4661 10.2727 10.4106 10.1688 10.329 10.0872C10.2474 10.0056 10.1435 9.95006 10.0303 9.92755C9.91715 9.90504 9.79986 9.91659 9.69327 9.96075C9.58668 10.0049 9.49558 10.0797 9.43148 10.1756C9.36738 10.2715 9.33317 10.3843 9.33317 10.4997C9.33317 10.6544 9.39463 10.8028 9.50402 10.9122C9.61342 11.0216 9.76179 11.083 9.9165 11.083ZM9.9165 8.74967C10.0319 8.74967 10.1447 8.71546 10.2406 8.65137C10.3365 8.58727 10.4113 8.49616 10.4554 8.38957C10.4996 8.28298 10.5111 8.16569 10.4886 8.05254C10.4661 7.93938 10.4106 7.83544 10.329 7.75386C10.2474 7.67228 10.1435 7.61672 10.0303 7.59422C9.91715 7.57171 9.79986 7.58326 9.69327 7.62741C9.58668 7.67156 9.49558 7.74633 9.43148 7.84226C9.36738 7.93819 9.33317 8.05097 9.33317 8.16634C9.33317 8.32105 9.39463 8.46942 9.50402 8.57882C9.61342 8.68822 9.76179 8.74967 9.9165 8.74967ZM6.99984 8.74967C7.11521 8.74967 7.22799 8.71546 7.32392 8.65137C7.41985 8.58727 7.49462 8.49616 7.53877 8.38957C7.58292 8.28298 7.59447 8.16569 7.57196 8.05254C7.54945 7.93938 7.4939 7.83544 7.41232 7.75386C7.33074 7.67228 7.2268 7.61672 7.11364 7.59422C7.00048 7.57171 6.88319 7.58326 6.7766 7.62741C6.67001 7.67156 6.57891 7.74633 6.51481 7.84226C6.45072 7.93819 6.4165 8.05097 6.4165 8.16634C6.4165 8.32105 6.47796 8.46942 6.58736 8.57882C6.69675 8.68822 6.84513 8.74967 6.99984 8.74967ZM11.0832 1.74967H10.4998V1.16634C10.4998 1.01163 10.4384 0.863258 10.329 0.753862C10.2196 0.644466 10.0712 0.583008 9.9165 0.583008C9.76179 0.583008 9.61342 0.644466 9.50402 0.753862C9.39463 0.863258 9.33317 1.01163 9.33317 1.16634V1.74967H4.6665V1.16634C4.6665 1.01163 4.60505 0.863258 4.49565 0.753862C4.38625 0.644466 4.23788 0.583008 4.08317 0.583008C3.92846 0.583008 3.78009 0.644466 3.67069 0.753862C3.5613 0.863258 3.49984 1.01163 3.49984 1.16634V1.74967H2.9165C2.45238 1.74967 2.00726 1.93405 1.67907 2.26224C1.35088 2.59043 1.1665 3.03555 1.1665 3.49967V11.6663C1.1665 12.1305 1.35088 12.5756 1.67907 12.9038C2.00726 13.232 2.45238 13.4163 2.9165 13.4163H11.0832C11.5473 13.4163 11.9924 13.232 12.3206 12.9038C12.6488 12.5756 12.8332 12.1305 12.8332 11.6663V3.49967C12.8332 3.03555 12.6488 2.59043 12.3206 2.26224C11.9924 1.93405 11.5473 1.74967 11.0832 1.74967ZM11.6665 11.6663C11.6665 11.8211 11.605 11.9694 11.4956 12.0788C11.3863 12.1882 11.2379 12.2497 11.0832 12.2497H2.9165C2.76179 12.2497 2.61342 12.1882 2.50402 12.0788C2.39463 11.9694 2.33317 11.8211 2.33317 11.6663V6.41634H11.6665V11.6663ZM11.6665 5.24967H2.33317V3.49967C2.33317 3.34496 2.39463 3.19659 2.50402 3.0872C2.61342 2.9778 2.76179 2.91634 2.9165 2.91634H3.49984V3.49967C3.49984 3.65438 3.5613 3.80276 3.67069 3.91215C3.78009 4.02155 3.92846 4.08301 4.08317 4.08301C4.23788 4.08301 4.38625 4.02155 4.49565 3.91215C4.60505 3.80276 4.6665 3.65438 4.6665 3.49967V2.91634H9.33317V3.49967C9.33317 3.65438 9.39463 3.80276 9.50402 3.91215C9.61342 4.02155 9.76179 4.08301 9.9165 4.08301C10.0712 4.08301 10.2196 4.02155 10.329 3.91215C10.4384 3.80276 10.4998 3.65438 10.4998 3.49967V2.91634H11.0832C11.2379 2.91634 11.3863 2.9778 11.4956 3.0872C11.605 3.19659 11.6665 3.34496 11.6665 3.49967V5.24967ZM4.08317 8.74967C4.19854 8.74967 4.31132 8.71546 4.40725 8.65137C4.50318 8.58727 4.57795 8.49616 4.6221 8.38957C4.66625 8.28298 4.6778 8.16569 4.6553 8.05254C4.63279 7.93938 4.57723 7.83544 4.49565 7.75386C4.41407 7.67228 4.31013 7.61672 4.19697 7.59422C4.08382 7.57171 3.96653 7.58326 3.85994 7.62741C3.75335 7.67156 3.66224 7.74633 3.59815 7.84226C3.53405 7.93819 3.49984 8.05097 3.49984 8.16634C3.49984 8.32105 3.5613 8.46942 3.67069 8.57882C3.78009 8.68822 3.92846 8.74967 4.08317 8.74967ZM4.08317 11.083C4.19854 11.083 4.31132 11.0488 4.40725 10.9847C4.50318 10.9206 4.57795 10.8295 4.6221 10.7229C4.66625 10.6163 4.6778 10.499 4.6553 10.3859C4.63279 10.2727 4.57723 10.1688 4.49565 10.0872C4.41407 10.0056 4.31013 9.95006 4.19697 9.92755C4.08382 9.90504 3.96653 9.91659 3.85994 9.96075C3.75335 10.0049 3.66224 10.0797 3.59815 10.1756C3.53405 10.2715 3.49984 10.3843 3.49984 10.4997C3.49984 10.6544 3.5613 10.8028 3.67069 10.9122C3.78009 11.0216 3.92846 11.083 4.08317 11.083Z"
									fill="#929292" />
							</svg>
							Aucune échéance
						</p>
					<?php } else { ?>
						<p>
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M6.99984 11.083C7.11521 11.083 7.22799 11.0488 7.32392 10.9847C7.41985 10.9206 7.49462 10.8295 7.53877 10.7229C7.58292 10.6163 7.59447 10.499 7.57196 10.3859C7.54945 10.2727 7.4939 10.1688 7.41232 10.0872C7.33074 10.0056 7.2268 9.95006 7.11364 9.92755C7.00048 9.90504 6.88319 9.91659 6.7766 9.96075C6.67001 10.0049 6.57891 10.0797 6.51481 10.1756C6.45072 10.2715 6.4165 10.3843 6.4165 10.4997C6.4165 10.6544 6.47796 10.8028 6.58736 10.9122C6.69675 11.0216 6.84513 11.083 6.99984 11.083ZM9.9165 11.083C10.0319 11.083 10.1447 11.0488 10.2406 10.9847C10.3365 10.9206 10.4113 10.8295 10.4554 10.7229C10.4996 10.6163 10.5111 10.499 10.4886 10.3859C10.4661 10.2727 10.4106 10.1688 10.329 10.0872C10.2474 10.0056 10.1435 9.95006 10.0303 9.92755C9.91715 9.90504 9.79986 9.91659 9.69327 9.96075C9.58668 10.0049 9.49558 10.0797 9.43148 10.1756C9.36738 10.2715 9.33317 10.3843 9.33317 10.4997C9.33317 10.6544 9.39463 10.8028 9.50402 10.9122C9.61342 11.0216 9.76179 11.083 9.9165 11.083ZM9.9165 8.74967C10.0319 8.74967 10.1447 8.71546 10.2406 8.65137C10.3365 8.58727 10.4113 8.49616 10.4554 8.38957C10.4996 8.28298 10.5111 8.16569 10.4886 8.05254C10.4661 7.93938 10.4106 7.83544 10.329 7.75386C10.2474 7.67228 10.1435 7.61672 10.0303 7.59422C9.91715 7.57171 9.79986 7.58326 9.69327 7.62741C9.58668 7.67156 9.49558 7.74633 9.43148 7.84226C9.36738 7.93819 9.33317 8.05097 9.33317 8.16634C9.33317 8.32105 9.39463 8.46942 9.50402 8.57882C9.61342 8.68822 9.76179 8.74967 9.9165 8.74967ZM6.99984 8.74967C7.11521 8.74967 7.22799 8.71546 7.32392 8.65137C7.41985 8.58727 7.49462 8.49616 7.53877 8.38957C7.58292 8.28298 7.59447 8.16569 7.57196 8.05254C7.54945 7.93938 7.4939 7.83544 7.41232 7.75386C7.33074 7.67228 7.2268 7.61672 7.11364 7.59422C7.00048 7.57171 6.88319 7.58326 6.7766 7.62741C6.67001 7.67156 6.57891 7.74633 6.51481 7.84226C6.45072 7.93819 6.4165 8.05097 6.4165 8.16634C6.4165 8.32105 6.47796 8.46942 6.58736 8.57882C6.69675 8.68822 6.84513 8.74967 6.99984 8.74967ZM11.0832 1.74967H10.4998V1.16634C10.4998 1.01163 10.4384 0.863258 10.329 0.753862C10.2196 0.644466 10.0712 0.583008 9.9165 0.583008C9.76179 0.583008 9.61342 0.644466 9.50402 0.753862C9.39463 0.863258 9.33317 1.01163 9.33317 1.16634V1.74967H4.6665V1.16634C4.6665 1.01163 4.60505 0.863258 4.49565 0.753862C4.38625 0.644466 4.23788 0.583008 4.08317 0.583008C3.92846 0.583008 3.78009 0.644466 3.67069 0.753862C3.5613 0.863258 3.49984 1.01163 3.49984 1.16634V1.74967H2.9165C2.45238 1.74967 2.00726 1.93405 1.67907 2.26224C1.35088 2.59043 1.1665 3.03555 1.1665 3.49967V11.6663C1.1665 12.1305 1.35088 12.5756 1.67907 12.9038C2.00726 13.232 2.45238 13.4163 2.9165 13.4163H11.0832C11.5473 13.4163 11.9924 13.232 12.3206 12.9038C12.6488 12.5756 12.8332 12.1305 12.8332 11.6663V3.49967C12.8332 3.03555 12.6488 2.59043 12.3206 2.26224C11.9924 1.93405 11.5473 1.74967 11.0832 1.74967ZM11.6665 11.6663C11.6665 11.8211 11.605 11.9694 11.4956 12.0788C11.3863 12.1882 11.2379 12.2497 11.0832 12.2497H2.9165C2.76179 12.2497 2.61342 12.1882 2.50402 12.0788C2.39463 11.9694 2.33317 11.8211 2.33317 11.6663V6.41634H11.6665V11.6663ZM11.6665 5.24967H2.33317V3.49967C2.33317 3.34496 2.39463 3.19659 2.50402 3.0872C2.61342 2.9778 2.76179 2.91634 2.9165 2.91634H3.49984V3.49967C3.49984 3.65438 3.5613 3.80276 3.67069 3.91215C3.78009 4.02155 3.92846 4.08301 4.08317 4.08301C4.23788 4.08301 4.38625 4.02155 4.49565 3.91215C4.60505 3.80276 4.6665 3.65438 4.6665 3.49967V2.91634H9.33317V3.49967C9.33317 3.65438 9.39463 3.80276 9.50402 3.91215C9.61342 4.02155 9.76179 4.08301 9.9165 4.08301C10.0712 4.08301 10.2196 4.02155 10.329 3.91215C10.4384 3.80276 10.4998 3.65438 10.4998 3.49967V2.91634H11.0832C11.2379 2.91634 11.3863 2.9778 11.4956 3.0872C11.605 3.19659 11.6665 3.34496 11.6665 3.49967V5.24967ZM4.08317 8.74967C4.19854 8.74967 4.31132 8.71546 4.40725 8.65137C4.50318 8.58727 4.57795 8.49616 4.6221 8.38957C4.66625 8.28298 4.6778 8.16569 4.6553 8.05254C4.63279 7.93938 4.57723 7.83544 4.49565 7.75386C4.41407 7.67228 4.31013 7.61672 4.19697 7.59422C4.08382 7.57171 3.96653 7.58326 3.85994 7.62741C3.75335 7.67156 3.66224 7.74633 3.59815 7.84226C3.53405 7.93819 3.49984 8.05097 3.49984 8.16634C3.49984 8.32105 3.5613 8.46942 3.67069 8.57882C3.78009 8.68822 3.92846 8.74967 4.08317 8.74967ZM4.08317 11.083C4.19854 11.083 4.31132 11.0488 4.40725 10.9847C4.50318 10.9206 4.57795 10.8295 4.6221 10.7229C4.66625 10.6163 4.6778 10.499 4.6553 10.3859C4.63279 10.2727 4.57723 10.1688 4.49565 10.0872C4.41407 10.0056 4.31013 9.95006 4.19697 9.92755C4.08382 9.90504 3.96653 9.91659 3.85994 9.96075C3.75335 10.0049 3.66224 10.0797 3.59815 10.1756C3.53405 10.2715 3.49984 10.3843 3.49984 10.4997C3.49984 10.6544 3.5613 10.8028 3.67069 10.9122C3.78009 11.0216 3.92846 11.083 4.08317 11.083Z"
									fill="#929292" />
							</svg>
							Echeance :<?php echo $formatDate[$cpt] ?>
						</p>
					<?php } ?>
					</p>
					<div class="conteneurBtnEditon">

						<form action="index.php" method="GET">

							<?php if ($arrListe[$cpt]['est_complete'] == 0) { ?>
								<input type="hidden" name="id" value="<?php echo $arrListe[$cpt]["id"]; ?>">
								<button type="submit" name="btn_completion"
									value="<?php echo $arrListe[$cpt]['est_complete']; ?>"><svg width="24" height="24"
										viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<circle cx="12" cy="12" r="11" fill="none" stroke="#1B1B1B" stroke-width="2" />
									</svg>
								</button>

							<?php } else if ($arrListe[$cpt]['est_complete'] == 1) { ?>
									<input type="hidden" name="id" value="<?php echo $arrListe[$cpt]["id"]; ?>">
									<button type="submit" name="btn_completion"
										value="<?php echo $arrListe[$cpt]['est_complete']; ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25"
											fill="none">
											<path
												d="M15.264 8.398L10.116 13.558L8.136 11.578C8.02843 11.4524 7.89605 11.3504 7.74717 11.2783C7.59829 11.2063 7.43613 11.1658 7.27087 11.1594C7.10561 11.1531 6.94081 11.1809 6.78682 11.2412C6.63283 11.3016 6.49297 11.3931 6.37602 11.51C6.25908 11.627 6.16757 11.7668 6.10724 11.9208C6.04691 12.0748 6.01906 12.2396 6.02545 12.4049C6.03183 12.5701 6.07231 12.7323 6.14433 12.8812C6.21636 13.03 6.31839 13.1624 6.444 13.27L9.264 16.102C9.37613 16.2132 9.50911 16.3012 9.65532 16.3609C9.80152 16.4206 9.95808 16.4509 10.116 16.45C10.4308 16.4487 10.7325 16.3237 10.956 16.102L16.956 10.102C17.0685 9.99044 17.1578 9.85772 17.2187 9.71149C17.2796 9.56526 17.311 9.40841 17.311 9.25C17.311 9.09158 17.2796 8.93474 17.2187 8.78851C17.1578 8.64227 17.0685 8.50955 16.956 8.398C16.7312 8.1745 16.427 8.04905 16.11 8.04905C15.793 8.04905 15.4888 8.1745 15.264 8.398ZM12 0.25C9.62663 0.25 7.30655 0.953788 5.33316 2.27236C3.35977 3.59094 1.8217 5.46508 0.913451 7.6578C0.00519941 9.85051 -0.232441 12.2633 0.230582 14.5911C0.693605 16.9189 1.83649 19.057 3.51472 20.7353C5.19295 22.4135 7.33115 23.5564 9.65892 24.0194C11.9867 24.4824 14.3995 24.2448 16.5922 23.3365C18.7849 22.4283 20.6591 20.8902 21.9776 18.9168C23.2962 16.9434 24 14.6234 24 12.25C24 10.6741 23.6896 9.1137 23.0866 7.6578C22.4835 6.20189 21.5996 4.87902 20.4853 3.76472C19.371 2.65042 18.0481 1.7665 16.5922 1.16345C15.1363 0.560389 13.5759 0.25 12 0.25V0.25ZM12 21.85C10.1013 21.85 8.24524 21.287 6.66653 20.2321C5.08782 19.1772 3.85736 17.6779 3.13076 15.9238C2.40416 14.1696 2.21405 12.2393 2.58447 10.3771C2.95488 8.51491 3.8692 6.80436 5.21178 5.46177C6.55436 4.11919 8.26492 3.20488 10.1271 2.83446C11.9894 2.46404 13.9196 2.65415 15.6738 3.38076C17.4279 4.10736 18.9272 5.33781 19.9821 6.91652C21.037 8.49524 21.6 10.3513 21.6 12.25C21.6 14.7961 20.5886 17.2379 18.7882 19.0382C16.9879 20.8386 14.5461 21.85 12 21.85V21.85Z"
												fill="#1B1B1B" />
										</svg></button>
							<?php } ?>
						</form>

						<?php if ($arrListe[$cpt]['echeance'] == NULL) { ?>
							<a
								href='modifier.php?id_liste=<?php echo urlencode($arrNomListe["id"]); ?>&id_item=<?php echo urlencode($arrListe[$cpt]["id"]); ?>&nom_item=<?php echo urlencode($arrListe[$cpt]["nom"]); ?>&echeance=NULL&jour=NULL&mois=NULL; ?>&completion=<?php echo urlencode($arrListe[$cpt]["est_complete"]); ?>&annee=NULL'>
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path
										d="M3.6 19.2014H8.688C8.84593 19.2023 9.00248 19.172 9.14869 19.1123C9.29489 19.0526 9.42787 18.9647 9.54 18.8535L17.844 10.5399L21.252 7.20487C21.3645 7.09335 21.4537 6.96066 21.5147 6.81447C21.5756 6.66829 21.607 6.51148 21.607 6.35312C21.607 6.19475 21.5756 6.03795 21.5147 5.89176C21.4537 5.74557 21.3645 5.61289 21.252 5.50136L16.164 0.354855C16.0524 0.242414 15.9197 0.153166 15.7735 0.0922616C15.6273 0.0313568 15.4704 0 15.312 0C15.1536 0 14.9967 0.0313568 14.8505 0.0922616C14.7043 0.153166 14.5716 0.242414 14.46 0.354855L11.076 3.74987L2.748 12.0635C2.63678 12.1756 2.54879 12.3085 2.48907 12.4547C2.42936 12.6008 2.39909 12.7573 2.4 12.9152V18.0017C2.4 18.3199 2.52643 18.625 2.75147 18.85C2.97652 19.075 3.28174 19.2014 3.6 19.2014ZM15.312 2.89812L18.708 6.29313L17.004 7.99664L13.608 4.60162L15.312 2.89812ZM4.8 13.4071L11.916 6.29313L15.312 9.68815L8.196 16.8021H4.8V13.4071ZM22.8 21.6007H1.2C0.88174 21.6007 0.576515 21.7271 0.351472 21.9521C0.126428 22.177 0 22.4822 0 22.8003C0 23.1185 0.126428 23.4237 0.351472 23.6486C0.576515 23.8736 0.88174 24 1.2 24H22.8C23.1183 24 23.4235 23.8736 23.6485 23.6486C23.8736 23.4237 24 23.1185 24 22.8003C24 22.4822 23.8736 22.177 23.6485 21.9521C23.4235 21.7271 23.1183 21.6007 22.8 21.6007Z"
										fill="#1B1B1B" />
								</svg></a>
						<?php } else { ?>
							<a
								href='modifier.php?id_liste=<?php echo urlencode($arrNomListe["id"]); ?>&id_item=<?php echo urlencode($arrListe[$cpt]["id"]); ?>&nom_item=<?php echo urlencode($arrListe[$cpt]["nom"]); ?>&echeance=<?php echo urlencode($arrListe[$cpt]["echeance"]); ?>&jour=<?php echo urlencode($arrListe[$cpt]['jour']); ?>&mois=<?php echo urlencode($arrListe[$cpt]['mois']); ?>&completion=<?php echo urlencode($arrListe[$cpt]["est_complete"]); ?>&annee=<?php echo urlencode($arrListe[$cpt]['annee']); ?>'>
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path
										d="M3.6 19.2014H8.688C8.84593 19.2023 9.00248 19.172 9.14869 19.1123C9.29489 19.0526 9.42787 18.9647 9.54 18.8535L17.844 10.5399L21.252 7.20487C21.3645 7.09335 21.4537 6.96066 21.5147 6.81447C21.5756 6.66829 21.607 6.51148 21.607 6.35312C21.607 6.19475 21.5756 6.03795 21.5147 5.89176C21.4537 5.74557 21.3645 5.61289 21.252 5.50136L16.164 0.354855C16.0524 0.242414 15.9197 0.153166 15.7735 0.0922616C15.6273 0.0313568 15.4704 0 15.312 0C15.1536 0 14.9967 0.0313568 14.8505 0.0922616C14.7043 0.153166 14.5716 0.242414 14.46 0.354855L11.076 3.74987L2.748 12.0635C2.63678 12.1756 2.54879 12.3085 2.48907 12.4547C2.42936 12.6008 2.39909 12.7573 2.4 12.9152V18.0017C2.4 18.3199 2.52643 18.625 2.75147 18.85C2.97652 19.075 3.28174 19.2014 3.6 19.2014ZM15.312 2.89812L18.708 6.29313L17.004 7.99664L13.608 4.60162L15.312 2.89812ZM4.8 13.4071L11.916 6.29313L15.312 9.68815L8.196 16.8021H4.8V13.4071ZM22.8 21.6007H1.2C0.88174 21.6007 0.576515 21.7271 0.351472 21.9521C0.126428 22.177 0 22.4822 0 22.8003C0 23.1185 0.126428 23.4237 0.351472 23.6486C0.576515 23.8736 0.88174 24 1.2 24H22.8C23.1183 24 23.4235 23.8736 23.6485 23.6486C23.8736 23.4237 24 23.1185 24 22.8003C24 22.4822 23.8736 22.177 23.6485 21.9521C23.4235 21.7271 23.1183 21.6007 22.8 21.6007Z"
										fill="#1B1B1B" />
								</svg></a>
						<?php } ?>
						<form class="iconeSupp" action="index.php" method="GET">
							<input type="hidden" name="id[]" value="<?php echo $arrListe[$cpt]["id"]; ?>">
							<button type="submit" name="btn_suppressionItem" value="btn_suppressionItem"><svg
									xmlns="http://www.w3.org/2000/svg" width="24" height="27" viewBox="0 0 24 27"
									fill="none">
									<path
										d="M9.33333 21.2677C9.68696 21.2677 10.0261 21.1276 10.2761 20.8784C10.5262 20.6291 10.6667 20.291 10.6667 19.9385V11.9631C10.6667 11.6105 10.5262 11.2724 10.2761 11.0232C10.0261 10.7739 9.68696 10.6338 9.33333 10.6338C8.97971 10.6338 8.64057 10.7739 8.39052 11.0232C8.14048 11.2724 8 11.6105 8 11.9631V19.9385C8 20.291 8.14048 20.6291 8.39052 20.8784C8.64057 21.1276 8.97971 21.2677 9.33333 21.2677ZM22.6667 5.31692H17.3333V3.98769C17.3333 2.93009 16.9119 1.91581 16.1618 1.16797C15.4116 0.420131 14.3942 0 13.3333 0H10.6667C9.6058 0 8.58838 0.420131 7.83824 1.16797C7.08809 1.91581 6.66667 2.93009 6.66667 3.98769V5.31692H1.33333C0.979711 5.31692 0.640573 5.45697 0.390524 5.70625C0.140476 5.95553 0 6.29362 0 6.64615C0 6.99869 0.140476 7.33678 0.390524 7.58606C0.640573 7.83534 0.979711 7.97538 1.33333 7.97538H2.66667V22.5969C2.66667 23.6545 3.08809 24.6688 3.83824 25.4166C4.58839 26.1645 5.6058 26.5846 6.66667 26.5846H17.3333C18.3942 26.5846 19.4116 26.1645 20.1618 25.4166C20.9119 24.6688 21.3333 23.6545 21.3333 22.5969V7.97538H22.6667C23.0203 7.97538 23.3594 7.83534 23.6095 7.58606C23.8595 7.33678 24 6.99869 24 6.64615C24 6.29362 23.8595 5.95553 23.6095 5.70625C23.3594 5.45697 23.0203 5.31692 22.6667 5.31692ZM9.33333 3.98769C9.33333 3.63516 9.47381 3.29706 9.72386 3.04778C9.97391 2.79851 10.313 2.65846 10.6667 2.65846H13.3333C13.687 2.65846 14.0261 2.79851 14.2761 3.04778C14.5262 3.29706 14.6667 3.63516 14.6667 3.98769V5.31692H9.33333V3.98769ZM18.6667 22.5969C18.6667 22.9495 18.5262 23.2876 18.2761 23.5368C18.0261 23.7861 17.687 23.9262 17.3333 23.9262H6.66667C6.31305 23.9262 5.97391 23.7861 5.72386 23.5368C5.47381 23.2876 5.33333 22.9495 5.33333 22.5969V7.97538H18.6667V22.5969ZM14.6667 21.2677C15.0203 21.2677 15.3594 21.1276 15.6095 20.8784C15.8595 20.6291 16 20.291 16 19.9385V11.9631C16 11.6105 15.8595 11.2724 15.6095 11.0232C15.3594 10.7739 15.0203 10.6338 14.6667 10.6338C14.313 10.6338 13.9739 10.7739 13.7239 11.0232C13.4738 11.2724 13.3333 11.6105 13.3333 11.9631V19.9385C13.3333 20.291 13.4738 20.6291 13.7239 20.8784C13.9739 21.1276 14.313 21.2677 14.6667 21.2677Z"
										fill="#1B1B1B" />
								</svg></button>
						</form>
					</div>

				</li>
			<?php } ?>
		</ul>
		<a class="hyperlien lienRetour" href="<?php echo $niveau; ?>index.php">Retour temporaire</a>
	</main>
	<div class="conteneurBoutons">
		<form class="formNouveau" action="modifier.php" method="GET">
			<input type="hidden" name="id_liste" value="<?php echo  ($arrNomListe["id"]);?>">
			<input type="hidden" name="nom_liste" value="<?php echo ($arrNomListe["nom"]);?>">
			<input class="bouton btn_nouveau" type="submit" name="btn_nouveau" value="nouveau">
		</form>
		</form>
		<form action="index.php" method="GET">
			<input class="bouton" type="submit" name="btn_suppression" value="suppression">
		</form>
	</div>
	</main>


</body>

</html>