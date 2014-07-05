<?php
	require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/User.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');
	
	session_start();
	
	// TODO : gérer les cas d'erreur
	if(isset($_SESSION['userConnect'])) {
		$user = $_SESSION['userConnect'];
		try {		
			$user = getUserById($user->id);
			echo $user->getUserJSON();
		} catch (Exception $e) {
			echo "Erreur lors de la récupération de l'utilisateur";
		}
	}
	
?>