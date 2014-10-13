<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/connectionBDD.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/jeuDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/User.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Jeu.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Race.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Classe.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Personnage.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/RoleUser.php');

// Fonction qui ramène un utilisateur selon son login
function getUserByName($nom) {
	
	try {
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id, login, droit from er_user where login = :nomUser");
	
	$sqlQuery->execute(array('nomUser' => $nom));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$user = new User($lignes->id, $lignes->login, $lignes->droit);
		// On récupère les personnages de l'utilisateur
		$user->persos = getPersonnagesByUserId($user->id);
    } 

	return $user;
	}  catch (Exception $e) {
		throw $e;
	}
}

// Fonction qui retourne un utilisateur complet selon son identifiant
function getUserById($id) {
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id, login, droit from er_user where id = :idUser");
	$sqlQuery->execute(array('idUser' => $id));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$user = new User($lignes->id, $lignes->login, $lignes->droit);
		// On récupère les personnages de l'utilisateur
		$user->persos = getPersonnagesByUserId($user->id);
    } 
	
	return $user;
}

// fonction qui récupère tous les utilisateurs
function getAllUsers() {
	$listeUsers = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id, login, droit FROM er_user");
	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$user = new User($lignes->id, $lignes->login, $lignes->droit);
		// On récupère la liste de jeux sur lesquels l'utilisateur a des droits d'administration
		$user->listeJeuAdmin = getUserJeuAdmin($lignes->id);
		
		// On ajoute l'utilisateur à la liste
		$listeUsers[] = $user;
    } 
	
	return $listeUsers;
}

// Fonction qui retourne la liste des jeux sur lesquels l'utilisateur donné à un rôle d'administrateur
function getUserJeuAdmin($idUser) {
	$listeJeux = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT idJeu FROM er_usergestionjeu WHERE idUser = :idUser");
	$sqlQuery->execute(array('idUser' => $idUser));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ)) {
		// On récupère le jeu et on l'ajoute à la liste
		$listeJeux[] = getJeuById($lignes->idJeu);
    } 
	
	return $listeJeux;
}

// fonction qui récupère tous les personnages
function getAllPersos() {
	$listePersos = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT p.id, p.nom, p.level, p.genre, r.id as idRace, r.libelle as libelleRace, 
		c.id as idClasse, c.libelle as libelleClasse, p.idJeu
		FROM er_personnage p 
		LEFT JOIN er_race r ON (p.idRace = r.id) 
		LEFT JOIN er_classe c ON (p.idClasse = c.id)");
		
	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le jeu associé
		$jeu = getJeuById($lignes->idJeu);
		$race = new Race($lignes->idRace, $lignes->libelleRace);
		$classe = new Classe($lignes->idClasse, $lignes->libelleClasse);
		$perso = new Personnage($lignes->id, $lignes->nom, $jeu);
		$perso->level = $lignes->level;
		$perso->genre = $lignes->genre;
		$perso->race = $race;
		$perso->classe = $classe;
		
		// On ajoute le personnage à la liste
		$listePersos[] = $perso;
    } 
	
	return $listePersos;
}

// fonction qui récupère tous les personnages
function getAllPersosIdJeu($idUser) {
	$listePersos = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT p.id, p.nom, p.level, p.genre, r.id as idRace, r.libelle as libelleRace, 
		c.id as idClasse, c.libelle as libelleClasse, p.idJeu
		FROM er_personnage p
		LEFT JOIN er_race r ON (p.idRace = r.id) 
		LEFT JOIN er_classe c ON (p.idClasse = c.id), er_usergestionjeu g
		WHERE g.idUser = :idUser
		AND g.idJeu = p.idJeu");
		
	$sqlQuery->execute(array('idUser' => $idUser));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le jeu associé
		$jeu = getJeuById($lignes->idJeu);
		$race = new Race($lignes->idRace, $lignes->libelleRace);
		$classe = new Classe($lignes->idClasse, $lignes->libelleClasse);
		$perso = new Personnage($lignes->id, $lignes->nom, $jeu);
		$perso->level = $lignes->level;
		$perso->genre = $lignes->genre;
		$perso->race = $race;
		$perso->classe = $classe;
		
		// On ajoute le personnage à la liste
		$listePersos[] = $perso;
    } 
	
	return $listePersos;
}

// Fonction qui récupère les personnages d'un utilisateur en fonction de son identifiant
function getPersonnagesByUserId($idUser) {
	$listePersos = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT p.id, p.nom, p.level, p.genre, r.id as idRace, 
		r.libelle as libelleRace, r.iconPath as iconPathRace,
		c.id as idClasse, c.libelle as libelleClasse, c.iconPath as iconPathClasse, 
		p.idJeu
		from er_personnage p LEFT JOIN er_race r ON (p.idRace = r.id) LEFT JOIN er_classe c ON (p.idClasse = c.id)
		where idUser = :idUser");
		
	$sqlQuery->execute(array('idUser' => $idUser));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le jeu associé
		$jeu = getJeuById($lignes->idJeu);
		$race = new Race($lignes->idRace, $lignes->libelleRace);
		$race->iconPath = $lignes->iconPathRace;
		$classe = new Classe($lignes->idClasse, $lignes->libelleClasse);
		$classe->iconPath = $lignes->iconPathClasse;
		$perso = new Personnage($lignes->id, $lignes->nom, $jeu);
		$perso->level = $lignes->level;
		$perso->genre = $lignes->genre;
		$perso->race = $race;
		$perso->classe = $classe;
		
		// On ajoute le personnage à la liste
		$listePersos[] = $perso;
    } 
	
	return $listePersos;
}

