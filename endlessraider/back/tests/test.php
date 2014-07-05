<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/connectionBDD.php');

$date = strtotime('2014-06-04');
$y = date('Y', $date);
$m = date('n', $date);
$d = date('j', $date);

$dateSearch = $d."/".$m."/".$y;
echo $dateSearch;
echo "<br/>";
$sql = "SELECT 
	DATE_FORMAT(date, '%d/%m/%Y') AS date, 
	DATE_FORMAT(date, '%H:%i') AS heure
	FROM er_evenement e
	WHERE DATE_FORMAT(e.date, '%e/%c/%Y') = '".$dateSearch."'";
echo $sql;
echo "<br/>";
$req = mysql_query($sql , getConnexionBDD()) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	while($data = mysql_fetch_assoc($req))
    {
		echo "date : ".$data['date']." heure : ".$data['heure']."<br/>";
	}


$heure = '10:00';

$tmp = explode("/", '01/12/2014');
$date_iso = $tmp[2]."-".$tmp[1]."-".$tmp[0];
if(isset($heure)) {
	$date_iso = $date_iso.' '.$heure;
}

echo $date_iso;

closeConnexionBDD();

echo "<br/>";
try {
	echo "je tente quelque chose";
	echo "<br/>";
	throw new Exception(htmlentities("J'ai levé une exception"), 10);
	
} catch (Exception $e) {
	echo "<br/>";
	echo "je catch l'exception : ".$e->getMessage()."(".$e->getCode().")";
}
echo "<br/>";
echo "<br/>";
echo "TEST CREATION CONNEXION BDD AVEC PDO";
echo "<br/>";

$PARAM_hote='localhost'; // le chemin vers le serveur
$PARAM_port='3306';
$PARAM_nom_bd='endlessraider'; // le nom de votre base de données
$PARAM_utilisateur='root'; // nom d'utilisateur pour se connecter
$PARAM_mot_passe=''; // mot de passe de l'utilisateur pour se connecter
try {
	$connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe, array(PDO::ATTR_PERSISTENT => true));
}
catch(Exception $e) {
	echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'N° : '.$e->getCode();
}
$resultats=$connexion->query("SELECT login FROM er_user"); 
$resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
while( $ligne = $resultats->fetch() ) // on récupère la liste des membres
{
        echo 'Utilisateur : '.$ligne->login.'<br />'; // on affiche les membres
}
$resultats->closeCursor();
?>