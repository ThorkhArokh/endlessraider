<?php
// Classe représentant un rôle d'un personnage pour un jeu
    class Role {
        // Identifiant technique
        var $id;
        // Libellé du rôle
        var $libelle;
		// Nombre d'occurences de ce rôle possible lors des inscriptions
		var $nbrVoulu;
		
		//Constructeur
		function Role($idIn, $libelleIn) {
			$this->id = $idIn;
			$this->libelle = $libelleIn;
		}
    }
?>