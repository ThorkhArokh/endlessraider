<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/User.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');
	
	session_start();
	
	$data = array();
	$data['success'] = false;
	$data['message'] = "";
	
	try {
		$userEndless = getUserId();
	}  catch (Exception $e) {
		$data['message'] = $e;
	}
	
	if ($userEndless > 1) {
		try {
			// On récupère l'utisateur via son id
			$user = getUserById($userEndless);
			if($user != null) {
				$_SESSION['userConnect'] = $user;
				$data['user'] = $user ;
				$data['success'] = true;
			} else {
				$login = getInfosUserEndless($userEndless);
				$user = getUserByName($login);
				if($user != null) {
					$_SESSION['userConnect'] = $user;
					$data['user'] = $user ;
					$data['success'] = true;
				} else {
					addUserWithId($userEndless);
					$user = getUserById($userEndless);
					if($user != null) {
						$_SESSION['userConnect'] = $user;
						$data['user'] = $user ;
						$data['success'] = true;
					} else {
						$data['success'] = false;
						$data['message'] = "Erreur lors de la récupération de la session.";
					}
				}
			}
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = $e->getMessage();
		}
	} else {
		$data['success'] = false;
		$data['message'] = "Session Expirée, veuillez vous reconnecter.";
	}
	
	echo json_encode($data);

?>