<?php
	define("CODE_STATUT_DISPO", "D");
	define("CODE_STATUT_INDISPO", "I");
	define("CODE_STATUT_WAIT", "W");
	define("CODE_STATUT_MAYBE", "M");
	
	// Classe abstraite représentant le référentiel des statuts
	abstract class RefStatut {
		// Liste des statuts possible
		static $STATUTS = null;
		
		// Fonction qui retourne tous les statuts possibles
		static function getAllStatut() {
			return array_values(self::STATUTS());
		}
		
		// Gestion du singleton
		public static function STATUTS() {
            if (self::$STATUTS == null) {
               self::$STATUTS = array( 'D'=>new Statut('D', "Disponible"),
					'I'=>new Statut('I', "Non disponible"),
					'M'=>new Statut('M', "Peut être"),
					'W'=>new Statut('W', "En attente")
				);
            }
            return self::$STATUTS;
        }
		
		// Fonction qui retourne un statut pour un code donné
		static function getStatut($code) {
			$listStatut = self::STATUTS();
			return $listStatut[$code];
		}
	}
	
	// Classe représentant un statut d'inscription
	class Statut {
		// code technique du statut
		var $code;
		// libellé du statut
		var $libelle;
	
		// Constructeur
		function Statut($codeIn, $libelleIn) {
			$this->code = $codeIn;
			$this->libelle = $libelleIn;
		}
	}
?>