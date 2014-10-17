// Déclaration du module gérant les contrôleurs
var endlessRaiderController = angular.module('endlessRaiderController', []);

// Controller permettant de gérer les class css des liens actifs suite au choix utilisateur
endlessRaiderController.controller("MainCtrl", function(Session, USER_ROLES, $scope, $rootScope, $location, AuthService, $cookies, $timeout) {
	$scope.message = {};
	AuthService.login().then(
	function() {
		$location.path( "/calendrier" );
		$rootScope.currentUser = Session.userId;
    }, 
	function (reason) {
		$rootScope.currentUser = null;
		$scope.message.libelle = reason;
		$scope.message.type = "alert-danger";
		// On efface le message après un certain temps d'affichage
		$timeout(function(){$scope.message.libelle = ''}, 2000); 
    }); 
	
	AuthService.getSession();
	$rootScope.currentUser = Session.userId;
	
	$scope.userRoles = USER_ROLES;
	$scope.menuClass = function(page) {
		var current = $location.path().substring(1);
		return !current.indexOf(page) ? "active" : "";
	};

	$scope.moisClass = function(page) {
		var current = $location.path().substring(1);
		return page === current ? "active" : "";
	};
	
	$scope.isAuthorized = function(authorizedRoles) { 
		return AuthService.isAuthorized(authorizedRoles)};
		
	$scope.isDatePast = function(date) {
		var isPast = false;
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		if(date.annee < yyyy) {
			isPast = true;
		} else if(date.annee == yyyy){
			if(date.mois < mm) {
				isPast = true;
			} else if(date.mois == mm) { 
				if(date.jour < dd) {
					isPast = true;
				}
			}
		}
		return isPast;
	};
  
	// Fonction de déconnexion
	$scope.logout = function() {
			AuthService.logout().then(function () {
			$location.path( "/login" );
			$rootScope.currentUser = null;
		}, function () {
			$location.path( "/erreur/error/logoutFailed" );
		});
	};
});

endlessRaiderController.controller('LoginCtrl', function (Session, $scope, $rootScope, AUTH_EVENTS, AuthService, $location, $timeout) {
  $scope.credentials = {
    username: '',
    password: ''
  };
  $scope.message = {};
  $scope.login = function (credentials) {
    AuthService.login(credentials).then(
	function() {
		$location.path( "/calendrier" );
		$rootScope.currentUser = Session.userId;
    }, 
	function (reason) {
		$rootScope.currentUser = null;
		$scope.message.libelle = reason;
		$scope.message.type = "alert-danger";
		// On efface le message après un certain temps d'affichage
		$timeout(function(){$scope.message.libelle = ''}, 2000); 
    }); 
  };
});

// définition du contrôlleur d'affichage des erreurs
endlessRaiderController.controller('ErreurCtrl', ['$scope', '$routeParams', function($scope, $routeParams) {
	ERROR_CODE = {
		noAccess: { 
			message : "Vous n'avez pas les droits nécessaires effectuer cette action",
			type : "warning"
		},
		loginFailed: {
			message : "Utilisateur ou mot de passe non valide",
			type : "error"
		},
		logoutFailed: {
			message : "Erreur lors de la déconnexion",
			type : "error"
		}
	};
	$scope.erreur = {
		type : ERROR_CODE[$routeParams.code].type,
		message : ERROR_CODE[$routeParams.code].message
	};
 }]);


// définition du contrôleur pour l'agenda
endlessRaiderController.controller('AgendaCtrl', ['$scope', 'Agenda', '$routeParams', 'Jeu', afficheCalendrier]);

// Fonction qui permet d'afficher la page du calendrier pour une année et un mois donné
function afficheCalendrier($scope, Agenda, $routeParams, Jeu) {
	// On récupère la liste des jeux
	Jeu.getList().success(function(data, status, headers, config) {
		$scope.listJeux = data;
	});

  $scope.anneeEnCours = $routeParams.annee;
  $scope.moisEnCours = $routeParams.mois;
  if($scope.anneeEnCours === undefined) {
	$scope.anneeEnCours = new Date().getFullYear();
  }
  $scope.anneeEnCours = parseInt($scope.anneeEnCours);
  if($scope.moisEnCours === undefined) {
	$scope.moisEnCours = new Date().getMonth()+1;
  }
  $scope.dates = Agenda.get({annee:$scope.anneeEnCours, mois:$scope.moisEnCours});
  $scope.joursSemaineList = 
	[	{'libelle' :"Lundi"}, 
		{'libelle' :"Mardi"}, 
		{'libelle' :"Mercredi"}, 
		{'libelle' :"Jeudi"}, 
		{'libelle' :"Vendredi"}, 
		{'libelle' :"Samedi"}, 
		{'libelle' :"Dimanche"}
	];
	$scope.moisList = 
	[	{'libelle' :"Janvier", 'ind' : 1}, 
		{'libelle' :"Février", 'ind' : 2}, 
		{'libelle' :"Mars", 'ind' : 3}, 
		{'libelle' :"Avril", 'ind' : 4}, 
		{'libelle' :"Mai", 'ind' : 5}, 
		{'libelle' :"Juin", 'ind' : 6}, 
		{'libelle' :"Juillet", 'ind' : 7}, 
		{'libelle' :"Août", 'ind' : 8}, 
		{'libelle' :"Septembre", 'ind' : 9}, 
		{'libelle' :"octobre", 'ind' : 10}, 
		{'libelle' :"Novembre", 'ind' : 11}, 
		{'libelle' :"Décembre", 'ind' : 12}
	];
}
