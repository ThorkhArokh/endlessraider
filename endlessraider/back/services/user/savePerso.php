<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');

session_start();

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";
	
$persoToSave = json_decode(file_get_contents("php://input"));
if(isset($persoToSave)){
	if(isset($_SESSION['userConnect'])) {
		$user = $_SESSION['userConnect'];
		try {
			// On essaie d'enregistrer le personnage
			if(savePersonnage($persoToSave, $user->id)) {
				// On met à jour la liste de personnage de l'utilisateur connecté
				$_SESSION['userConnect']->persos = getPersonnagesByUserId($user->id);
				$resultat['success'] = true;
				$resultat['message'] = "Personnage enregistré.";
			} else {
				$resultat['success'] = false;
				$resultat['message'] = "erreur lors de l'enregistrement du personnage.";
			}
		} catch (Exception $e) {
			$resultat['success'] = false;
			$resultat['message'] = $e->getMessage();
		}
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "Perte de session. Veuillez vous reconnecter.";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun personnage à enregistrer";
}

echo json_encode($resultat);
	
?>