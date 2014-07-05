<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/RoleUser.php');

$res = deleteUserAdminJeu(3, 2);

echo "resultat :".$res;

?>