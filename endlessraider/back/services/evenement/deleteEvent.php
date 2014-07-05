<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

// TODO vérifier que l'utilisateur connecté est bien administrateur !

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";
// On vérifie qu'on a un identifiant d'événement 
if(isset($_GET['idEvent'])) {
	$idEvent = $_GET['idEvent'];
	// On essaie de supprimer l'événement via son identifiant
	try {
		if(deleteEvent($idEvent)) {
			$resultat['success'] = true;
			$resultat['message'] = "Evénement supprimé.";
		} else {
			$resultat['success'] = false;
			$resultat['message'] = "erreur lors de la suppression de l'événement.";
		}
	} catch (Exception $e) {
		$resultat['success'] = false;
		$resultat['message'] = $e->getMessage();
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun événement sélectionné";
}

echo json_encode($resultat);

?>