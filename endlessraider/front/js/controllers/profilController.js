// Filtre sur les jeux
endlessRaiderApp.filter('filtrejeu', function() {
    return function(listePersos, jeu) {
		var listePersosFiltre = [];
		if(jeu && jeu != '') {
			for (var i = 0; i < listePersos.length; i++) {
				if(listePersos[i].jeu.id == jeu.id) {
					listePersosFiltre.push(listePersos[i]);
				}
			}
		} else {
			listePersosFiltre = listePersos;
		}
		return listePersosFiltre;
    };
  });

// définition du contrôleur pour un profile utilisateur
endlessRaiderController.controller('ProfileCtrl', ['TEMPLATE', '$scope', 'User', 'Personnage', 'Jeu', '$timeout',
function(TEMPLATE, $scope, User, Personnage, Jeu, $timeout) { 
	$scope.message = {};
	$scope.templateProfilePerso = {};
	$scope.templateProfilePerso.url = TEMPLATE.path;
	$scope.templateProfilePerso.nom = TEMPLATE.profilePerso+TEMPLATE.extention;
	
	// On récupère le profil de l'utilisateur
	 User.get().success(function(data, status, headers, config) {
		$scope.user = data;
		$scope.listePersos = $scope.user.persos;
		// On récupère la liste des jeux
		Jeu.getList().then(function(result) {
			$scope.listJeux = result.data;
			if($scope.listJeux && $scope.listJeux.length > 0) {
				$scope.showPanel = $scope.listJeux[0];
			}
		});
	});
	
	// Fonction qui permet de supprimer un personnage de la liste
	$scope.deletePerso = function(idPerso) {
		Personnage.supprimer(idPerso)
		.success(function(data, status, headers, config) {
			$scope.message.libelle = data.message;
			
			if(data.success) {
				$scope.message.type = "alert-success";
			} else {
				$scope.message.type = "alert-danger";
			}
			// On efface le message après un certain temps d'affichage
			$timeout(function(){$scope.message.libelle = ''}, 2000);
		}).then(function() {
			// On met à jour l'utilisateur
			$scope.user = User.get();
		});
	}
	
	// Fonction qui permet d'afficher/masquer les différentes parties de l'écran
	$scope.selectMenu = function(panel){
		$scope.showPanel = panel;
	}
}]);

// définition du contrôleur pour la gestion des personnages d'un utilisateur
endlessRaiderController.controller('PersonnageCtrl', ['TEMPLATE', '$scope', 'Personnage', 'Jeu',
 '$routeParams', '$timeout', '$location',
function(TEMPLATE, $scope, Personnage, Jeu, $routeParams, $timeout, $location) { 
	$scope.message = {};
	$scope.templatePerso = {};
	$scope.templatePerso.url = TEMPLATE.path;
	$scope.templatePerso.nom = TEMPLATE.persoEdit+TEMPLATE.extention;
	
	$scope.resetClasseRace = function() {
		$scope.personnage.race = {};
		$scope.personnage.classe = {};
	};

	Jeu.getList().then(function(result) {
		$scope.listJeux = result.data;
		if($routeParams.idPerso === undefined) {
			$scope.personnage = {};
			$scope.typeAdd = true;
		} else {
			$scope.typeAdd = false;
			Personnage.get($routeParams.idPerso)
			.success(function(data, status, headers, config) {
				$scope.personnage = data;
				// On récupère les références pour qu'angular puisse initialiser les selects
				for (var i = 0; i < $scope.listJeux.length; i++) {
					if ($scope.listJeux[i].id == $scope.personnage.jeu.id) {
						// On recopie la référence du jeu
						$scope.personnage.jeu = $scope.listJeux[i];
						for (var j = 0; j < $scope.listJeux[i].listClasses.length; j++) {
							if ($scope.listJeux[i].listClasses[j].id == $scope.personnage.classe.id) {
								// On recopie la référence de la classe
								$scope.personnage.classe = $scope.listJeux[i].listClasses[j];
								break;
							}
						}
						for (var k = 0; k < $scope.listJeux[i].listRaces.length; k++) {
							if ($scope.listJeux[i].listRaces[k].id == $scope.personnage.race.id) {
								// On recopie la référence de la race
								$scope.personnage.race = $scope.listJeux[i].listRaces[k];
								break;
							}
						}
						break;
					}
					
				}
			});
		}
	});
	
	//Fonction qui enregistre les informations saisies sur le personnage 
	$scope.submit = function(personnage) {
		if(personnage.level != null && personnage.level == ""){
			personnage.level = null;
		}
	
		Personnage.sauvegarder(personnage)
		.success(function(data, status, headers, config) {
			// On reset le formulaire
			$scope.personnage = {};
			
			$scope.message.libelle = data.message;
			
			if(data.success) {
				$scope.message.type = "alert-success";
			} else {
				$scope.message.type = "alert-danger";
			}
			// On efface le message après un certain temps d'affichage
			$timeout(function(){$scope.message.libelle = ''}, 2000); 
			
			if(data.success) {
				$timeout(function(){$location.path( "/profile" )}, 2000);
			}
		});
	}
}]);