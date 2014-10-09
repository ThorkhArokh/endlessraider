<?php
	$connexion = false;
	
	// Fonction qui créé une connexion à la base de données via l'interface PDO
    function getConnexionBDD()
    {
		global $connexion;
        if( $connexion )
            return $connexion;
	
        $PARAM_hote='mysql5-21.perso'; // le chemin vers le serveur
		$PARAM_port='3306';
		$PARAM_nom_bd='endlessfendl'; // le nom de votre base de données
		$PARAM_utilisateur='endlessfendl'; // nom d'utilisateur pour se connecter
		$PARAM_mot_passe='sAc2010gRe'; // mot de passe de l'utilisateur pour se connecter
		try {
			$connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, 
			$PARAM_utilisateur, $PARAM_mot_passe, 
			array(PDO::ATTR_PERSISTENT => true)); // Cette option permet d'avoir une connexion persistente
			
			return $connexion;
		}
		catch(Exception $e) {
			echo 'Erreur : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
		}
    }
   
	// Fonction qui permet fermer la connexion donnée
    function closeConnexionBDD($connexion)
    {
        $connexion = null;
    }
?>