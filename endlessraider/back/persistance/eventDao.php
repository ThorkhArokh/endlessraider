<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/connectionBDD.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Evenement.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Jeu.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Role.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Classe.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Race.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Personnage.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Participant.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/StatutParticipant.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/jeuDao.php');

// Fonction qui retourne l'intégralité des événements
function getAllEvents($isHisto) {
	$listEvent = array();
	
	$whereClauseSysdate = "";
	if(!$isHisto) {
		$whereClauseSysdate = "WHERE e.date > CURDATE()";
	}
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT
	id, 
	nom, 
	description, 
	imagePath,
	DATE_FORMAT(e.date, '%d/%m/%Y') AS dateEvent, 
	DATE_FORMAT(e.date, '%H:%i') AS heureEvent,
	idJeu,
	nbrParticipantsMax,
	levelMin,
	levelMax
	FROM er_evenement e ".
	$whereClauseSysdate 
	."ORDER BY e.date DESC");

	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$event = new Evenement($lignes->nom, $lignes->dateEvent);
		$event->id = $lignes->id;
		$event->jeu = getJeuById($lignes->idJeu);
		$event->heure = $lignes->heureEvent;
		$event->desc = $lignes->description;
		$event->imagePath = $lignes->imagePath;
		$event->nbrParticipantMax = $lignes->nbrParticipantsMax;
		$event->levelMin = $lignes->levelMin;
		$event->levelMax = $lignes->levelMax;
		$event->listRoles = getListRoles($lignes->id);
		$event->listParticipants = getListParticipants($lignes->id);
		
		$listEvent[] = $event;
	}
	
	return $listEvent;
}

// Fonction qui retourne l'intégralité des événements pour un jeu donné
function getAllEventsByIdJeu($idUser, $isHisto) {
	$listEvent = array();
	
	$whereClauseSysdate = "";
	if(!$isHisto) {
		$whereClauseSysdate = " AND e.date > CURDATE() ";
	}
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT
	id, 
	nom, 
	description, 
	imagePath,
	DATE_FORMAT(e.date, '%d/%m/%Y') AS dateEvent, 
	DATE_FORMAT(e.date, '%H:%i') AS heureEvent,
	e.idJeu,
	nbrParticipantsMax,
	levelMin,
	levelMax
	FROM er_evenement e, er_usergestionjeu g
	WHERE e.idJeu = g.idJeu 
	AND g.idUser = :idUser ".
	$whereClauseSysdate 
	." ORDER BY e.date DESC ");

	$sqlQuery->execute(array( 'idUser' => $idUser ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$event = new Evenement($lignes->nom, $lignes->dateEvent);
		$event->id = $lignes->id;
		$event->jeu = getJeuById($lignes->idJeu);
		$event->heure = $lignes->heureEvent;
		$event->desc = $lignes->description;
		$event->imagePath = $lignes->imagePath;
		$event->nbrParticipantMax = $lignes->nbrParticipantsMax;
		$event->levelMin = $lignes->levelMin;
		$event->levelMax = $lignes->levelMax;
		$event->listRoles = getListRoles($lignes->id);
		$event->listParticipants = getListParticipants($lignes->id);
		
		$listEvent[] = $event;
	}
	
	return $listEvent;
}

// Fonction qui permet de supprimer un événement
function deleteEvent($idEvent) {
	$connexionBDD = getConnexionBDD();
	$connexionBDD->beginTransaction();
	
	// On récupère l'event
	$event = getEventById($idEvent);

	// on créé la requête SQL
	$sqlQuery = $connexionBDD->prepare("DELETE from er_evenement where id = :idEvent");
	$resultat = $sqlQuery->execute(array( 'idEvent' => $idEvent ));
	if($resultat) {
		$connexionBDD->commit();
		// Si l'événement a correctement été supprimé
		// Alors on supprime l'image associé si elle existe
		deleteImage($event->imagePath);
	} else {
		// Erreur on effectue un rollback
		$connexionBDD->rollBack();
	}

	
	return $resultat;
}

