<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";

if(isset($_GET['idPerso'])) {
	$idPerso = $_GET['idPerso'];
	// On essaie de supprimer le personnage via son identifiant
	if(deletePersoById($idPerso)) {
		$resultat['success'] = true;
		$resultat['message'] = "Personnage supprimé.";
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "erreur lors de la suppression du personnage ";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun personnage sélectionné";
}

echo json_encode($resultat);
?>