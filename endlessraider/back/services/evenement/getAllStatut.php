<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/StatutParticipant.php');

	echo json_encode(RefStatut::getAllStatut());

?>