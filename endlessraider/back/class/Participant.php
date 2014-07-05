<?php
	// Classe représentant un participant à un événement
    class Participant {
		// Personnage avec lequel il s'est inscrit
		var $personnage;
		// Utilisateur ayant inscrit le personnage
		var $user;
		// Statut de son inscription (disponible, indisponible...)
		var $statut;
		// Role du personnage pour l'évent(healeur, cac etc...)
		var $role;
		// Commentaire
		var $commentaire;
		
		// Constructeur
        function Participant($personnageIn, $statutIn, $userIn) {
			$this->personnage = $personnageIn;
			$this->statut = $statutIn;
			$this->user = $userIn;
		}
	}
?>