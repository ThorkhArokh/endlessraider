<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/services/user/connectUser.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/RoleUser.php');

session_start();

$user = getUserByName('Sieg');

$_SESSION['userConnect'] = $user;

if(isset($_SESSION['userConnect'])) {
	$user = $_SESSION['userConnect'];
	echo "ROLE : ".$user->role;
	echo "ADMIN JEU : ".$user->idJeuAdmin;
	if($user->role == ROLE_EDITOR && isset($user->idJeuAdmin) ) {
		echo "Recherche par jeu";
		echo json_encode(getAllTemplatesEventsByIdJeu($user->idJeuAdmin));
	} else {
		echo "Recherche classique";
		echo json_encode(getAllTemplatesEvents());
	}
} else {
	echo "Perte de session. Veuillez vous reconnecter.";
}
?>