var app = angular.module('search', []);
app.controller('searchControl', function($scope, $http) {
    $http.get("http://localhost:8000/appartoo/web/app_dev.php/usersjson")
        .then(function(response) {
            $scope.liste = response.data;
        });
});
