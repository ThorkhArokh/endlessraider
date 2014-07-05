<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

// TODO vérifier que l'utilisateur connecté est bien administrateur !

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";

$eventToSave = json_decode(file_get_contents("php://input"));
if(isset($eventToSave)){
	// On essaie d'enregistrer l'événement
	if(saveEvent($eventToSave)) {
		$resultat['success'] = true;
		$resultat['message'] = "Evénement enregistré.";
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "erreur lors de l'enregistrement de l'événement.";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun événement à enregistrer";
}

echo json_encode($resultat);

?>