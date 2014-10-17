var agendaServices = angular.module('agendaServices', ['ngResource']);
var authServices = angular.module('authServices', ['ngResource']);
var userServices = angular.module('userServices', ['ngResource']);
var persoServices = angular.module('persoServices', ['ngResource']);
var jeuServices = angular.module('jeuServices', ['ngResource']);
var eventServices = angular.module('eventServices', ['ngResource']);

agendaServices.factory('Agenda', ['$resource',
  function($resource){
    return $resource('back/services/calendrier/getCalendrier.php', {annee:'', mois:''});
  }]);
  
userServices.factory('User', function ($http) {
	return {
		get : function(idPerso) {
			return $http({
				url:'back/services/user/getUser.php', 
				method: "GET"
			});
		},
		getAll : function() {
			return $http({
				url:'back/services/user/getAllUsers.php', 
				method: "GET"
			});
		},
		getAllRoleUser : function() {
			return $http({
				url:'back/services/user/admin/getAllRolesUser.php', 
				method: "GET"
			});
		},
		updateRoleUser : function(role, idUser) {
			return $http({
				url:'back/services/user/admin/updateRoleUser.php', 
				method: "GET",
				params: {role:role, idUser:idUser}
			});
		},
		saveDroitAdminJeu : function(idUser, idJeu) {
			return $http({
				url:'back/services/user/admin/saveUserAdminJeu.php', 
				method: "GET",
				params: {idUser:idUser, idJeu:idJeu}
			});
		},
		deleteDroitAdminJeu : function(idUser, idJeu) {
			return $http({
				url:'back/services/user/admin/deleteUserAdminJeu.php', 
				method: "GET",
				params: {idUser:idUser, idJeu:idJeu}
			});
		}
	}
});
    
persoServices.factory('Personnage', function ($http) {
    return {
		get : function(idPerso) {
			return $http({
				url:'back/services/user/getPerso.php', 
				method: "GET",
				params: {idPerso:idPerso}
			});
		},
		sauvegarder : function(personnage) {
			return $http.post('back/services/user/savePerso.php', personnage);
		},
		supprimer : function(idPerso) {
			return $http({
				url : 'back/services/user/deletePerso.php',
				method: "GET",
				params: {idPerso:idPerso}
			});
		},
		getAll : function() {
			return $http({
				url:'back/services/user/getAllPerso.php', 
				method: "GET"
			});
		}
	};
});
  
authServices.factory('AuthService', function ($http, Session, $cookieStore, $q) {
  return {
    login: function (credentials) {
		var d = $q.defer();
//		$http({
//			method: 'POST',
//			url: '/endlessraider/back/services/user/connectUser.php',
//			data:{credentials: credentials},
//			headers: {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
//		})
		$http.post('/endlessraider/back/services/user/connectUser.php', credentials)
        .then(function (res) {
			if(res.data.success) {
				Session.create(res.data.user.id, res.data.user.login, res.data.user.role.code);
				d.resolve(res);
			} else {
				Session.destroy();
				d.reject(res.data.message);
			}
        });
		return d.promise;
    },
	logout: function() {
		return $http
        .post('/endlessraider/back/services/user/disconnectUser.php')
        .then(function (res) {
			Session.destroy();
		});
	},
    isAuthenticated: function () {
		return (!!Session.userId);
    },
    isAuthorized: function (authorizedRoles) {
      if (!angular.isArray(authorizedRoles)) {
        authorizedRoles = [authorizedRoles];
      }
	  authorise = false;
	  if( authorizedRoles.indexOf('*') !== -1 || authorizedRoles.indexOf(Session.userRole) !== -1 ) {
		authorise = true;
	  }

	  return (this.isAuthenticated() && authorise );
    },
    getSession: function () {
        return Session.get();
    }
  };
});

