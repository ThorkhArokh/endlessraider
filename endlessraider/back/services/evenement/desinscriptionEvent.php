<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

session_start();

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";

if(isset($_GET['idEvent'])) {
	if(isset($_SESSION['userConnect'])) {
		// On essaie d'enregistrer l'inscription
		if(desinscriptionEvent($_GET['idEvent'], $_SESSION['userConnect']->id)) {
			// On met à jour le premier participant inscrit avec le statut en attente au statut disponible
			updatePremierParticipantEnAttente($_GET['idEvent']);
		
			$resultat['success'] = true;
			$resultat['message'] = "Désinscription enregistrée.";
		} else {
			$resultat['success'] = false;
			$resultat['message'] = "erreur lors de la désinscription.";
		}
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "Perte de session. Veuillez vous reconnecter.";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucun événement sélectionné";
}

echo json_encode($resultat);
?>