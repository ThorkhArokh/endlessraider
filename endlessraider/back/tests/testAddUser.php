<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/connectionBDD.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/User.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/userDao.php');

$res = getInfosUserEndless(353);
echo "res ".$res;

addUserWithId(353);


?>