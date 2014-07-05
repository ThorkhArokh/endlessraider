<?php
// Classe repréentant un personnage créé par un utilisateur pour matérialiser 
// son incription à un évènement
    class Personnage {
        // Identifiant technique
        var $id;
        // Nom du personnage
        var $nom;
		// Niveau du personnage
		var $level;
		// Genre du personnage
		var $genre;
		// Race du personnage
		var $race;
        // Classe du personnage
        var $classe;
        // Jeu auquel est rattaché le personnage
        var $jeu;
        
        // Constructeur
        function Personnage($idIn, $nomIn, $jeuIn) {
            $this->id = $idIn;
            $this->nom = $nomIn;
            $this->jeu = $jeuIn;
        }
		
		// Renvoi le personnage au format JSON
        function getPersoJSON() {
            return json_encode($this);
        }
        
        // Fonction d'enregistrement d'un personnage
        function save() {
            // TODO
        }
        
    }
?>