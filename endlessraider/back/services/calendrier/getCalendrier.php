<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Calendrier.php');

$year = date('Y');
$mois = date('n');
if(isset($_GET['annee'])) {
	$year = $_GET['annee'];
}
if(isset($_GET['mois'])) {
	$mois = $_GET['mois'];
}
$calendrier = new Calendrier($year);
echo $calendrier->getCalendrierJSONPourUnMois($year, $mois);

?>