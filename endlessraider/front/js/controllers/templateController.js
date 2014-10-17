// Filtre sur les statuts des participants à un événement
endlessRaiderApp.filter('filtreStatut', function() {
    return function(listePaticipants, statut) {
		var listeParticipantsFiltre = [];
		if(statut && statut != '' && listePaticipants) {
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
		if(role && role != '' && listePaticipants) {
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
		if(role && role != '' && listePaticipants) {
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
  
// Définition du contrôleur pour l'affichage d'un template
endlessRaiderController.controller('TemplateCtrl', ['TEMPLATE', '$scope', 'Evenement', '$routeParams', '$timeout',
function(TEMPLATE, $scope, Evenement, $routeParams, $timeout) {
	$scope.templateTemplate = {};
	$scope.templateTemplate.url = TEMPLATE.path;
	$scope.templateTemplate.nom = TEMPLATE.templateEvent+TEMPLATE.extention;
	
	// On récupère le template
	Evenement.getTemplate($routeParams.idTemplate)
	.success(function(data, status, headers, config) {
		$scope.template = data.template;
	});
}]);

// Définition du contrôleur pour la création/modification d'un template
endlessRaiderController.controller('TemplateEditCtrl', ['TEMPLATE', 'Session', '$scope', 'Evenement', 'Jeu', '$routeParams', '$timeout', '$location', '$upload', 
function(TEMPLATE, Session, $scope, Evenement, Jeu, $routeParams, $timeout, $location, $upload) {
	$scope.messageEvents = {};
	$scope.messageEventsImg = {};
	$scope.htmlEditDesc = '';
	$scope.templateEvent = {};
	$scope.templateEvent.url = TEMPLATE.path;
	$scope.templateEvent.nom = TEMPLATE.editEvent+TEMPLATE.extention;
	if(Session.idJeuAdmin) {
		$scope.currentUserIdJeuAdmin = Session.idJeuAdmin;
	}
	
	// On récupère la liste des jeux disponibles
	Jeu.getList().then(function(result) {
		$scope.listJeux = result.data;
		if($routeParams.idTemplate === undefined) {
			$scope.event = {};
			$scope.typeAdd = true;
		} else {
			$scope.typeAdd = false;
			// On récupère le template
			Evenement.getTemplate($routeParams.idTemplate)
			.success(function(data, status, headers, config) {
				if(data.success) {
					$scope.event = data.template;
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
		Evenement.sauvegarderTemplate(event)
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
				$timeout(function(){$location.path( "/administrer/section/templates" )}, 2000);
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
