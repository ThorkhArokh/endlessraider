<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');
	
	if(isset($_GET['idPerso'])) {
		$perso = getPersonnageById($_GET['idPerso']);
		echo $perso->getPersoJSON();
	}
?>