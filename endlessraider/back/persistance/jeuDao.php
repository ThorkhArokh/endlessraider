<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Jeu.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/TypeJeu.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Classe.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Race.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/ImageUtil.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/connectionBDD.php');

// Fonction qui retourne la liste des jeux disponibles
function getListJeux() {
	$listJeux = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT j.id , j.nom, j.iconPath, 
		j.idTypeJeu, t.code as codeTypeJeu, t.libelle as libelleTypeJeu
		FROM er_jeu j, er_typejeu t where j.idTypeJeu = t.id");
		
	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le type du jeu
		$typeJeu = new TypeJeu($lignes->idTypeJeu, $lignes->codeTypeJeu, $lignes->libelleTypeJeu);
		// On créé le jeu associé
		$jeu = new Jeu($lignes->nom, $typeJeu,  $lignes->iconPath); 
		$jeu->id = $lignes->id;
		
		// On récupère les classes associées au jeu
		$jeu->listClasses = getClassesByIdJeu($jeu->id);
		// On récupère les races associées au jeu
		$jeu->listRaces = getRacesByIdJeu($jeu->id);
	
		$listJeux[] = $jeu;
	}
		
	return $listJeux;
}

// Fonction qui retourne la liste des jeux disponibles
function getListJeuxByIdUser($idUser) {
	$listJeux = array();

	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT j.id , j.nom, j.iconPath,
		j.idTypeJeu, t.code as codeTypeJeu, t.libelle as libelleTypeJeu
		FROM er_jeu j, er_typejeu t, er_usergestionjeu g
		WHERE j.idTypeJeu = t.id
		AND j.id = g.idJeu 
		AND g.idUser = :idUser");

	$sqlQuery->execute(array('idUser' => $idUser));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
	{
		// On créé le type du jeu
		$typeJeu = new TypeJeu($lignes->idTypeJeu, $lignes->codeTypeJeu, $lignes->libelleTypeJeu);
		// On créé le jeu associé
		$jeu = new Jeu($lignes->nom, $typeJeu,  $lignes->iconPath);
		$jeu->id = $lignes->id;

		// On récupère les classes associées au jeu
		$jeu->listClasses = getClassesByIdJeu($jeu->id);
		// On récupère les races associées au jeu
		$jeu->listRaces = getRacesByIdJeu($jeu->id);

		$listJeux[] = $jeu;
	}

	return $listJeux;
}

// Fonction qui retourne le jeu correspondant à l'identifiant donné
function getJeuById($idJeu) {
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT j.id , j.nom, j.iconPath, 
		j.idTypeJeu, t.code as codeTypeJeu, t.libelle as libelleTypeJeu
		FROM er_jeu j, er_typejeu t 
		WHERE j.id = :idJeu 
		AND j.idTypeJeu = t.id");
	
	$sqlQuery->execute(array('idJeu' => $idJeu));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le type du jeu
		$typeJeu = new TypeJeu($lignes->idTypeJeu, $lignes->codeTypeJeu, $lignes->libelleTypeJeu);
		// On créé le jeu associé
		$jeu = new Jeu($lignes->nom, $typeJeu,  $lignes->iconPath); 
		$jeu->id = $lignes->id;
		
		// On récupère les classes associées au jeu
		$jeu->listClasses = getClassesByIdJeu($idJeu);
		// On récupère les races associées au jeu
		$jeu->listRaces = getRacesByIdJeu($idJeu);
	}

	return $jeu;
}

// Fonction qui récupère tous les types de jeu possible
function getAllTypesJeu() {
	$listTypeJeu = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id, code, libelle
		FROM er_typejeu");
		
	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le type du jeu
		$typeJeu = new TypeJeu($lignes->id, $lignes->code, $lignes->libelle);
		$listTypeJeu[] = $typeJeu;
	}
	
	return $listTypeJeu;
}

// Fonction qui récupère les classes pour un jeu donné
function getClassesByIdJeu($idJeu) {
	$listClasses = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id , libelle, iconPath
		from er_classe where idJeu = :idJeu");
	
	$sqlQuery->execute(array('idJeu' => $idJeu));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le jeu associé
		$classe = new Classe($lignes->id, $lignes->libelle); 
		$classe->iconPath = $lignes->iconPath;
		$listClasses[] = $classe;
	}
	
	return $listClasses;
}

// Fonction qui récupère les races pour un jeu donné
function getRacesByIdJeu($idJeu) {
	$listRaces = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id , libelle, iconPath
		from er_race where idJeu = :idJeu");
	
	$sqlQuery->execute(array('idJeu' => $idJeu));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le jeu associé
		$race = new Race($lignes->id, $lignes->libelle); 
		$race->iconPath = $lignes->iconPath;
		$listRaces[] = $race;
	}
	
	return $listRaces;
}

