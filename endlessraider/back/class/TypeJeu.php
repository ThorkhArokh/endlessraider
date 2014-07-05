<?php
// Classe représentant un type d'un jeu
    class TypeJeu {
        // Identifiant technique
        var $id;
		// Code fonctionnel du type
		var $code;
        // Libellé du type
        var $libelle;
		
		//Constructeur
		function TypeJeu($idIn, $codeIn, $libelleIn) {
			$this->id = $idIn;
			$this->code = $codeIn;
			$this->libelle = $libelleIn;
		}
    }
?>