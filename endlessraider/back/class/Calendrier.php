<?php
    //require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider/back/class/Date.php');
    require_once('Evenement.php');
    require_once('DateCalendrier.php');

    // Classe représentant un calendrier d'évènements
    class Calendrier {
        
        // Liste de dates du calendrier
        var $dates = array();
		
		var $days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        var $months = array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
        
        // Constructeur
        function Calendrier($year) {
            $this->dates = $this->getAllDates($year);
        }
		
        // Fonction qui retourne l'ensemble des dates nécessaires au calendrier
        function getAllDates($year) {
            $listDates = array();
            
            $date = strtotime($year.'-01-01');
            while(date('Y', $date) <= $year) {
                $y = date('Y', $date);
                $m = date('n', $date);
                $d = date('j', $date);
                //On remplace l'indice 0 du dimanche par 7 pour avoir les jours de 
                //la semaine de 1 à 7
                $w = str_replace('0', '7', date('w', $date));
                
                $dateTmp = new DateCalendrier($d, $m, $y, $w);
                
                $listDates[$y][$m][] = $dateTmp;
                $date = strtotime(date('Y-m-d', $date).' +1 DAY');
                
            }

            return $listDates;
        }  
		 	
		// Méthode permettant d'afficher le calendrier
        function showCalendrier($year, $moisEnCours) {
            $dates = $this->dates;
                   
			$calendrier = array();
            foreach($dates[$year][$moisEnCours] as $dateTmp):
				$semaine = date('W', mktime(0,0,0,$dateTmp->mois,$dateTmp->jour,$dateTmp->annee));
                $calendrier[$semaine][$dateTmp->jourSemaine] = $dateTmp;
				for ($i = 1; $i <= 7; $i++) {
					if(!array_key_exists( $i , $calendrier[$semaine] )) {
						$calendrier[$semaine][$i] = null;
					}
				}
            endforeach; 
			
			$calendrierIndexe = array();
			$ind = 1;
			foreach($calendrier as $semaineTmp):
				$calendrierIndexe[$ind] = $semaineTmp;
				$ind = $ind +1;
            endforeach; 

			return $calendrierIndexe;
        }  
        
        // Renvoi le calendrier au format JSON
        function getCalendrierJSON() {
            return json_encode($this->dates);
        }
		
		// Renvoi le calendrier au format JSON
        function getCalendrierJSONPourUnMois($annee, $mois) {
            return json_encode($this->showCalendrier($annee, $mois));
        }
        
    }

?>