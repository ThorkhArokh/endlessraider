<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

// TODO vérifier que l'utilisateur connecté est bien administrateur !

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";

$templateToSave = json_decode(file_get_contents("php://input"));
if(isset($templateToSave)){
	// On essaie d'enregistrer le template
	if(saveTemplateEvent($templateToSave)) {
		$resultat['success'] = true;
		$resultat['message'] = "Template enregistré.";
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "erreur lors de l'enregistrement du template.";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun template à enregistrer";
}

echo json_encode($resultat);

?>