<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Jeu.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/jeuDao.php');

if(isset($_GET['idJeu'])) {	
	$jeu = getJeuById($_GET['idJeu']);
	echo $jeu->getJeuJSON();
} else {
	echo "Aucun jeu trouvé";
}

?>