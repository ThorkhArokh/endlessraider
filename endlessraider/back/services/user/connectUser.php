<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/User.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');
	
	session_start();
	
	$data = array();
	$data['success'] = false;
	$data['message'] = "";
	
	if(isset($_POST["credentials"])) {
		$credentials = $_POST["credentials"];
		$username = $credentials['username'];
		$upswd = $credentials['password'];
	
		// TODO : mettre ici la gestion du mot de passe
		
		if(isset($username)) {
			try {
				// On récupère l'utisateur via son login
				$user = getUserByName($username);
				if($user != null) {
					$_SESSION['userConnect'] = $user;
					$data['user'] = $user ;
					$data['success'] = true;
				} else {
					$data['message'] = "Login ou password incorrect. (login : ".$username;
				}
			} catch (Exception $e) {
				$resultat['success'] = false;
				$resultat['message'] = $e->getMessage();
			}
		} else {
			$data['message'] = "Veuillez saisir un login.";
		}
	}
	
	echo json_encode($data);

?>