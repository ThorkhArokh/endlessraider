// Fichier contenant la définition de la factory permettant d'appeler les services pour un objet Jeu
jeuServices.factory('Jeu', function ($http) {
    return {
		getList : function() {
				return $http({
					url:'back/services/jeu/getListJeux.php', 
					method: "GET",
					isArray:true
				});
		},
		get : function(idJeu) {
			return $http({
				url:'back/services/jeu/getJeu.php', 
				method: "GET",
				params: {idJeu:idJeu}
			});
		},
		sauvegarder : function(jeu) {
			return $http.post('back/services/jeu/saveJeu.php', jeu);
		},
		supprimer : function(idJeu) {
			return $http({
				url : 'back/services/jeu/deleteJeu.php',
				method: "GET",
				params: {idJeu:idJeu}
			});
		},
		getAllType : function() {
			return $http({
				url : 'back/services/jeu/getAllTypeJeu.php',
				method: "GET"
			});
		}
	};
});