// Filtre sur les statuts des participants à un événement
endlessRaiderApp.filter('filtreStatut', function() {
    return function(listePaticipants, statut) {
		var listeParticipantsFiltre = [];
		if(statut && statut != '') {
			for (var i = 0; i < listePaticipants.length; i++) {
				if(listePaticipants[i].statut.code == statut.code) {
					listeParticipantsFiltre.push(listePaticipants[i]);
				}
			}
		} else {
			listeParticipantsFiltre = listePaticipants;
		}
		return listeParticipantsFiltre;
    };
  });
  
// Filtre sur les rôles
endlessRaiderApp.filter('filtreRole', function() {
    return function(listePaticipants, role) {
		var listeParticipantsFiltre = [];
		if(role && role != '') {
			for (var i = 0; i < listePaticipants.length; i++) {
				if(listePaticipants[i].role.id == role.id) {
					listeParticipantsFiltre.push(listePaticipants[i]);
				}
			}
		} else {
			listeParticipantsFiltre = listePaticipants;
		}
		return listeParticipantsFiltre;
    };
  });

 // Filtre sur les rôles et sur le statut disponible du participant
  endlessRaiderApp.filter('filtreRoleDispo', function() {
    return function(listePaticipants, role) {
		var listeParticipantsFiltre = [];
		if(role && role != '') {
			for (var i = 0; i < listePaticipants.length; i++) {
				if(listePaticipants[i].role.id == role.id && listePaticipants[i].statut.code == "D") {
					listeParticipantsFiltre.push(listePaticipants[i]);
				}
			}
		} else {
			listeParticipantsFiltre = listePaticipants;
		}
		return listeParticipantsFiltre;
    };
  });
  
// Définition du contrôleur pour l'affichage d'un événement
endlessRaiderController.controller('EvenementCtrl', ['TEMPLATE', '$scope', 'Evenement', '$routeParams', '$timeout',
function(TEMPLATE, $scope, Evenement, $routeParams, $timeout) {
	$scope.orderTabASC = true;
	$scope.filtreStatutTab = '';
	$scope.orderTab = 'personnage.nom';
	$scope.messageInscription = {};
	$scope.isNbrMaxParticipantsAtteint = false;
	$scope.isEventPasse = false;
	$scope.listStatutsRef = [];
	$scope.templateEvent = {};
	$scope.templateEvent.url = TEMPLATE.path;
	$scope.templateEvent.nom = TEMPLATE.event+TEMPLATE.extention;
	
	// Méthode qui permet de changer l'order d'affichage des colonnes
	$scope.changeOrder = function(order) {
		if($scope.orderTab == order) {
			if($scope.orderTabASC) {
				$scope.orderTabASC = false;
				$scope.orderTab = "-"+order;
			} else {
				$scope.orderTabASC = true;
				$scope.orderTab = order;
			}
		} else {
			$scope.orderTabASC = true;
			$scope.orderTab = order;
		}
	};

	// On récupère tous les statuts
	Evenement.getAllStatut().success(function(data, status, headers, config) {
		$scope.listStatuts = data;
		// On recopie les statuts dans la liste de références
		for (var i = 0; i < $scope.listStatuts.length; i++) {
			$scope.listStatutsRef.push($scope.listStatuts[i]);
		}
	});
	
	// On récupère l'événement
	Evenement.get($routeParams.idEvent)
	.success(function(data, status, headers, config) {
		updateEvent(data);
		for (var i = 0; i < $scope.listStatuts.length; i++) {
			if($scope.isNbrMaxParticipantsAtteint) {
				if($scope.listStatuts[i].code = 'D') {
					$scope.listStatuts.splice(i, 1);
					break;
				}
			}
			// On supprime le statut "En attente"
			if($scope.listStatuts[i].code == 'W') {
				$scope.listStatuts.splice(i, 1);
			}
		} 
	});
	
	//Fonction qui enregistre l'inscription à l'événement 
	$scope.submit = function(inscription) {
		inscription.event = $scope.event;
		Evenement.inscrire(inscription)
		.success(function(data, status, headers, config) {
			if(data.success) {
				if(data.warning) {
					$scope.messageInscription.libelle = data.warning;
					$scope.messageInscription.type = "alert-warning";
				}
			} else {
				$scope.messageInscription.libelle = data.message;
				$scope.messageInscription.type = "alert-danger";
				// On efface le message après un certain temps d'affichage
				$timeout(function(){$scope.messageInscription.libelle = ''}, 2000); 
			}
		}).then(function() {
			// On met à jour la liste des inscrits
			Evenement.get($routeParams.idEvent)
			.success(function(data, status, headers, config) {
				updateEvent(data);
				updateStatuts();
			});
		});
	}
	
	// Fonction qui permet de désinscrire l'utilisateur connecté de l'événement
	$scope.desinscription = function() {
		Evenement.desinscrire($scope.event.id)
		.success(function(data, status, headers, config) {
			if(!data.success) {
				$scope.messageInscription.libelle = data.message;
				$scope.messageInscription.type = "alert-danger";
				// On efface le message après un certain temps d'affichage
				$timeout(function(){$scope.messageInscription.libelle = ''}, 2000); 
			}
		}).then(function() {
			// On met à jour la liste des inscrits
			Evenement.get($routeParams.idEvent)
			.success(function(data, status, headers, config) {
				updateEvent(data);
				updateStatuts();
			});
		});
	}
	
	// Fonction de mise à jour de l'événement
	function updateEvent(data) {
		$scope.isUserInscrit = data.isUserInscrit;
		$scope.isNbrMaxParticipantsAtteint = data.isNbrMaxParticipantsAtteint;
		$scope.isEventPasse = data.isEventPasse;
		$scope.event = data.event;
		$scope.listPersoUser = data.listPersoUser;
		$scope.orderTab = 'personnage.nom';
	}
	
	// Fonction de mise à jour des statuts
	function updateStatuts() {
		// On récupère tous les statuts
		Evenement.getAllStatut().success(function(data, status, headers, config) {
			$scope.listStatuts = data;
		}).then(function() {
			for (var i = 0; i < $scope.listStatuts.length; i++) {
				if($scope.isNbrMaxParticipantsAtteint 
					&& $scope.listStatuts[i].code == 'D') {
					$scope.listStatuts.splice(i, 1);
				}
				// On supprime le statut "En attente"
				if($scope.listStatuts[i].code == 'W') {
					$scope.listStatuts.splice(i, 1);
				}
			}
		});
	}
	
	$scope.updateCommentaire = function(commentaire) {
		Evenement.updateCommentaire($scope.event.id, commentaire)
			.success(function(data, status, headers, config) {
				if(!data.success) {
					$scope.messageInscription.libelle = data.message;
					$scope.messageInscription.type = "alert-danger";
				}
			});
	}
}]);

