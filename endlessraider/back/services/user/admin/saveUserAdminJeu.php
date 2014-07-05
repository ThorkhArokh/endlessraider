<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/RoleUser.php');

session_start();

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";

if(isset($_SESSION['userConnect'])) {
	$user = $_SESSION['userConnect'];
	// Utilisateur doit être un administrateur pour effectuer cette action
	if($user->role->code == ROLE_ADMIN) {
		if(isset($_GET['idUser']) && isset($_GET['idJeu']) ) {
			if(saveUserAdminJeu($_GET['idUser'], $_GET['idJeu'])) {
				$resultat['success'] = true;
				$resultat['message'] = "Enregistrement effectué.";
			} else {
				$resultat['success'] = false;
				$resultat['message'] = "Une erreur est survenue lors de l'enregistrement des droits.";
			}
		} else {
			$resultat['success'] = false;
			$resultat['message'] = "Aucune donnée à enregistrer.";
		}
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