'use strict';

angular.module('myApp.friends', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/friends', {
            templateUrl: 'friends/friends.html',
            controller: 'friendsCtrl'
        })

            .when('/friend/add', {
                templateUrl: 'friends/add.html',
                controller: 'addFriendCtrl'

            }).when('/friend/view', {
                templateUrl: 'friends/view.html',
                controller: 'viewFriendCtrl'
        });
    }])

    .controller('friendsCtrl', ['$scope','$http', 'log', '$location', function($scope, $http, log, $location) {

        $scope.deleteFriend = (function ($friendId) {
            $http({
                method: "DELETE",
                url: 'http://localhost:8008/appartoo/web/app_dev.php/friends/' + $friendId,
                headers: {
                    'X-Auth-Token': log.token[0].value
                }
            })
                .then(function(result) {
                    /*$location.path('/friend/add');
                    $location.path('/friends');*/
                    console.log('things went well!', result);
                    $scope.users = result.data;


                }, function (err) {
                    console.error('things did not go so well', err);
                });
        });


        $scope.names = [];
        var $token = log.token[0].value;
        $http({
            method: "GET",
            url: 'http://localhost:8008/appartoo/web/app_dev.php/friendlist',
            headers: {
                'X-Auth-Token': $token
            }
        })
            .then(function(result) {
                $scope.names = result.data;
                console.table(result.data);

            }, function (err) {
                console.error('things did not go so well', err);
            });
    }])

    .controller('addFriendCtrl', ['$scope','$http', 'log', '$location', function($scope, $http, log, $location) {
        this.log = log;
        $scope.users = [];
        $http({
            method: "GET",
            url: 'http://localhost:8008/appartoo/web/app_dev.php/friends',
            headers: {
                'X-Auth-Token': log.token[0].value
            }
        })
            .then(function(result) {
                console.log('things went well!', result);

                $scope.users = result.data;

            }, function (err) {
                console.error('things did not go so well', err);
            });

        $scope.addFriend = function ($id) {
            console.log($id);
            $http({
                method: "POST",
                url: 'http://localhost:8008/appartoo/web/app_dev.php/friend/add-' + $id,
                headers: {
                    'X-Auth-Token': log.token[0].value
                }
            })
                .then(function(result) {
                    console.log('things went well!', result);

                    $scope.users = result.data;
                    $location.path('/friends');

                }, function (err) {
                    console.error('things did not go so well', err);
                });

        };

        $scope.newUser = {};
        $scope.registerNewUser = function (newUser) {
            newUser.password = "test";
            newUser.email = "test@email.com";
            $http.post('http://localhost:8008/appartoo/web/app_dev.php/friend', JSON.stringify(newUser))
                .then(function (response) {


                    $scope.addFriend(response.data.id);
                    if (response.data)
                        $scope.msg = "Post Data Submitted Successfully!";
                });
        }
    }])

    .controller('viewFriendCtrl', ['$scope', '$http', '$location', 'log', function ($scope, $http, $location, log) {
        this.log = log;
        $http({
            method: "GET",
            url: 'http://localhost:8008/appartoo/web/app_dev.php/friend/details/' + log.friendId,
            headers: {
                'X-Auth-Token': log.token[0].value
            }
        }).then(function (result) {
            $scope.info = result.data;

        })
    }]);