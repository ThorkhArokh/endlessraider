// Filtre des événements sur un jeu
endlessRaiderApp.filter('filtreEventParJeu', function() {
    return function(listeEvents, jeu) {
		var listeEventsFiltre = [];
		if(jeu && jeu != '') {
			for (var i = 0; i < listeEvents.length; i++) {
				if(listeEvents[i].jeu.id == jeu.id) {
					listeEventsFiltre.push(listeEvents[i]);
				}
			}
		} else {
			listeEventsFiltre = listeEvents;
		}
		return listeEventsFiltre;
    };
  });

// Contrôlleur associé à la page administrer.html
endlessRaiderController.controller('AdministrerCtrl', ['USER_ROLES', '$rootScope', '$scope', 'User', 'Jeu', 'Evenement', 'Personnage', '$routeParams', '$timeout',
function(USER_ROLES, $rootScope, $scope, User, Jeu, Evenement, Personnage, $routeParams, $timeout) {
	$scope.userRoles = USER_ROLES;
	$scope.showPanel = "events";
	if($routeParams.choix) {
		$scope.showPanel = $routeParams.choix;
	} else if( $rootScope.choixSectionAdmin ) {
		$scope.showPanel = $rootScope.choixSectionAdmin;
	}

	$scope.filtreEventJeuTab = '';
	$scope.filtreTemplateJeuTab = '';
	$scope.messageEvents = {};
	$scope.messageJeux = {};
	$scope.messageTemplates = {};
	$scope.checkHisto = false;
	
	// On récupère la liste des jeux
	Jeu.getList().success(function(data, status, headers, config) {
		$scope.listJeux = data;
	});
	// On récupère la liste des événements
	Evenement.getAll().success(function(data, status, headers, config) {
		$scope.listEvents = data;
	});
	// On récupère les templates
	Evenement.getAllTemplates().success(function(data, status, headers, config) {
		$scope.listTemplates = data;
	});
	
	// On récupère le rooster
	Personnage.getAll().success(function(data, status, headers, config) {
		$scope.listPersos = data;
		$scope.groupedPersos = _.groupBy($scope.listPersos, function(perso){return perso.jeu.nom});
	});
	
	User.getAll().success(function(data, status, headers, config) {
		$scope.listeUsers = data.listeUsers;
	});
	
	User.getAllRoleUser().success(function(data, status, headers, config) {
		$scope.listeRolesUser = data;
	});
	
	// Fonction qui permet d'afficher/masquer les différentes parties de l'écran
	$scope.selectMenu = function(panel){
		$scope.showPanel = panel;
	}
	
	// Fonction qui permet de déclencher une suppression d'un événement sélectionné
	$scope.deleteEvent = function(idEvent){
		$rootScope.choixSectionAdmin = "events";
		Evenement.supprimer(idEvent)
		.success(function(data, status, headers, config) {
			$scope.messageEvents.libelle = data.message;
			if(data.success) {
				$scope.messageEvents.type = "alert-success";
			} else {
				$scope.messageEvents.type = "alert-danger";
			}
			// On efface le message après un certain temps d'affichage
			$timeout(function(){$scope.messageEvents.libelle = ''}, 2000); 
		}).then(function(){
			// On récupère la liste des événements
			Evenement.getAll().success(function(data, status, headers, config) {
			$scope.listEvents = data;
			});
		});
	}
	
	// Fonction qui permet de déclencher une suppression d'un template sélectionné
	$scope.deleteTemplate = function(idTemplate){
		$rootScope.choixSectionAdmin = "templates";
		Evenement.supprimerTemplate(idTemplate)
		.success(function(data, status, headers, config) {
			$scope.messageTemplates.libelle = data.message;			
			if(data.success) {
				$scope.messageTemplates.type = "alert-success";
			} else {
				$scope.messageTemplates.type = "alert-danger";
			}
			// On efface le message après un certain temps d'affichage
			$timeout(function(){$scope.messageTemplates.libelle = ''}, 2000); 
		}).then(function(){
			// On récupère la liste des templates
			Evenement.getAllTemplates().success(function(data, status, headers, config) {
				$scope.listTemplates = data;
			});
		});
	}
	
	// Fonction qui permet de déclencher une suppression d'un jeu sélectionné
	$scope.deleteJeu = function(idJeu){
		$rootScope.choixSectionAdmin = "jeux";
		Jeu.supprimer(idJeu)
		.success(function(data, status, headers, config) {
			$scope.messageJeux.libelle = data.message;
			
			if(data.success) {
				$scope.messageJeux.type = "alert-success";
			} else {
				$scope.messageJeux.type = "alert-danger";
			}
			// On efface le message après un certain temps d'affichage
			$timeout(function(){$scope.messageJeux.libelle = ''}, 2000); 
		}).then(function() {
			// On récupère la liste des jeux
			Jeu.getList().success(function(data, status, headers, config) {
				$scope.listJeux = data;
			});
			// On récupère la liste des événements
			Evenement.getAll().success(function(data, status, headers, config) {
				$scope.listEvents = data;
			});
		});
	}
	
	// Fonction qui permet de sauvegarder un event sous forme d'un template
	$scope.saveEventAsTemplate = function(idEvent) {
		$rootScope.choixSectionAdmin = "events";
		Evenement.saveEventAsTemplate(idEvent)
		.success(function(data, status, headers, config) {
			$scope.messageEvents.libelle = data.message;
			if(data.success) {
				$scope.messageEvents.type = "alert-success";
			} else {
				$scope.messageEvents.type = "alert-danger";
			}
			// On efface le message après un certain temps d'affichage
			$timeout(function(){$scope.messageEvents.libelle = ''}, 2000); 
		}).then(function(){
			// On récupère la liste des templates
			Evenement.getAllTemplates().success(function(data, status, headers, config) {
				$scope.listTemplates = data;
			});
		});
	}

	$scope.afficherHisto = function(checkHisto) {
		$scope.checkHisto = checkHisto;
		if(checkHisto) {
			// On récupère la liste de tous les événements
			Evenement.getAllHistorique().success(function(data, status, headers, config) {
				$scope.listEvents = data;
			});
		} else {
			// On récupère la liste des événements non passés
			Evenement.getAll().success(function(data, status, headers, config) {
				$scope.listEvents = data;
			});
		}
	}
	
	// Permet de définir un utilisateur comme administrateur d'un jeu
	$scope.addJeuAdminParUser = function(jeu, idUser) {
		User.saveDroitAdminJeu(idUser, jeu.id).success(function(data, status, headers, config) {
				User.getAll().success(function(data, status, headers, config) {
					$scope.listeUsers = data.listeUsers;
				});
			});
	}
	
	// Permet de supprimer un utilisateur comme administrateur d'un jeu
	$scope.deleteJeuAdminParUser = function(jeu, idUser) {
		User.deleteDroitAdminJeu(idUser, jeu.id).success(function(data, status, headers, config) {
				User.getAll().success(function(data, status, headers, config) {
					$scope.listeUsers = data.listeUsers;
				});
			});
	}
	
	$scope.updateRoleUser = function(role, idUser) {
		User.updateRoleUser(role.code, idUser).success(function(data, status, headers, config) {
				User.getAll().success(function(data, status, headers, config) {
					$scope.listeUsers = data.listeUsers;
				});
			});
	}
}]);