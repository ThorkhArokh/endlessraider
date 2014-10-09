<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/StatutParticipant.php');

// Classe représentant un evenement
    class Evenement {
        // Identifiant technique
        var $id;
        // Nom de l'événement
        var $nom;
        // Description de l'événement
        var $desc;
        // Chemin de l'image
        var $imagePath;
        // Date de l'événement
        var $date;
        // Heure de début de l'événement
        var $heure;
        // Jeu associé
        var $jeu;
        // Limite du nombre de participants
        var $nbrParticipantMax;
        // Level minimum pour participer à l'événement
        var $levelMin;
        // Level maximum pour participer à l'événement
        var $levelMax;
        // Liste des participants
        var $listParticipants;
        // Liste des rôles désirés lors de l'événement
        var $listRoles;
		// Indicateur pour déterminer si l'événement est passé ou non
		var $isEventPasse;
        
        function Evenement ($nomIn, $dateIn) {
            $this->nom = $nomIn;
            $this->date = $dateIn;
			
			$this->isEventPasse();
        }
        
		// Renvoi l'événement au format JSON
        function getEventJSON() {
            return json_encode($this);
        }
        
		// Fonction qui vérifie si un utilisateur est inscrit à l'événement
		function isUserInscritEnParticipant($userIn) {
			$isInscrit = false;
			
			foreach($this->listParticipants as $participantTmp):
				if($userIn->id == $participantTmp->user->id) {
					$isInscrit = true;
					break;
				}
			endforeach;
			
			return $isInscrit;
		}
		
		// Fonction qui vérifie si le nombre maximum de participants est atteint
		function isNbrMaxParticipantsAtteint() {
			$res = false;
			
			if(isset($this->nbrParticipantMax)) {
				$countInscritDispo = 0;
				// On recherche les participants inscrits au statut "disponible"
				foreach($this->listParticipants as $participantTmp):
					if(CODE_STATUT_DISPO == $participantTmp->statut->code) {
						$countInscritDispo++;
					}
				endforeach;
			
				$res = $countInscritDispo >= $this->nbrParticipantMax;
			}
			
			return $res;
		}
		
		// Fonction qui vérifie si le nombre de participants pour un rôle donné est atteint
		function isRolePein($role) {
			$res = false;
			
			if(isset($role->nbrVoulu)) {
				$nbrInscritsRole = 0;
				foreach($this->listParticipants as $participantTmp):
					if($participantTmp->role->id == $role->id 
						&&  $participantTmp->statut->code == CODE_STATUT_DISPO) {
						$nbrInscritsRole++;
					}
				endforeach;
				$res = $nbrInscritsRole >= $role->nbrVoulu;
			}
			return $res ;
		}
		
		// Fonction qui permet de savoir si l'événement est passé ou non
		function isEventPasse() {
			$res = false;
			if(isset($this->date)) {
				$dateDuJour = date("Y-m-d H:i:s");
				if($this->getDateIso() < $dateDuJour) {
					$res = true;
				}
			}
			$this->isEventPasse = $res;
			return $res;
		}
		
		// Fonction qui permet de retourne la date et l'heure de l'événement au format iso
		function getDateIso() {
			$tmp = explode("/", $this->date);
			$date_iso = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			if(isset($this->heure)) {
				$date_iso = $date_iso.' '.$this->heure;
			} else {
				$date_iso = $date_iso.' 00:00:00';
			}
			
			return $date_iso;
		}
		
    }
?>