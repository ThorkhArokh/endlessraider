<?php
	// Classe représentant une race d'un personnage pour un jeu
    class Race {
        // Identifiant technique
        var $id;
        // Libellé de la race
        var $libelle;
		// Icone
		var $iconPath;
		
		//Constructeur
		function Race($idIn, $libelleIn) {
			$this->id = $idIn;
			$this->libelle = $libelleIn;
		}
    }
?>