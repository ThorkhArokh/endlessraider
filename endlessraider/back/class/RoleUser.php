<?php

define("ROLE_ADMIN", "admin");
define("ROLE_EDITOR", "officier");
define("ROLE_MEMBRE", "membre");

	// Classe abstraite représentant le référentiel des rôles utilisateurs
	abstract class RefRoleUser {
		// Liste des statuts possible
		static $ROLES = null;
		
		// Fonction qui retourne tous les statuts possibles
		static function getAllRoleUser() {
			return array_values(self::ROLES());
		}
		
		// Gestion du singleton
		public static function ROLES() {
            if (self::$ROLES == null) {
               self::$ROLES = array( ROLE_ADMIN=>new RoleUser('admin', "Administrateur"),
					ROLE_EDITOR=>new RoleUser('officier', "Officier"),
					ROLE_MEMBRE=>new RoleUser('membre', "Membre")
				);
            }
            return self::$ROLES;
        }
		
		// Fonction qui retourne un statut pour un code donné
		static function getRole($code) {
			$listRoles = self::ROLES();
			return $listRoles[$code];
		}
	}
	
	// Classe représentant un rôle utilisateur
	class RoleUser {
		// code technique du rôle
		var $code;
		// libellé du rôle
		var $libelle;
	
		// Constructeur
		function RoleUser($codeIn, $libelleIn) {
			$this->code = $codeIn;
			$this->libelle = $libelleIn;
		}
	}
?>