// Fonction qui enregistre le jeu donné
function saveJeu($jeu) {
	
	$idJeu = null;
	if(isset($jeu->id) ){
		$idJeu = $jeu->id;
	}

	$typeJeu = null;
	if(isset($jeu->type)) {
		$typeJeu = $jeu->type->id;
	}
	$iconPathJeu = null;
	if(isset($jeu->iconPath)) {
		$iconPathJeu = $jeu->iconPath;
	}

	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_jeu (id, nom, iconPath, idTypeJeu) 
	values (:idJeu, 
	:nomJeu, 
	:iconPathJeu,
	:typeJeu) 
	ON DUPLICATE KEY UPDATE 
	nom = :nomJeu,
	idTypeJeu = :typeJeu,
	iconPath = :iconPathJeu");
	
	$resultat = $sqlQuery->execute(array(
		'idJeu' => $idJeu,
		'nomJeu' => $jeu->nom,
		'typeJeu' => $typeJeu,
		'iconPathJeu' => $iconPathJeu
	));

	if($resultat) {
		$idJeuTmp = $connexionBDD->lastInsertId();
		if($idJeuTmp == 0){
			$idJeuTmp = $idJeu;
		} else {
			// Pour un nouveau jeu on enregistre les droits pour les utilisateurs admin automatiquement
			$resultat = saveAllAdminUserAdminJeu($idJeuTmp);
			if(!$resultat) {
				throw new Exception("Erreur lors de l'attribution des droits aux administrateurs.");
			}
		}
		
		if(isset($jeu->listeIdClasseSupp)) {
			foreach($jeu->listeIdClasseSupp as $idClasseASupp):
				// On supprime les classes du jeu sélectionnées
				$resultat = deleteClasseById($idClasseASupp);
				if(!$resultat) {
					throw new Exception("Erreur lors de la suppression de la classe : ".$idClasseASupp);
				}
			endforeach;
		}
		
		if(isset($jeu->listClasses)) {
			foreach($jeu->listClasses as $classe):
				$resultat = saveClasse($idJeuTmp, $classe);
				if(!$resultat) {
					throw new Exception("Erreur lors de l'enregistrement de la classe : ".$classe->libelle);
				}
			endforeach;
		}
		
		if(isset($jeu->listeIdRaceSupp)) {
			foreach($jeu->listeIdRaceSupp as $idRaceASupp):
				// On supprime les races du jeu sélectionnées
				$resultat = deleteRaceById($idRaceASupp);
				if(!$resultat) {
					throw new Exception("Erreur lors de la suppression de la race : ".$idRaceASupp);
				}
			endforeach;
		}
		
		if(isset($jeu->listRaces)) {
			foreach($jeu->listRaces as $race):
				$resultat = saveRace($idJeuTmp, $race);
				if(!$resultat) {
					throw new Exception("Erreur lors de l'enregistrement de la race : ".$race->libelle);
				}
			endforeach;
		}
	}
	
	return $idJeuTmp;
}

// Fonction qui enregistre la race donnée
function saveRace($idJeu, $race) {
	$idRace = null;
	if(isset($race->id) ){
		$idRace = $race->id;
	}
	
	$iconPath = null;
	if(isset($race->iconPath)) {
		$iconPath = $race->iconPath;
	}

	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_race (id, libelle, idJeu, iconPath) 
	values (:idRace, :libelleRace, :idJeu, :iconPath)
	ON DUPLICATE KEY UPDATE 
	libelle = :libelleRace,
	iconPath = :iconPath");
	
	$resultat = $sqlQuery->execute(array(
		'idRace' => $idRace,
		'libelleRace' => $race->libelle,
		'idJeu' => $idJeu,
		'iconPath' => $iconPath
	));
	return $resultat;
}

// Fonction qui enregistre la classe donnée
function saveClasse($idJeu, $classe) {
	$idClasse = null;
	if(isset($classe->id) ){
		$idClasse = $classe->id;
	}
	
	$iconPath = null;
	if(isset($classe->iconPath)) {
		$iconPath = $classe->iconPath;
	}
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_classe (id, libelle, idJeu, iconPath) 
	values (:idClasse, :libelleClasse, :idJeu, :iconPath)
	ON DUPLICATE KEY UPDATE 
	libelle = :libelleClasse,
	iconPath = :iconPath");
	
	$resultat = $sqlQuery->execute(array(
		'idClasse' => $idClasse,
		'libelleClasse' => $classe->libelle,
		'idJeu' => $idJeu,
		'iconPath' => $iconPath
	));
	
	return $resultat;
}

// Fonction qui supprime un jeu ayant l'identifiant donné
function deleteJeu($idJeu){
	// On récupère le jeu à supprimer
	$jeu = getJeuById($idJeu);
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE from er_jeu where id = :idJeu");
	
	$resultat = $sqlQuery->execute(array('idJeu' => $idJeu));
	
	// Si le jeu a correctement été supprimé
	if($resultat) {
		// Alors on supprime l'image associé si elle existe
		deleteImage($jeu->iconPath);
	}
	
	return $resultat;
}

// Fonction qui supprime les races d'un jeu
function deleteRaceByIdJeu($idJeu){
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE from er_race where idJeu = :idJeu");
	
	$resultat = $sqlQuery->execute(array('idJeu' => $idJeu));
	
	return $resultat;
}

// Fonction qui supprime les classes d'un jeu
function deleteClasseByIdJeu($idJeu){
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE from er_classe where idJeu = :idJeu");
	
	$resultat = $sqlQuery->execute(array('idJeu' => $idJeu));
	
	return $resultat;
}

// Fonction qui supprime une classes via son identifiant
function deleteClasseById($id){
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE from er_classe where id = :id");
	
	$resultat = $sqlQuery->execute(array('id' => $id));
	
	return $resultat;
}

// Fonction qui supprime une race via son identifiant
function deleteRaceById($id){
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE from er_race where id = :id");
	
	$resultat = $sqlQuery->execute(array('id' => $id));
	
	return $resultat;
}

?>