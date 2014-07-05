<?php
// Classe représentant la classe d'un personnage pour un jeu
    class Classe {
        // Identifiant technique
        var $id;
        // Libellé du rôle
        var $libelle;
		// Icone
		var $iconPath;
		
		//Constructeur
		function Classe($idIn, $libelleIn) {
			$this->id = $idIn;
			$this->libelle = $libelleIn;
		}
    }
?>