<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/User.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Evenement.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

session_start();

// On initialise l'objet de réponse
$reponse = array();

if(isset($_GET['idEvent'])) {	
	// On récupère l'événement via son identifiant
	$event = getEventById($_GET['idEvent']);
	
	$isUserInscrit = false;
	$isNbrMaxParticipantsAtteint = false;
	$isEventPasse = false;
	$listPersoUser = array();
	// On vérifie que l'utilisateur connecté est inscrit ou pas
	if(isset($_SESSION['userConnect'])) {
		$userSession = $_SESSION['userConnect'];
		$isUserInscrit = $event->isUserInscritEnParticipant($userSession);
		$isNbrMaxParticipantsAtteint = $event->isNbrMaxParticipantsAtteint();
		$isEventPasse = $event->isEventPasse();
		foreach($userSession->persos as $perso) {
			// On ne récupère que les personnages correspondant au jeu
			if($perso->jeu->id == $event->jeu->id) {
				$isPersoOk = true;
				// Si les levels minimum/maximum sont précisés on filtre
				if(isset($event->levelMin)) {
					if(isset($perso->level)) {
						if($perso->level < $event->levelMin ) {
							$isPersoOk = false;
						}
					} else {
						$isPersoOk = false;
					}
				}
				if(isset($event->levelMax)) {
					if(isset($perso->level)) {
						if($perso->level > $event->levelMax ) {
							$isPersoOk = false;
						}
					} else {
						$isPersoOk = false;
					}
				}
				if($isPersoOk) {
					$listPersoUser[] = $perso;
				}
			}
		}
	}
	
	// On construit la réponse
	$reponse['success'] = true;
	$reponse['event'] = $event;
	$reponse['isUserInscrit'] = $isUserInscrit;
	$reponse['listPersoUser'] = $listPersoUser;
	$reponse['isNbrMaxParticipantsAtteint'] = $isNbrMaxParticipantsAtteint;
	$reponse['isEventPasse'] = $isEventPasse;
} else {
	$reponse['success'] = false;
	$reponse['message'] = "Aucun événement trouvé";
}

// on envoie la réponse au format JSON
echo json_encode($reponse);

?>