'use strict';

// Declare app level module which depends on views, and components
angular.module('myApp', [
    'ngRoute',
    'myApp.home',
    'myApp.friends',
    'myApp.login',
    /*'myApp.profile'*/
])
    .config(['$locationProvider', '$routeProvider', function($locationProvider, $routeProvider) {
        $locationProvider.hashPrefix('!');
        $routeProvider.otherwise({redirectTo: '/home'});
    }])

    .controller('MainCtrl', ['$scope', '$http', '$window', '$location', 'log',function ($scope, $http, $window, $location, log) {

        this.log = log;
        $scope.logout = function () {
            $http({
                method: "DELETE",
                url: "http://localhost:8008/appartoo/web/app_dev.php/auth-tokens/" + log.token[0].id,
                headers: {
                    'X-Auth-Token': log.token[0].value
                }
            })
                .then(function () {
                    log.clear();
                    $location.path('/#!/home');
                })
        };

        log.login();

    }])

    .factory('log', ['$http', '$location', function ($http, $location) {
        var $this = this;

        $this.postdata = function (data) {
            $http.post('http://localhost:8008/appartoo/web/app_dev.php/auth-tokens', JSON.stringify(data))
                .then(function () {
                    $location.path('/#!/home');

                    $this.login();
                });
        };
        $this.login = function(){
            $http.get('http://localhost:8008/appartoo/web/app_dev.php/auth-tokens')
                .then(function (result) {
                    $this.token = result.data;
                    console.log($this.token);
                    if ($this.token.length > 0) {
                        $this.connected = true
                    }
                })
        };
        $this.clear = function () {
            console.log('cleared');
            $this.token = null;
            $this.connected = false;
        };

        $this.viewFriend = function ($id) {
            $this.friendId = $id;
            $location.path('/friend/view');


        };

        return this;
    }

    ]);

