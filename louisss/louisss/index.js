console.log('connect index.js');
var app = angular.module('myAppIndex', []);
app.controller('myCtrlIndex', function ($scope) {
    console.log('connect index.js controller');
//   const queryString = window.location.search;
//   console.log(queryString);
//   const urlParams = new URLSearchParams(queryString);
//   const uuidBook = urlParams.get('uuidBook')
//   console.log(uuidBook);

  info();
  function info() {

    var settings = {
        "url": "http://localhost/php-api/index.php/api/User3/getallbook",
        "method": "POST",
        "timeout": 0,
      };
      
      $.ajax(settings).done(function (response) {
        // console.log(response);
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