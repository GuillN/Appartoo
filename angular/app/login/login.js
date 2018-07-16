'use strict';

angular.module('myApp.login', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/login', {
            templateUrl: 'login/login.html',
            controller: 'loginCtrl'
        }).when('/register', {
            templateUrl: 'login/register.html',
            controller: 'registerCtrl'
        });
    }])

    .controller('loginCtrl', ['$scope', '$http', '$location','log', function($scope, $http, $location, log) {
        this.log = log;
        $scope.data = {};
        log.postdata($scope.data) /*{
            $http.post('http://localhost:8008/appartoo/web/app_dev.php/auth-tokens', JSON.stringify(data))
                .then(function (response) {
                    $location.path('/#!/home');

                    if (response.data)
                        $scope.msg = "Post Data Submitted Successfully!";
                    log.login();
                });
        };*/
    }])

    .controller('registerCtrl', ['$http','$scope','$location', 'log', function ($http, $scope, $location, log) {
        this.log = log;
        $scope.newData = {};
        var creds = {};

        $scope.registerUser = function (newData) {
            $http.post('http://localhost:8008/appartoo/web/app_dev.php/friend', JSON.stringify(newData))
                .then(function (response) {
                    creds.login = newData.username;
                    creds.password = newData.password;
                    log.postdata(creds);

                    if (response.data)
                        $scope.msg = "Post Data Submitted Successfully!";
                });
        }
    }]);