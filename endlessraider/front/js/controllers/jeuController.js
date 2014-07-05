// Définition du contrôleur pour la création/modification d'un jeu
endlessRaiderController.controller('JeuEditCtrl', ['TEMPLATE', '$scope', 'Jeu', '$routeParams', '$timeout', '$location', '$upload',
function(TEMPLATE, $scope, Jeu, $routeParams, $timeout, $location, $upload) {
	$scope.messageJeux = {};
	var listeIdClasseSupp = [];
	var listeIdRaceSupp = [];
	$scope.templateJeu = {};
	$scope.templateJeu.url = TEMPLATE.path;
	$scope.templateJeu.nom = TEMPLATE.jeuEdit+TEMPLATE.extention;
	$scope.raceToAdd = {};
	$scope.classeToAdd = {};
	
	// On récupère la liste des types de jeu
	Jeu.getAllType().then(function(result) {
		$scope.listTypeJeu = result.data;
		if($routeParams.idJeu === undefined) {
			$scope.jeu = {};
			$scope.typeAdd = true;
		} else {
			$scope.typeAdd = false;
			Jeu.get($routeParams.idJeu)
			.success(function(data, status, headers, config) {
				$scope.jeu = data;
				
				// On récupère les références pour qu'angular puisse initialiser les selects
				for (var i = 0; i < $scope.listTypeJeu.length; i++) {
					if ($scope.listTypeJeu[i].id == $scope.jeu.type.id) {
						// On recopie la référence du jeu
						$scope.jeu.type = $scope.listTypeJeu[i];
					}
				}
			});
		}
	});

	// Fonction qui ajoute une classe à la liste de classes du jeu
	$scope.ajouterClasse = function(classeToAdd) {
		if(classeToAdd && classeToAdd.libelle){
			var classeToAddTmp = { id:null, libelle:classeToAdd.libelle, iconPath:classeToAdd.iconPath};
			if(!$scope.jeu.listClasses) {
				$scope.jeu.listClasses = [];
			}
			$scope.jeu.listClasses.push(classeToAddTmp);
			$scope.classeToAdd = {};
		}
	}
	
	// Fonction qui supprime une classe précédement ajoutée
	$scope.deleteClasse = function(classe) {
		if(classe.id) {
			listeIdClasseSupp.push(classe.id);
		}
		$scope.jeu.listClasses.splice($scope.jeu.listClasses.indexOf(classe), 1);
	}
	
	// Fonction qui ajoute une race à la liste de races du jeu
	$scope.ajouterRace = function(raceToAdd) {
		if(raceToAdd && raceToAdd.libelle){
			var raceToAddTmp = { id:null, libelle:raceToAdd.libelle, iconPath:raceToAdd.iconPath};
			if(!$scope.jeu.listRaces) {
				$scope.jeu.listRaces = [];
			}
			$scope.jeu.listRaces.push(raceToAddTmp);
			$scope.raceToAdd = {};
		}
	}
	
	// Fonction qui supprime une race précédement ajouté
	$scope.deleteRace = function(race) {
		if(race.id) {
			listeIdRaceSupp.push(race.id);
		}
		$scope.jeu.listRaces.splice($scope.jeu.listRaces.indexOf(race), 1);
	}
	
	// Fonction qui enregistre les données fournies pour le jeu
	$scope.submit = function(jeu) {
		jeu.listeIdClasseSupp = listeIdClasseSupp;
		jeu.listeIdRaceSupp = listeIdRaceSupp;
		Jeu.sauvegarder(jeu)
		.success(function(data, status, headers, config) {
			// On reset le formulaire
			$scope.jeu = {};
			$scope.messageJeux.libelle = data.message;
			
			if(data.success) {
				$scope.messageJeux.type = "alert-success";
			} else {
				$scope.messageJeux.type = "alert-danger";
			}
			// On efface le message après un certain temps d'affichage
			$timeout(function(){$scope.messageJeux.libelle = ''}, 2000); 
			
			if(data.success) {
				$timeout(function(){$location.path( "/administrer/section/jeux" )}, 2000);
			}
		});
	}
	
	$scope.onFileSelect = function($files, objet) {
		console.log(objet);
		//$files: an array of files selected, each file has name, size, and type.
		for (var i = 0; i < $files.length; i++) {
		  var file = $files[i];
		  $scope.upload = $upload.upload({
			url: 'back/services/image/uploadImage.php', //upload.php script, node.js route, or servlet url
			// method: 'POST' or 'PUT',
			// headers: {'header-key': 'header-value'},
			// withCredentials: true,
			data: {myObj: objet.iconPath},
			file: file, // or list of files: $files for html5 only
			/* set the file formData name ('Content-Desposition'). Default is 'file' */
			//fileFormDataName: myFile, //or a list of names for multiple files (html5).
			/* customize how data is added to formData. See #40#issuecomment-28612000 for sample code */
			//formDataAppender: function(formData, key, val){}
		  }).progress(function(evt) {
			//console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
		  }).success(function(data, status, headers, config) {
			if(data.success) {
				// file is uploaded successfully	
				objet.iconPath = data.pathImage;
			} else {
				$scope.messageJeux.type = "alert-danger";
				$scope.messageJeux.libelle = data.message;
				// On efface le message après un certain temps d'affichage
				$timeout(function(){$scope.messageJeux.libelle = ''}, 2000);
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