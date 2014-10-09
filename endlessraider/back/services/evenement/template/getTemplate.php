<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/User.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Evenement.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

session_start();

// On initialise l'objet de réponse
$reponse = array();

if(isset($_GET['idTemplate'])) {	
	// On récupère le template d'événement via son identifiant
	$template = getTemplateEventById($_GET['idTemplate']);
	
	// On construit la réponse
	$reponse['success'] = true;
	$reponse['template'] = $template;
	
} else {
	$reponse['success'] = false;
	$reponse['message'] = "Aucun événement trouvé";
}

// on envoie la réponse au format JSON
echo json_encode($reponse);

?>