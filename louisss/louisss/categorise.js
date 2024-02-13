console.log('connect categories.js');
var app = angular.module('myAppCate', []);
app.controller('myCtrlCate', function ($scope) {
    console.log('connect categories.js controller');

  info();
  function info() {

    var settings = {
      "url": "http://localhost/php-api/index.php/api/User3/getallcate",
      "method": "POST",
      "timeout": 0,
    };
          
      $.ajax(settings).done(function (response) {
        console.log(response);
        if (response) {
            let item = response;
            console.log(item);
            $scope.item = item;
            console.log($scope.item);
            // $scope.id = item.id;
            // $scope.name = item.name;
            // $scope.uuid = item.uuid;
            $scope.image = "http://localhost/" + item.image;
            $scope.$apply();
          }
      });
  }

});