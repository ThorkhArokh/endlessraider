eventServices.factory('Evenement', function ($http) {
    return {
		get : function(idEvent) {
			return $http({
				url :'back/services/evenement/getEvent.php',
				method: "GET",
				params: {idEvent:idEvent}
			});
		},
		getAll : function() {
			return $http({
				url :'back/services/evenement/getAllEvents.php',
				method: "GET",
				isArray:true
			});
		},
		getAllHistorique : function() {
			return $http({
				url :'back/services/evenement/getAllEventsHistorique.php',
				method: "GET",
				isArray:true
			});
		},
		supprimer : function(idEvent) {
			return $http({
				url :'back/services/evenement/deleteEvent.php',
				method: "GET",
				params: {idEvent:idEvent}
			});
		},
		sauvegarder : function(evenement) {
			return $http.post('back/services/evenement/saveEvent.php', evenement);
		},
		getAllStatut : function() {
			return $http({
				url :'back/services/evenement/getAllStatut.php',
				method: "GET",
				isArray:true
			});
		},
		inscrire : function(inscription) {
			return $http.post('back/services/evenement/inscriptionEvent.php', inscription);
		},
		desinscrire : function(idEvent) {
			return $http({
				url :'back/services/evenement/desinscriptionEvent.php',
				method: "GET",
				params: {idEvent:idEvent}
			});
		},
		getTemplate : function(idTemplate) {
			return $http({
				url :'back/services/evenement/template/getTemplate.php',
				method: "GET",
				params: {idTemplate:idTemplate}
			});
		},
		getAllTemplates : function() {
			return $http({
				url :'back/services/evenement/template/getAllTemplates.php',
				method: "GET",
				isArray:true
			});
		},
		supprimerTemplate : function(idTemplate) {
			return $http({
				url :'back/services/evenement/template/deleteTemplate.php',
				method: "GET",
				params: {idTemplate:idTemplate}
			});
		},
		sauvegarderTemplate : function(template) {
			return $http.post('back/services/evenement/template/saveTemplate.php', template);
		},
		saveEventAsTemplate : function(idEvent) {
			return $http({
				url: 'back/services/evenement/template/saveEventAsTemplate.php',
				method: "GET",
				params: {idEvent:idEvent}
			});
		},
		updateCommentaire : function(idEvent, commentaire) {
			return $http({
			url: 'back/services/evenement/updateCommentaireInscriptionEvent.php',
				method: "GET",
				params: {idEvent:idEvent, commentaire:commentaire}
			});
		}
	};
});