<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Evenement.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');


$evt = getEventById(5);

echo "event ".$evt->nom." : ".$evt->isEventPasse()."<br/>";

$evt2 = getEventById(10);

echo "event ".$evt2->nom." : ".$evt2->isEventPasse()."<br/>";

$evt3 = getEventById(9);

echo "event ".$evt3->nom." : ".$evt3->isEventPasse()."<br/>";

$evt4 = getEventById(11);

echo "event ".$evt4->nom." : ".$evt4->isEventPasse()."<br/>";


$evt5 = getEventById(12);

echo "event ".$evt5->nom." : ".$evt5->isEventPasse()."<br/>";
?>