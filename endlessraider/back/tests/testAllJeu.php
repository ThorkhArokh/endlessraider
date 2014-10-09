<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/services/user/connectUser.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/RoleUser.php');

session_start();

$user = getUserByName('Sieg');

$_SESSION['userConnect'] = $user;

if(isset($_SESSION['userConnect'])) {
	$user = $_SESSION['userConnect'];
	echo "ROLE : ".$user->role->code;
	if($user->role->code == ROLE_EDITOR && isset($user->id) ) {
		echo "Recherche par user";
		echo json_encode(getListJeuxByIdUser($user->id));
	} else {
		echo "Recherche classique";
		echo json_encode(getListJeux());
	}
} else {
	echo "Perte de session. Veuillez vous reconnecter.";
}
?>
