<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Evenement.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

session_start();

// On initialise l'objet de réponse
$resultat = array();

if(isset($_GET['idEvent'])) {	
	// On récupère l'événement via son identifiant
	$event = getEventById($_GET['idEvent']);
	
	// On essaie d'enregistrer le template
	if(saveTemplateEvent($event)) {
		$resultat['success'] = true;
		$resultat['message'] = "Template enregistré.";
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "erreur lors de l'enregistrement du template.";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun événement à enregistrer";
}

// on envoie la réponse au format JSON
echo json_encode($resultat);

?>