<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/RoleUser.php');

	echo json_encode(RefRoleUser::getAllRoleUser());

?>