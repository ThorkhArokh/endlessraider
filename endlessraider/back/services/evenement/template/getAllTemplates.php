<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/RoleUser.php');

session_start();

if(isset($_SESSION['userConnect'])) {
	$user = $_SESSION['userConnect'];
	
	if($user->role->code == ROLE_EDITOR && isset($user->id) ) {
		echo json_encode(getAllTemplatesEventsByIdJeu($user->id));
	} else {
		echo json_encode(getAllTemplatesEvents());
	}
} else {
	echo "Perte de session. Veuillez vous reconnecter.";
}
	
?>