<?php
// Classe représentant un jeu
    class Jeu {
        //Identifiant technique
        var $id;
        // Nom du jeu
        var $nom;
        // Type du jeu;
        var $type;
        // Chemin de l'icône du jeu
        var $iconPath;
		
		// Liste des classes disponibles
		var $listClasses = array();
		// Liste des races disponibles
		var $listRaces = array();
        
        // Constructeur
        function Jeu ($nomIn, $typeIn, $iconPathIn) {
            $this->nom = $nomIn;
            $this->type = $typeIn;
            $this->iconPath = $iconPathIn;
        }
        
        // Fonction qui permet d'enregistrer le jeu
        function save() {
            // TODO
        }
        
		// Renvoi le jeu au format JSON
        function getJeuJSON() {
            return json_encode($this);
        }
    }
?>