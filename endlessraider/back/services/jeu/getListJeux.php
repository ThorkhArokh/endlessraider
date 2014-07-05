<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Jeu.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/jeuDao.php');

	echo json_encode(getListJeux());
?>