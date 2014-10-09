<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');

session_start();

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";

if(isset($_SESSION['userConnect'])) {
	$user = $_SESSION['userConnect'];
	// Utilisateur doit être un administrateur pour effectuer cette action
	if($user->role->code == ROLE_ADMIN) {
		$resultat['success'] = true;
		$resultat['listeUsers'] = getAllUsers();
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "Vous n'avez pas les droits nécessaires pour effectuer cette action.";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Perte de session. Veuillez vous reconnecter.";
}

// on envoie la réponse au format JSON
echo json_encode($resultat);
?>