// Définition du contrôleur pour la création/modification d'un événement
endlessRaiderController.controller('EvenementEditCtrl', ['TEMPLATE', '$scope', 'Evenement', 'Jeu', '$routeParams', '$timeout', '$location', '$upload', 
function(TEMPLATE, $scope, Evenement, Jeu, $routeParams, $timeout, $location, $upload) {
	$scope.messageEvents = {};
	$scope.messageEventsImg = {};
	$scope.htmlEditDesc = '';
	$scope.templateEvent = {};
	$scope.templateEvent.url = TEMPLATE.path;
	$scope.templateEvent.nom = TEMPLATE.editEvent+TEMPLATE.extention;
	
	// On récupère la liste des jeux disponibles
	Jeu.getList().then(function(result) {
		$scope.listJeux = result.data;
		if($routeParams.idEvent === undefined) {
			$scope.event = {};
			
			// Si on vient du clique sur une date dans le calendrier
			if($routeParams.jourEvent && $routeParams.moisEvent && $routeParams.anneeEvent) {
				$scope.event.date = $routeParams.jourEvent+"/"+$routeParams.moisEvent+"/"+$routeParams.anneeEvent;
			}
			
			// Si on vient de l'admin avec un identifiant de template
			if($routeParams.idTemplate) {
				Evenement.getTemplate($routeParams.idTemplate)
				.success(function(data, status, headers, config) {
					if(data.success) {
						$scope.event = data.template;
						$scope.event.id = '';
						$scope.htmlEditDesc = $scope.event.desc;
						// On récupère les références pour qu'angular puisse initialiser les selects
						for (var i = 0; i < $scope.listJeux.length; i++) {
							if ($scope.listJeux[i].id == $scope.event.jeu.id) {
								// On recopie la référence du jeu
								$scope.event.jeu = $scope.listJeux[i];
							}
						}
					}
				});
			}
			
			$scope.typeAdd = true;
		} else {
			$scope.typeAdd = false;
			// On récupère l'événement
			Evenement.get($routeParams.idEvent)
			.success(function(data, status, headers, config) {
				if(data.success) {
					$scope.event = data.event;
					$scope.htmlEditDesc = $scope.event.desc;
					// On récupère les références pour qu'angular puisse initialiser les selects
					for (var i = 0; i < $scope.listJeux.length; i++) {
						if ($scope.listJeux[i].id == $scope.event.jeu.id) {
							// On recopie la référence du jeu
							$scope.event.jeu = $scope.listJeux[i];
						}
					}
				} else {
					$scope.messageEvents.libelle = data.message;
					$scope.messageEvents.type = "alert-danger";
				}
			});
		}
	});
	
	// Fonction qui ajoute un rôle à la liste de rôles du jeu
	$scope.ajouterRole = function(roleToAddLbl, roleToAddNbr) {
		if(roleToAddLbl){
			var roleToAdd = { id:null, libelle:roleToAddLbl, nbrVoulu:roleToAddNbr};
			if(!$scope.event.listRoles) {
				$scope.event.listRoles = [];
			}
			$scope.event.listRoles.push(roleToAdd);
			$scope.roleToAddlbl = '';
		}
	}
	
	// Fonction qui supprime un rôle précédement ajouté
	$scope.deleteRole = function(role) {
		$scope.event.listRoles.splice($scope.event.listRoles.indexOf(role), 1);
		if(role.id) {
			if(!$scope.event.listIdRolesASupp) {
				$scope.event.listIdRolesASupp = [];
			}
			$scope.event.listIdRolesASupp.push(role.id);
		}
	}
	
	//Fonction qui enregistre les informations saisies sur l'événement 
	$scope.submit = function(event, htmlEditDesc) {
		event.desc = htmlEditDesc;
		if(event.nbrParticipantMax != null && event.nbrParticipantMax == ""){
			event.nbrParticipantMax = null;
		}
		if(event.levelMin != null && event.levelMin == ""){
			event.levelMin = null;
		}
		if(event.levelMax != null && event.levelMax == ""){
			event.levelMax = null;
		}
		
		Evenement.sauvegarder(event)
		.success(function(data, status, headers, config) {
			// On reset le formulaire
			$scope.event = {};
			$scope.htmlEditDesc = '';
			$scope.messageEvents.libelle = data.message;
			
			if(data.success) {
				$scope.messageEvents.type = "alert-success";
			} else {
				$scope.messageEvents.type = "alert-danger";
			}
			// On efface le message après un certain temps d'affichage
			$timeout(function(){$scope.messageEvents.libelle = ''}, 2000); 
			
			if(data.success) {
				$timeout(function(){$location.path( "/administrer/section/events" )}, 2000);
			}
		});
		
	}
	
	$scope.onFileSelect = function($files) {
		//$files: an array of files selected, each file has name, size, and type.
		for (var i = 0; i < $files.length; i++) {
		  var file = $files[i];
		  $scope.upload = $upload.upload({
			url: 'back/services/image/uploadImage.php', //upload.php script, node.js route, or servlet url
			// method: 'POST' or 'PUT',
			// headers: {'header-key': 'header-value'},
			// withCredentials: true,
			data: {myObj: $scope.event.imagePath},
			file: file, // or list of files: $files for html5 only
			/* set the file formData name ('Content-Desposition'). Default is 'file' */
			//fileFormDataName: myFile, //or a list of names for multiple files (html5).
			/* customize how data is added to formData. See #40#issuecomment-28612000 for sample code */
			//formDataAppender: function(formData, key, val){}
		  }).progress(function(evt) {
		  }).success(function(data, status, headers, config) {
			if(data.success) {
				// file is uploaded successfully	
				$scope.event.imagePath = data.pathImage;
			} else {
				$scope.event.imagePath = '';
				$scope.messageEventsImg.type = "alert-danger";
				$scope.messageEventsImg.libelle = data.message;
				// On efface le message après un certain temps d'affichage
				$timeout(function(){$scope.messageEventsImg.libelle = ''}, 2000); 
			}
		  });
		  //.error(...)
		  //.then(success, error, progress); 
		  //.xhr(function(xhr){xhr.upload.addEventListener(...)})// access and attach any event listener to XMLHttpRequest.
		}
		/* alternative way of uploading, send the file binary with the file's content-type.
		   Could be used to upload files to CouchDB, imgur, etc... html5 FileReader is needed. 
		   It could also be used to monitor the progress of a normal http post/put request with large data*/
		// $scope.upload = $upload.http({...})  see 88#issuecomment-31366487 for sample code.
	};
}]);
