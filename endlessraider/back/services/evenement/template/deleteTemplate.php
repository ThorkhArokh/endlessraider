<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

// TODO vérifier que l'utilisateur connecté est bien administrateur !

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";
// On vérifie qu'on a un identifiant d'événement 
if(isset($_GET['idTemplate'])) {
	$idTemplate = $_GET['idTemplate'];
	// On essaie de supprimer l'événement via son identifiant
	try {
		if(deleteTemplateEvent($idTemplate)) {
			$resultat['success'] = true;
			$resultat['message'] = "Template supprimé.";
		} else {
			$resultat['success'] = false;
			$resultat['message'] = "erreur lors de la suppression du template.";
		}
	} catch (Exception $e) {
		$resultat['success'] = false;
		$resultat['message'] = $e->getMessage();
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun template sélectionné";
}

echo json_encode($resultat);

?>