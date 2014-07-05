<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/jeuDao.php');

// TODO vérifier que l'utilisateur connecté est bien administrateur !

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";
// On vérifie qu'on a un identifiant de jeu
if(isset($_GET['idJeu'])) {
	$idJeu = $_GET['idJeu'];
	// On essaie de supprimer le jeu via son identifiant
	if(deleteJeu($idJeu)) {
		$resultat['success'] = true;
		$resultat['message'] = "Jeu supprimé.";
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "erreur lors de la suppression du jeu";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun jeu sélectionné";
}

echo json_encode($resultat);

?>