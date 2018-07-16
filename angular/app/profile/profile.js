'use strict';

angular.module('myApp.profile', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/profile', {
            templateUrl: 'profile/profile.html',
            controller: 'profileCtrl'
        });
    }])

    .controller('profileCtrl', ['$scope', '$http', '$location','log', function($scope, $http, $location, log) {
        this.log = log;

    }]);