<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Evenement.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/StatutParticipant.php');

session_start();

$resultat = array();
$resultat['success'] = false;
$resultat['message'] = "";
$messageSuffixe = "";

$inscriptionParticipant = json_decode(file_get_contents("php://input"));
if(isset($inscriptionParticipant)){
	if(isset($_SESSION['userConnect'])) {
		// On vérifie que le maximum d'inscription n'est pas dépassé 
		$event = getEventById($inscriptionParticipant->event->id);
		// On vérifie que l'évent existe
		if(isset($event)) {
			if($event->isNbrMaxParticipantsAtteint()) {
				// On vérifie que le statut voulu est disponible sinon on ne fait rien de spécial
				if($inscriptionParticipant->statut->code == CODE_STATUT_DISPO) {
					// On inscrit l'utilisateur au statut en attente
					$inscriptionParticipant->statut = RefStatut::getStatut(CODE_STATUT_WAIT);
					$resultat['warning'] = "Votre statut a été basculé à '".RefStatut::getStatut(CODE_STATUT_WAIT)->libelle."' car le nombre de participants maximum a été atteint.";
				}
			} else {
				if($inscriptionParticipant->statut->code == CODE_STATUT_DISPO
					&& isset($inscriptionParticipant->role) && $event->isRolePein($inscriptionParticipant->role) ) {
					// On inscrit l'utilisateur au statut en attente
					$inscriptionParticipant->statut = RefStatut::getStatut(CODE_STATUT_WAIT);
					$resultat['warning'] = "Votre statut a été basculé à '".RefStatut::getStatut(CODE_STATUT_WAIT)->libelle."' car le nombre de participants maximum pour le rôle choisi a été atteint.";
				}
			}
			
			if(!$event->isEventPasse()) {
				// On essaie d'enregistrer l'inscription
				if(inscriptionEvent($inscriptionParticipant, $inscriptionParticipant->event->id, $_SESSION['userConnect']->id)) {
					$resultat['success'] = true;
					$resultat['message'] = "Inscription enregistrée.";
				} else {
					$resultat['success'] = false;
					$resultat['message'] = "erreur lors de l'enregistrement de l'inscription.";
				}
			} else {
				$resultat['success'] = false;
				$resultat['message'] = "L'événement est passé, vous ne pouvez plus vous y inscrire.";
			}
		} else {
			$resultat['success'] = false;
			$resultat['message'] = "Evénement non trouvé.";
		}
	} else {
		$resultat['success'] = false;
		$resultat['message'] = "Perte de session. Veuillez vous reconnecter.";
	}
} else {
	$resultat['success'] = false;
	$resultat['message'] = "Aucune inscription à enregistrer";
}

echo json_encode($resultat);
?>