// Fonction qui retourne la liste des événements pour une date donnée
function getEventsByDate($date) {
	$listEvent = array();
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT
	id, 
	nom, 
	description, 
	imagePath,
	DATE_FORMAT(e.date, '%d/%m/%Y') AS dateEvent, 
	DATE_FORMAT(e.date, '%H:%i') AS heureEvent,
	idJeu,
	nbrParticipantsMax,
	levelMin,
	levelMax
	FROM er_evenement e
	WHERE DATE_FORMAT(e.date, '%e/%c/%Y') = :dateRecherche");

	$sqlQuery->execute(array( 'dateRecherche' => $date ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ)) {
		$event = new Evenement($lignes->nom, $lignes->dateEvent);
		$event->id = $lignes->id;
		$event->jeu = getJeuById($lignes->idJeu);
		$event->heure = $lignes->heureEvent;
		$event->desc = $lignes->description;
		$event->imagePath = $lignes->imagePath;
		$event->nbrParticipantMax = $lignes->nbrParticipantsMax;
		$event->levelMin = $lignes->levelMin;
		$event->levelMax = $lignes->levelMax;
		$event->listRoles = getListRoles($lignes->id);
		$event->listParticipants = getListParticipants($lignes->id);
		
		$listEvent[] = $event;
	}
	
	return $listEvent;
}

// Fonction qui retourne un événement via son identifiant
function getEventById($id) {
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT
	id, 
	nom, 
	description, 
	imagePath,
	DATE_FORMAT(date, '%d/%m/%Y') AS dateEvent, 
	DATE_FORMAT(date, '%H:%i') AS heureEvent,
	idJeu,
	nbrParticipantsMax,
	levelMin,
	levelMax
	FROM er_evenement e
	WHERE e.id = :idEvent");

	$sqlQuery->execute(array( 'idEvent' => $id ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ)) {
		$event = new Evenement($lignes->nom, $lignes->dateEvent);
		$event->id = $lignes->id;
		$event->jeu = getJeuById($lignes->idJeu);
		$event->heure = $lignes->heureEvent;
		$event->desc = $lignes->description;
		$event->imagePath = $lignes->imagePath;
		$event->nbrParticipantMax = $lignes->nbrParticipantsMax;
		$event->levelMin = $lignes->levelMin;
		$event->levelMax = $lignes->levelMax;
		$event->listRoles = getListRoles($lignes->id);
		$event->listParticipants = getListParticipants($lignes->id);
	
	}
	
	return $event;
}

// Fonction qui récupère les rôles d'un événement
function getListRoles($idEvent) {
	$listRoles = array();
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id, libelle, nbrVoulu from er_role where idEvenement = :idEvent");
	
	$sqlQuery->execute(array( 'idEvent' => $idEvent ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ)) {
		$role = new Role($lignes->id, $lignes->libelle);
		$role->nbrVoulu = $lignes->nbrVoulu; 
		$listRoles[] = $role;
	}
	
	return $listRoles;
}

