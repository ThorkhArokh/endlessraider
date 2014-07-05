<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Evenement.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/persistance/eventDao.php');

// Classe représentant une date pour le calendrier
    class DateCalendrier {
        var $jour;
        var $mois;
        var $annee;
        var $jourSemaine;
        
        // Liste d'évènements saisis à cette date
        var $listeEvent = array();
        
        // Constructeur
        function DateCalendrier($jourIn, $moisIn, $anneeIn, $jourSemaineIn) {
            $this->jour = $jourIn;
            $this->mois = $moisIn;
            $this->annee = $anneeIn;
            $this->jourSemaine = $jourSemaineIn;
            $date = $this->jour."/".$this->mois."/".$this->annee;
			// On récupère la liste des événements pour cette date
			$this->listeEvent = getEventsByDate($date);
        }
        
        // Ajoute un événement
        function addEvent($event) {
            $this->listeEvent[] = $event;
        }
        
        // Supprime un événement
        function removeEvent($event) {
            //TODO
        }
    }
?>