// Fonction qui recherche un personnage via son identifiant
function getPersonnageById($idPerso) {
	$perso = null;
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT p.id, p.nom, p.level, p.genre, 
		r.id as idRace, r.libelle as libelleRace, r.iconPath as iconPathRace,
		c.id as idClasse, c.libelle as libelleClasse, c.iconPath as iconPathClasse, 
		p.idJeu
		FROM er_personnage p 
		LEFT JOIN er_race r ON (p.idRace = r.id) 
		LEFT JOIN er_classe c ON (p.idClasse = c.id)
		WHERE p.id = :idPerso");
	
	$sqlQuery->execute(array('idPerso' => $idPerso));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le jeu associé
		$jeu = getJeuById($lignes->idJeu);
		$race = new Race($lignes->idRace, $lignes->libelleRace);
		$race->iconPath = $lignes->iconPathRace;
		$classe = new Classe($lignes->idClasse, $lignes->libelleClasse);
		$classe->iconPath = $lignes->iconPathClasse;
		$perso = new Personnage($lignes->id, $lignes->nom, $jeu);
		$perso->level = $lignes->level;
		$perso->genre = $lignes->genre;
		$perso->race = $race;
		$perso->classe = $classe;
	}
	
	return $perso;
}

// Fonction qui enregitre un personnage pour un utilisateur
function savePersonnage($perso, $idUser) {

	$idPerso = null;
	if(isset($perso->id) ){
		$idPerso = $perso->id;
	}

	$levelPerso = null;
	if(isset($perso->level) && $perso->level != '') {
		$levelPerso = $perso->level;
	}
	
	$genrePerso = null;
	if(isset($perso->genre)) {
		$genrePerso = $perso->genre;
	}
	
	$idRacePerso = null;
	if(isset($perso->race) && isset($perso->race->id)) {
		$idRacePerso = $perso->race->id;
	}
	
	$idClassePerso = null;
	if(isset($perso->classe) && isset($perso->classe->id)) {
		$idClassePerso = $perso->classe->id;
	}
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_personnage (id, nom,
	level,	
	genre,
	idRace,
	idClasse,
	idJeu,
	idUser)
	values (:idPerso, 
	:nomPerso,
	:levelPerso, 
	:genrePerso, 
	:idRacePerso, 
	:idClassePerso,
	:jeuId, 
	:idUser) ON DUPLICATE KEY UPDATE 
	nom = :nomPerso,
	level = :levelPerso,
	genre = :genrePerso,
	idRace = :idRacePerso,
	idClasse = :idClassePerso,
	idJeu = :jeuId");
	
	$resultat = $sqlQuery->execute(array(
		'idPerso' => $idPerso,
		'nomPerso' => $perso->nom,
		'levelPerso' => $levelPerso,
		'genrePerso' => $genrePerso,
		'idRacePerso' => $idRacePerso,
		'idClassePerso' => $idClassePerso,
		'jeuId' => $perso->jeu->id,
		'idUser' => $idUser
	));
	
	return $resultat;
}

// Fonction qui supprime un personnage via son identifiant
function deletePersoById($idPerso){
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE from er_personnage where id = :idPerso");
	
	$resultat = $sqlQuery->execute(array('idPerso' => $idPerso));
	
	return $resultat;
}

// Fonction qui définie un utilisateur comme étant l'administrateur d'un jeu
function saveUserAdminJeu($idUser, $idJeu) {
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_usergestionjeu (idUser, idJeu) 
	VALUES (:idUser, :idJeu)");
	
	$resultat = $sqlQuery->execute(array('idUser' => $idUser, 'idJeu' => $idJeu));
	
	return $resultat;
}

// Fonction qui donne les droits de gestion sur un jeu à tous les utilisateurs ayant un rôle ADMIN
function saveAllAdminUserAdminJeu($idJeu) {
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id FROM er_user WHERE droit = :droitAdmin");
		
	$sqlQuery->execute(array('droitAdmin' => ROLE_ADMIN));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$resultat = saveUserAdminJeu($lignes->id, $idJeu);
    } 
	
	return $resultat;
}

// Fonction qui supprime un utilisateur comme étant l'administrateur d'un jeu
function deleteUserAdminJeu($idUser, $idJeu) {
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE FROM er_usergestionjeu WHERE idUser = :idUser AND idJeu = :idJeu");

	$resultat = $sqlQuery->execute(array('idUser' => $idUser, 'idJeu' => $idJeu));
	
	return $resultat;
}

// Fonction qui supprime un utilisateur comme étant l'administrateur de tous les jeux auquels il est rattaché
function deleteUserAllDroitsAdminJeu($idUser) {
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE FROM er_usergestionjeu WHERE idUser = :idUser");

	$resultat = $sqlQuery->execute(array('idUser' => $idUser));
	
	return $resultat;
}

function updateRoleUser($role, $idUser) {
	$resultat = true;
	// Si le rôle est changé vers MEMBRE
	if($role == ROLE_MEMBRE) {
		// Alors on supprime tous les droits d'administration sur tous les jeux rattachés à cet utilisateur*
		$resultat = deleteUserAllDroitsAdminJeu($idUser);
	}

	if($resultat) {
		// on créé la requête SQL
		$connexionBDD = getConnexionBDD();
		$sqlQuery = $connexionBDD->prepare("UPDATE er_user set droit = :role where id = :idUser");

		$resultat = $sqlQuery->execute(array('idUser' => $idUser, 'role' => $role));
	}
	
	return $resultat;
}

?>