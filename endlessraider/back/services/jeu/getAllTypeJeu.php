<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/jeuDao.php');

	echo json_encode(getAllTypesJeu());
?>