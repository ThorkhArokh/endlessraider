var endlessRaiderApp = angular.module('endlessRaiderApp', [
	'ngRoute', 
	'ngCookies',
	'ngAnimate',
	'xeditable',
	'angularFileUpload',
	'textAngular',
	'endlessRaiderController', 
	'agendaServices',
	'eventServices',
	'authServices',
	'userServices',
	'persoServices',
	'jeuServices'
]);

endlessRaiderApp.constant('TEMPLATE', {
	path : 'front/pages/templates/',
	extention : '.html',
	event: 'templateEvent',
	editEvent: 'templateEventEdit',
	persoEdit: 'templatePersoEdit',
	profilePerso: 'templateProfilePerso',
	templateEvent: 'templateTemplateEvent',
	jeuEdit: 'templateJeuEdit'
});

endlessRaiderApp.constant('AUTH_EVENTS', {
  loginSuccess: 'auth-login-success',
  loginFailed: 'auth-login-failed',
  logoutSuccess: 'auth-logout-success',
  sessionTimeout: 'auth-session-timeout',
  notAuthenticated: 'auth-not-authenticated',
  notAuthorized: 'auth-not-authorized'
});

endlessRaiderApp.constant('USER_ROLES', {
  all: '*',
  admin: 'admin',
  editor: 'officier',
  membre: 'membre',
  guest: 'guest'
});

endlessRaiderApp.directive('datepicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
         link: function (scope, element, attrs, ngModelCtrl) {
				element.datepicker({
					dateFormat: 'dd/mm/yy',
					onSelect: function (date) {
						scope.$apply(function () {
                            ngModelCtrl.$setViewValue(date);
                        });
					}
				});
        }
    };
});

endlessRaiderApp.service('Session', function ($cookies) {
  this.create = function (sessionId, userId, userRole) {
    this.id = sessionId;
    this.userId = userId;
    this.userRole = userRole;
	
	$cookies.login = userId;
  };
  this.destroy = function () {
    this.id = null;
    this.userId = null;
    this.userRole = null;
	
	$cookies.login = '';
  };
  return this;
});

// Fonction qui permet de catcher les changements d'url afin d'appliquer les droits d'affichage
endlessRaiderApp.run(function ($rootScope, AUTH_EVENTS, AuthService, $location, editableOptions) {
	editableOptions.theme = 'bs3';

	$rootScope.$on('$routeChangeStart', function (event, next) {
	var authorizedRoles = next.data.authorizedRoles;
	if(authorizedRoles.length > 0) { 
		if (!AuthService.isAuthorized(authorizedRoles)) {
		  event.preventDefault();
		  if (AuthService.isAuthenticated()) {
			// user is not allowed
			$rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
			$location.path( "/erreur/warning/noAccess" );
		  } else {
			// user is not logged in
			$rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
			$location.path( "/login" );
		  }
		}
	}
	});
});

endlessRaiderApp.config(['$routeProvider', 'USER_ROLES', 
  function($routeProvider, USER_ROLES) {
    $routeProvider.
      when('/calendrier', {
        templateUrl: 'front/pages/calendrier.html',
        controller: 'AgendaCtrl',
		data : {
			authorizedRoles: []
		}
      }).
      when('/calendrier/:annee/:mois', {
        templateUrl: 'front/pages/calendrier.html',
        controller: 'AgendaCtrl',
		data : {
			authorizedRoles: []
		}
      }).
	  when('/calendrier/:annee', {
        templateUrl: 'front/pages/calendrier.html',
        controller: 'AgendaCtrl',
		data : {
			authorizedRoles: []
		}
      }).
	  when('/profile', {
        templateUrl: 'front/pages/profile.html',
        controller: 'ProfileCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor, USER_ROLES.membre]
		}
      }).
	  when('/profile/addPersonnage', {
        templateUrl: 'front/pages/ajouterPerso.html',
        controller: 'PersonnageCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor, USER_ROLES.membre]
		}
      }).
	  when('/profile/addPersonnage/:idPerso', {
        templateUrl: 'front/pages/ajouterPerso.html',
        controller: 'PersonnageCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor, USER_ROLES.membre]
		}
      }).
	  when('/evenement/:idEvent', {
        templateUrl: 'front/pages/evenement.html',
        controller: 'EvenementCtrl',
		data : {
			authorizedRoles: []
		}
      }).
	  when('/evenement/template/:idTemplate', {
        templateUrl: 'front/pages/template.html',
        controller: 'TemplateCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
		}
      }).
	  when('/administrer', {
        templateUrl: 'front/pages/administrer.html',
        controller: 'AdministrerCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
		}
      }).
	  when('/administrer/section/:choix', {
        templateUrl: 'front/pages/administrer.html',
        controller: 'AdministrerCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
		}
      }).
	  when('/administrer/editEvent', {
		templateUrl: 'front/pages/editEvent.html',
        controller: 'EvenementEditCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
		}
	  }).
	  when('/administrer/editEvent/:idEvent', {
		templateUrl: 'front/pages/editEvent.html',
        controller: 'EvenementEditCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
		}
	  }).
	  when('/administrer/editEvent/date/:jourEvent/:moisEvent/:anneeEvent', {
		templateUrl: 'front/pages/editEvent.html',
        controller: 'EvenementEditCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
		}
	  }).
	  when('/administrer/editEvent/template/:idTemplate', {
		templateUrl: 'front/pages/editEvent.html',
        controller: 'EvenementEditCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
		}
	  }).
	  when('/administrer/editJeu', {
        templateUrl: 'front/pages/editJeu.html',
        controller: 'JeuEditCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin]
		}
      }).
	  when('/administrer/editJeu/:idJeu', {
        templateUrl: 'front/pages/editJeu.html',
        controller: 'JeuEditCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin]
		}
      }).
	  when('/administrer/editTemplate', {
        templateUrl: 'front/pages/editTemplate.html',
        controller: 'TemplateEditCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
		}
      }).
	  when('/administrer/editTemplate/:idTemplate', {
        templateUrl: 'front/pages/editTemplate.html',
        controller: 'TemplateEditCtrl',
		data : {
			authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
		}
      }).
	  when('/login', {
        templateUrl: 'front/pages/login.html',
        controller: 'LoginCtrl',
		data : {
			authorizedRoles: []
		}
      }).
	  when('/erreur/:type/:code', {
        templateUrl: 'front/pages/erreur.html',
        controller: 'ErreurCtrl',
		data : {
			authorizedRoles: []
		}
      }).
	  when('/accueil', {
		templateUrl: 'front/pages/accueil.html',
		data : {
			authorizedRoles: []
		}
	  }).
      otherwise({
        redirectTo: '/accueil',
		data : {
			authorizedRoles: []
		}
      });
  }]);