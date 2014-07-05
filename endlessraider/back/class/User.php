<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/RoleUser.php');

    // Classe représentant un utilisateur du raider
    class User {
        var $id;
        var $login;
        var $role;
		
		//Liste des jeux sur lesquels l'utilisateur à le rôle d'administrateur
		var $listeJeuAdmin = array();
		
		// Liste des personnages de l'utilisateur
		var $persos = array();
        
        // Constructeur
        function User($idIn, $loginIn, $roleIn) {
            $this->id = $idIn;
            $this->login = $loginIn;
            $this->role = RefRoleUser::getRole($roleIn);
        }
		
		// Renvoi l'événement au format JSON
        function getUserJSON() {
            return json_encode($this);
        }
        
		// Ajoute un personnage à la liste des personnages de l'utilisateur
		function addPersonnage($personnage) {
			$this->persos[] = $personnage;
		}
    }
?>