<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Personnage.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/RoleUser.php');

session_start();

if(isset($_SESSION['userConnect'])) {
	$user = $_SESSION['userConnect'];
	
	if($user->role->code == ROLE_EDITOR && isset($user->id) ) {
		echo json_encode(getAllPersosIdJeu($user->id));
	} else {
		echo json_encode(getAllPersos());
	}
} else {
	echo "Perte de session. Veuillez vous reconnecter.";
}
?>