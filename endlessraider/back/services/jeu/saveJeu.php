<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/jeuDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');
// TODO vérifier que l'utilisateur connecté est bien administrateur !

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";

$jeuToSave = json_decode(file_get_contents("php://input"));
if(isset($jeuToSave)){
	try {
		// On essaie d'enregistrer le jeu
		if(saveJeu($jeuToSave)) {
			$resultat['success'] = true;
			$resultat['message'] = "Jeu enregistré.";
		} else {
			$resultat['success'] = false;
			$resultat['message'] = "erreur lors de l'enregistrement du jeu.";
		}
	} catch (Exception $e) {
		$resultat['success'] = false;
		$resultat['message'] = $e->getMessage();
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun jeu à enregistrer";
}

echo json_encode($resultat);

?>