// Fonction qui enregistre le role donnée
function saveRole($idEvent, $role) {

	$nbrVoulu = null;
	if(isset($role->nbrVoulu) ){
		$nbrVoulu = $role->nbrVoulu;
	}

	$connexionBDD = getConnexionBDD();
	// on créé la requête SQL
	$sqlQuery =  $connexionBDD->prepare("INSERT INTO er_role (id, libelle, nbrVoulu, idEvenement) 
	values (:idRole, :libelleEvent, :nbrVoulu, :idEvent)
	ON DUPLICATE KEY UPDATE 
	libelle = :libelleEvent,
	nbrVoulu = :nbrVoulu");
	
	$resultat = $sqlQuery->execute(array( 
		'idRole' => $role->id, 
		'libelleEvent' => $role->libelle, 
		'nbrVoulu' => $nbrVoulu,
		'idEvent' => $idEvent 
	));
	
	return $resultat;
}

// Fonction qui supprime les rôles associés à un événement
function deleteRoleByIdEvent($idEvent) {
	$connexionBDD = getConnexionBDD();
	// on créé la requête SQL
	$sqlQuery =  $connexionBDD->prepare("DELETE from er_role where idEvenement = :idEvent");
	
	$resultat = $sqlQuery->execute(array( 'idEvent' => $idEvent ));
	return $resultat;
}

// Fonction qui supprime le rôle
function deleteRoleById($idRole) {
	$connexionBDD = getConnexionBDD();
	// on créé la requête SQL
	$sqlQuery =  $connexionBDD->prepare("DELETE from er_role where id = :idRole");
	
	$resultat = $sqlQuery->execute(array( 'idRole' => $idRole ));
	return $resultat;
}

// Fonction qui ramène les participants à un événement
function getListParticipants($idEvent) {
	$listParticipants = array();
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery =  $connexionBDD->prepare("SELECT 
	idPersonnage,
	idUser,
	idRole, r.libelle as libelleRole, r.nbrVoulu as nbrVoulu,
	statut, commentaire
	FROM er_participant p
	LEFT JOIN er_role r ON (r.id = p.idRole)
	WHERE p.idEvenement = :idEvent");
	
	$sqlQuery->execute(array( 'idEvent' => $idEvent ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ)) {
		$user = getUserById($lignes->idUser);
		$perso = getPersonnageById($lignes->idPersonnage);
		$role = new Role($lignes->idRole, $lignes->libelleRole);
		$role->nbrVoulu = $lignes->nbrVoulu;
		$participant = new Participant($perso, RefStatut::getStatut($lignes->statut), $user);
		$participant->role = $role;
		$participant->commentaire = $lignes->commentaire;
		$listParticipants[] = $participant;
	}
	
	return $listParticipants;
}

// Fonction qui met à jour le premier participant inscrit avec le statut en attente au statut disponible
function updatePremierParticipantEnAttente($idEvent) {
	$connexionBDD = getConnexionBDD();
	$sqlQuery =  $connexionBDD->prepare("SELECT 
	idUser
	FROM er_participant p
	WHERE p.idEvenement = :idEvent
	AND p.statut = '".CODE_STATUT_WAIT."' 
	ORDER BY dateInscription ASC LIMIT 1");
	
	$resultat = false;
	
	$sqlQuery->execute(array( 'idEvent' => $idEvent ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ)) {
		$idUser = $lignes->idUser;
	}
	
	if(isset($idUser)) {
		$sqlQuery =  $connexionBDD->prepare("UPDATE er_participant set statut='D' 
		WHERE idUser = :idUser 
		AND idEvenement = :idEvent");
		$resultat = $sqlQuery->execute(array( 'idEvent' => $idEvent, 'idUser' => $idUser ));
	}
	
	return $resultat;
}

// Fonction qui enregistre un événement
function saveEvent($event) {
	
	$idEvent = null;
	if(isset($event->id) ){
		$idEvent = $event->id;
	}
	
	$descEvent = null;
	if(isset($event->desc) ){
		$descEvent = $event->desc;
	}
	
	$imgPathEvent = null;
	if(isset($event->imagePath) ){
		$imgPathEvent = $event->imagePath;
	}

	$nbrMaxPartEvent = null;
	if(isset($event->nbrParticipantMax) ){
		$nbrMaxPartEvent = $event->nbrParticipantMax;
	}
	
	$levelMinEvent = null;
	if(isset($event->levelMin) ){
		$levelMinEvent = $event->levelMin;
	}
	
	$levelMaxEvent = null;
	if(isset($event->levelMax) ){
		$levelMaxEvent = $event->levelMax;
	}
	
	// Transcription de la date et de l'heure	
	$tmp = explode("/", $event->date);
	$date_iso = $tmp[2]."-".$tmp[1]."-".$tmp[0];
	if(isset($event->heure)) {
		$date_iso = $date_iso.' '.$event->heure;
	} else {
		$date_iso = $date_iso.' 00:00:00';
	}
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$connexionBDD->beginTransaction();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_evenement (
	id, 
	nom, 
	description, 
	imagePath,
	date,
	idJeu,
	nbrParticipantsMax,
	levelMin,
	levelMax) 
	values (:idEvent,
	:nomEvent, 
	:descEvent, 
	:imgPathEvent,
	:dateIso ,
	:jeuId,
	:nbrMaxPartEvent,
	:levelMinEvent,
	:levelMaxEvent)
	ON DUPLICATE KEY UPDATE 
	nom = :nomEvent,
	description = :descEvent, 
	imagePath = :imgPathEvent,
	date = :dateIso,
	idJeu = :jeuId,
	nbrParticipantsMax = :nbrMaxPartEvent,
	levelMin = :levelMinEvent,
	levelMax = :levelMaxEvent");
		
	$resultat = $sqlQuery->execute(array( 
			'idEvent' => $idEvent,
			'nomEvent' => $event->nom,
			'descEvent' => $descEvent,
			'imgPathEvent' => $imgPathEvent,
			'dateIso' => $date_iso,
			'jeuId' => $event->jeu->id,
			'nbrMaxPartEvent' => $nbrMaxPartEvent,
			'levelMinEvent' => $levelMinEvent,
			'levelMaxEvent' => $levelMaxEvent
		)
	);
	if($resultat) {
		// on récupère l'identifiant généré
		$idEventTmp = $connexionBDD->lastInsertId();
		// Si on a mis à jour l'événement on a pas de lastInsertId
		if($idEventTmp == 0){
			$idEventTmp = $idEvent;
		}
		
		if(isset($event->listIdRolesASupp)) {
			foreach($event->listIdRolesASupp as $idRoleASupp):
				// On supprime les rôles de l'événement
				$resultat = deleteRoleById($idRoleASupp);
				if(!$resultat) {
					// Erreur on effectue un rollback
					$connexionBDD->rollBack();
					break;
				}
			endforeach;
		}
		
		// On créé les rôles associés à l'événement
		if(isset($event->listRoles)) {
			foreach($event->listRoles as $role):
				$resultat = saveRole($idEventTmp, $role);
				if(!$resultat) {
					// Erreur on effectue un rollback
					$connexionBDD->rollBack();
					break;
				}
			endforeach;
		}
	} else {
		// Erreur on effectue un rollback
		$connexionBDD->rollBack();
	}
	
	// Si tout c'est bien passé on commit
	if($resultat) {
		$connexionBDD->commit();
	}
	
	return $resultat;
}

// Fonction qui permet d'inscrire un participant à un événement
function inscriptionEvent($participant, $idEvent, $idUser) {
	
	$idRole = null;
	if(isset($participant->role) && isset($participant->role->id)){
		$idRole = $participant->role->id;
	}
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_participant (
	idPersonnage,
	idEvenement,
	idUser,
	idRole,
	statut,
	commentaire)
	VALUES (
	:idPersonnage,
	:idEvent,
	:idUser,
	:idRole,
	:codeStatut,
	:commentaire)
	ON DUPLICATE KEY UPDATE 
	idPersonnage = :idPersonnage,
	idRole = :idRole,
	statut = :codeStatut,
	commentaire = :commentaire");
	
	$resultat = $sqlQuery->execute(array( 
			'idPersonnage' => $participant->personnage->id,
			'idEvent' => $idEvent,
			'idUser' => $idUser,
			'idRole' => $idRole,
			'codeStatut' => $participant->statut->code,
			'commentaire' => $participant->commentaire
		)
	);
	
	return $resultat;
}

// Fonction qui permet de désinscrire un utilisateur d'un event
function desinscriptionEvent($idEvent, $idUser) {
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE FROM er_participant 
	WHERE idEvenement = :idEvenement 
	AND idUser = :idUser");
	
	$resultat = $sqlQuery->execute(array( 
			'idEvenement' => $idEvent,
			'idUser' => $idUser
		)
	);
	
	return $resultat;
}

// Supprime tous les participants d'un d'événement
function suppressionParticipantByIdEvent($idEvent) {
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE FROM er_participant 
	WHERE idEvenement = :idEvenement ");
	
	$resultat = $sqlQuery->execute(array('idEvenement' => $idEvent));
	
	return $resultat;
}

// Fonction qui enregistre un template pour un événement
function saveTemplateEvent($event) {
	$idEvent = null;
	if(isset($event->id) ){
		$idEvent = $event->id;
	}
	
	$descEvent = null;
	if(isset($event->desc) ){
		$descEvent = $event->desc;
	}
	
	$imgPathEvent = null;
	if(isset($event->imagePath) ){
		$imgPathEvent = $event->imagePath;
	}

	$nbrMaxPartEvent = null;
	if(isset($event->nbrParticipantMax) ){
		$nbrMaxPartEvent = $event->nbrParticipantMax;
	}
	
	$levelMinEvent = null;
	if(isset($event->levelMin) ){
		$levelMinEvent = $event->levelMin;
	}
	
	$levelMaxEvent = null;
	if(isset($event->levelMax) ){
		$levelMaxEvent = $event->levelMax;
	}
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$connexionBDD->beginTransaction();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_templateevenement (
	id, 
	nom, 
	description, 
	imagePath,
	idJeu,
	nbrParticipantsMax,
	levelMin,
	levelMax) 
	values (:idEvent,
	:nomEvent, 
	:descEvent, 
	:imgPathEvent,
	:jeuId,
	:nbrMaxPartEvent,
	:levelMinEvent,
	:levelMaxEvent)
	ON DUPLICATE KEY UPDATE 
	nom = :nomEvent,
	description = :descEvent, 
	imagePath = :imgPathEvent,
	idJeu = :jeuId,
	nbrParticipantsMax = :nbrMaxPartEvent,
	levelMin = :levelMinEvent,
	levelMax = :levelMaxEvent");
		
	$resultat = $sqlQuery->execute(array( 
			'idEvent' => $idEvent,
			'nomEvent' => $event->nom,
			'descEvent' => $descEvent,
			'imgPathEvent' => $imgPathEvent,
			'jeuId' => $event->jeu->id,
			'nbrMaxPartEvent' => $nbrMaxPartEvent,
			'levelMinEvent' => $levelMinEvent,
			'levelMaxEvent' => $levelMaxEvent
		)
	);
	if($resultat) {
		// on récupère l'identifiant généré
		$idEventTmp = $connexionBDD->lastInsertId();
		// Si on a mis à jour l'événement on a pas de lastInsertId
		if($idEventTmp == 0){
			$idEventTmp = $idEvent;
		}
		
		if(isset($event->listIdRolesASupp)) {
			foreach($event->listIdRolesASupp as $idRoleASupp):
				// On supprime les rôles de l'événement
				$resultat = deleteTemplateRoleById($idRoleASupp);
				if(!$resultat) {
					// Erreur on effectue un rollback
					$connexionBDD->rollBack();
					break;
				}
			endforeach;
		}
		
		// On créé les rôles associés à l'événement
		if(isset($event->listRoles)) {
			foreach($event->listRoles as $role):
				$resultat = saveTemplateRole($idEventTmp, $role);
				if(!$resultat) {
					// Erreur on effectue un rollback
					$connexionBDD->rollBack();
					break;
				}
			endforeach;
		}
	} else {
		// Erreur on effectue un rollback
		$connexionBDD->rollBack();
	}
	
	// Si tout c'est bien passé on commit
	if($resultat) {
		$connexionBDD->commit();
	}
	
	return $resultat;
}

// Fonction qui enregistre le role donnée pour le template
function saveTemplateRole($idTemplate, $role) {
	$nbrVoulu = null;
	if(isset($role->nbrVoulu) ){
		$nbrVoulu = $role->nbrVoulu;
	}

	$connexionBDD = getConnexionBDD();
	// on créé la requête SQL
	$sqlQuery =  $connexionBDD->prepare("INSERT INTO er_templaterole (id, libelle, nbrVoulu, idTemplate) 
	values (:idRole, :libelleEvent, :nbrVoulu, :idTemplate)
	ON DUPLICATE KEY UPDATE 
	libelle = :libelleEvent,
	nbrVoulu = :nbrVoulu");
	
	$resultat = $sqlQuery->execute(array( 
		'idRole' => $role->id, 
		'libelleEvent' => $role->libelle, 
		'nbrVoulu' => $nbrVoulu,
		'idTemplate' => $idTemplate 
	));
	
	return $resultat;
}

// Fonction qui supprime le rôle lié à un template
function deleteTemplateRoleById($idRole) {
	$connexionBDD = getConnexionBDD();
	// on créé la requête SQL
	$sqlQuery =  $connexionBDD->prepare("DELETE from er_templaterole where id = :idRole");
	
	$resultat = $sqlQuery->execute(array( 'idRole' => $idRole ));
	return $resultat;
}

// Fonction qui permet de supprimer un template pour un événement
function deleteTemplateEvent($idEvent) {
	$connexionBDD = getConnexionBDD();
	$connexionBDD->beginTransaction();
	
	// On récupère l'event
	$event = getEventById($idEvent);

	// on créé la requête SQL
	$sqlQuery = $connexionBDD->prepare("DELETE from er_templateevenement where id = :idEvent");
	$resultat = $sqlQuery->execute(array( 'idEvent' => $idEvent ));
	if($resultat) {
		$connexionBDD->commit();
		// Si l'événement a correctement été supprimé
		// Alors on supprime l'image associé si elle existe
		deleteImage($event->imagePath);
	} else {
		// Erreur on effectue un rollback
		$connexionBDD->rollBack();
	}

	
	return $resultat;
}

// Fonction qui retourne l'intégralité des templates d'événements
function getAllTemplatesEvents() {
	$listEvent = array();
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT
	id, 
	nom, 
	description, 
	imagePath,
	idJeu,
	nbrParticipantsMax,
	levelMin,
	levelMax
	FROM er_templateevenement e");

	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$event = new Evenement($lignes->nom, null);
		$event->id = $lignes->id;
		$event->jeu = getJeuById($lignes->idJeu);
		$event->desc = $lignes->description;
		$event->imagePath = $lignes->imagePath;
		$event->nbrParticipantMax = $lignes->nbrParticipantsMax;
		$event->levelMin = $lignes->levelMin;
		$event->levelMax = $lignes->levelMax;
		$event->listRoles = getListRoles($lignes->id);
		$event->listParticipants = getListParticipants($lignes->id);
		
		$listEvent[] = $event;
	}
	
	return $listEvent;
}

// Fonction qui retourne l'intégralité des templates d'événements pour un identifiant de jeu donné
function getAllTemplatesEventsByIdJeu($idUser) {
	$listEvent = array();
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT
	id, 
	nom, 
	description, 
	imagePath,
	e.idJeu,
	nbrParticipantsMax,
	levelMin,
	levelMax
	FROM er_templateevenement e, er_usergestionjeu g
	WHERE e.idJeu = g.idJeu
	AND g.idUser = :idUser");

	$sqlQuery->execute(array( 'idUser' => $idUser ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$event = new Evenement($lignes->nom, null);
		$event->id = $lignes->id;
		$event->jeu = getJeuById($lignes->idJeu);
		$event->desc = $lignes->description;
		$event->imagePath = $lignes->imagePath;
		$event->nbrParticipantMax = $lignes->nbrParticipantsMax;
		$event->levelMin = $lignes->levelMin;
		$event->levelMax = $lignes->levelMax;
		$event->listRoles = getListRoles($lignes->id);
		$event->listParticipants = getListParticipants($lignes->id);
		
		$listEvent[] = $event;
	}
	
	return $listEvent;
}

// Fonction qui retourne un template d'événement via son identifiant
function getTemplateEventById($id) {
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT
	id, 
	nom, 
	description, 
	imagePath,
	idJeu,
	nbrParticipantsMax,
	levelMin,
	levelMax
	FROM er_templateevenement e
	WHERE e.id = :idEvent");

	$sqlQuery->execute(array( 'idEvent' => $id ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ)) {
		$event = new Evenement($lignes->nom, null);
		$event->id = $lignes->id;
		$event->jeu = getJeuById($lignes->idJeu);
		$event->desc = $lignes->description;
		$event->imagePath = $lignes->imagePath;
		$event->nbrParticipantMax = $lignes->nbrParticipantsMax;
		$event->levelMin = $lignes->levelMin;
		$event->levelMax = $lignes->levelMax;
		$event->listRoles = getListTemplateRoles($lignes->id);
	}
	
	return $event;
}

// Fonction qui récupère les rôles d'un template
function getListTemplateRoles($idTemplate) {
	$listRoles = array();
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id, libelle, nbrVoulu from er_templaterole where idTemplate = :idTemplate");
	
	$sqlQuery->execute(array( 'idTemplate' => $idTemplate ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ)) {
		$role = new Role($lignes->id, $lignes->libelle);
		$role->nbrVoulu = $lignes->nbrVoulu; 
		$listRoles[] = $role;
	}
	
	return $listRoles;
}

// Fonction qui met à jour le commentaire de l'inscription
function updateCommentaireInscription($idEvent, $idUser, $commentaire) {
	$connexionBDD = getConnexionBDD();
	// on créé la requête SQL
	$sqlQuery =  $connexionBDD->prepare("UPDATE er_participant set commentaire = :commentaire 
		WHERE idUser = :idUser and idEvenement = :idEvent");
		
	$resultat = $sqlQuery->execute(array( 
		'idUser' => $idUser, 
		'idEvent' => $idEvent, 
		'commentaire' => $commentaire
	));
	
	return $resultat;
}

?>