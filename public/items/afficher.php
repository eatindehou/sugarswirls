Afficher les items!
<?php $niveau = "../"; ?>
<a href="<?php echo $niveau; ?>index.php">Retour</a>
<?php include($niveau . "liaisons/php/config.inc.php"); ?>
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
	var_dump($arrListe[$cpt]['est_complete']);

	if ($arrListe[$cpt]['est_complete'] == 0) {
		$estcomplete = false;
	} else {
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
	<title>Items de la liste Devoirs à faire</title>
	<?php include($niveau . "liaisons/fragments/entete.inc.php") ?>
	<?php include($niveau . "liaisons/fragments/headlinks.inc.html") ?>
</head>

<body>
	<br action="index.php" action="GET">
	<header>
		<p class="textegGrandeTaille">Mes autres tâches</p>
		<p class="textePetiteTaille">Ajouter une liste
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M10 0C8.02219 0 6.08879 0.58649 4.4443 1.6853C2.79981 2.78412 1.51809 4.3459 0.761209 6.17317C0.00433284 8.00043 -0.193701 10.0111 0.192152 11.9509C0.578004 13.8907 1.53041 15.6725 2.92894 17.0711C4.32746 18.4696 6.10929 19.422 8.0491 19.8079C9.98891 20.1937 11.9996 19.9957 13.8268 19.2388C15.6541 18.4819 17.2159 17.2002 18.3147 15.5557C19.4135 13.9112 20 11.9778 20 10C20 8.68678 19.7413 7.38642 19.2388 6.17317C18.7363 4.95991 17.9997 3.85752 17.0711 2.92893C16.1425 2.00035 15.0401 1.26375 13.8268 0.761205C12.6136 0.258658 11.3132 0 10 0ZM10 18C8.41775 18 6.87104 17.5308 5.55544 16.6518C4.23985 15.7727 3.21447 14.5233 2.60897 13.0615C2.00347 11.5997 1.84504 9.99113 2.15372 8.43928C2.4624 6.88743 3.22433 5.46197 4.34315 4.34315C5.46197 3.22433 6.88743 2.4624 8.43928 2.15372C9.99113 1.84504 11.5997 2.00346 13.0615 2.60896C14.5233 3.21447 15.7727 4.23984 16.6518 5.55544C17.5308 6.87103 18 8.41775 18 10C18 12.1217 17.1572 14.1566 15.6569 15.6569C14.1566 17.1571 12.1217 18 10 18ZM14 9H11V6C11 5.73478 10.8946 5.48043 10.7071 5.29289C10.5196 5.10536 10.2652 5 10 5C9.73479 5 9.48043 5.10536 9.2929 5.29289C9.10536 5.48043 9 5.73478 9 6V9H6C5.73479 9 5.48043 9.10536 5.2929 9.29289C5.10536 9.48043 5 9.73478 5 10C5 10.2652 5.10536 10.5196 5.2929 10.7071C5.48043 10.8946 5.73479 11 6 11H9V14C9 14.2652 9.10536 14.5196 9.2929 14.7071C9.48043 14.8946 9.73479 15 10 15C10.2652 15 10.5196 14.8946 10.7071 14.7071C10.8946 14.5196 11 14.2652 11 14V11H14C14.2652 11 14.5196 10.8946 14.7071 10.7071C14.8946 10.5196 15 10.2652 15 10C15 9.73478 14.8946 9.48043 14.7071 9.29289C14.5196 9.10536 14.2652 9 14 9Z"
					fill="#1B1B1B" />
			</svg>
		</p>
		<nav class="menuListe">
			<ul class="menuListe__listes">
				<li class="menuListe__items"><a href="#" class="menuListe__lien">Voyages</a></li>
				<li class="menuListe__items"><a href="#" class="menuListe__lien">Films à voir</a></li>
				<li class="menuListe__items"><a href="#" class="menuListe__lien">Pays à visiter</a></li>
				<li class="menuListe__items"><a href="#" class="menuListe__lien">Repas à manger</a></li>
				<li class="menuListe__items"><a href="#" class="menuListe__lien">Devoirs à faire</a></li>
			</ul>
		</nav>
	</header>
	<main class="conteneur mainListe">
		<ul class="ItemsDeLaListe">
			<h1 class="titreNiveau1">Nom de la liste</h1>
			<p class="textePetiteTaille">Ajouter un item
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M10 0C8.02219 0 6.08879 0.58649 4.4443 1.6853C2.79981 2.78412 1.51809 4.3459 0.761209 6.17317C0.00433284 8.00043 -0.193701 10.0111 0.192152 11.9509C0.578004 13.8907 1.53041 15.6725 2.92894 17.0711C4.32746 18.4696 6.10929 19.422 8.0491 19.8079C9.98891 20.1937 11.9996 19.9957 13.8268 19.2388C15.6541 18.4819 17.2159 17.2002 18.3147 15.5557C19.4135 13.9112 20 11.9778 20 10C20 8.68678 19.7413 7.38642 19.2388 6.17317C18.7363 4.95991 17.9997 3.85752 17.0711 2.92893C16.1425 2.00035 15.0401 1.26375 13.8268 0.761205C12.6136 0.258658 11.3132 0 10 0ZM10 18C8.41775 18 6.87104 17.5308 5.55544 16.6518C4.23985 15.7727 3.21447 14.5233 2.60897 13.0615C2.00347 11.5997 1.84504 9.99113 2.15372 8.43928C2.4624 6.88743 3.22433 5.46197 4.34315 4.34315C5.46197 3.22433 6.88743 2.4624 8.43928 2.15372C9.99113 1.84504 11.5997 2.00346 13.0615 2.60896C14.5233 3.21447 15.7727 4.23984 16.6518 5.55544C17.5308 6.87103 18 8.41775 18 10C18 12.1217 17.1572 14.1566 15.6569 15.6569C14.1566 17.1571 12.1217 18 10 18ZM14 9H11V6C11 5.73478 10.8946 5.48043 10.7071 5.29289C10.5196 5.10536 10.2652 5 10 5C9.73479 5 9.48043 5.10536 9.2929 5.29289C9.10536 5.48043 9 5.73478 9 6V9H6C5.73479 9 5.48043 9.10536 5.2929 9.29289C5.10536 9.48043 5 9.73478 5 10C5 10.2652 5.10536 10.5196 5.2929 10.7071C5.48043 10.8946 5.73479 11 6 11H9V14C9 14.2652 9.10536 14.5196 9.2929 14.7071C9.48043 14.8946 9.73479 15 10 15C10.2652 15 10.5196 14.8946 10.7071 14.7071C10.8946 14.5196 11 14.2652 11 14V11H14C14.2652 11 14.5196 10.8946 14.7071 10.7071C14.8946 10.5196 15 10.2652 15 10C15 9.73478 14.8946 9.48043 14.7071 9.29289C14.5196 9.10536 14.2652 9 14 9Z"
						fill="#1B1B1B" />
				</svg>
			</p>
			<li class="ItemsDeLaListe__liste">
				<?php for ($cpt = 0; $cpt < count($arrListe); $cpt++) { ?>
					<ul class="itemsDeLaListe__item">
						<!-- <li>
							 echo $arrListe[$cpt]["id"]; ?>
						</li> -->
						<li>
							<p class="texteMoyenneTaille"><?php echo $arrListe[$cpt]["nom"] ?></p>
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
								<button type="submit" name="btn_completion"
									value="<?php echo $arrListe[$cpt]["est_complete"]; ?>">À
									Compléter</button>
							<?php } else { ?>
								<?php $estcomplete = false ?>
								<button type="submit" name="btn_completion"
									value="<?php echo $arrListe[$cpt]["est_complete"]; ?>">Complété</button>
							<?php } ?>
							<?php ?>

						</form>
						<form action="index.php" method="GET">
							<input type="hidden" name="id[]" value="<?php echo $arrListe[$cpt]["id"]; ?>">
							<button type="submit" name="btn_suppressionItem" value="btn_suppressionItem">Supprimer
								l'item</button>
						</form>
						<a
							href='maj/index.php?id_participant=<?php echo $arrListe[$cpt]["id_participant"]; ?>&nom_participant=<?php echo $arrListe[$cpt]["nom_participant"]; ?>&prenom_participant=<?php echo $arrListe[$cpt]["prenom_participant"]; ?>&jour_naissance_participant=<?php echo $arrListe[$cpt]["jour"]; ?>&mois_naissance_participant=<?php echo $arrListe[$cpt]["mois"]; ?>&annee_naissance_participant=<?php echo $arrListe[$cpt]["annee"]; ?>&naissance_participant=<?php echo $arrListe[$cpt]["naissance_participant"]; ?>&sexe_participant=<?php echo $arrListe[$cpt]["sexe_participant"]; ?>&id_categorie=<?php echo $arrListe[$cpt]["id_categorie"]; ?>&id_sport=<?php echo $arrListe[$cpt]["id_sport"]; ?>'>
							Éditer l'item</a><br><br>

					</ul>
				<?php } ?>
			</li>
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