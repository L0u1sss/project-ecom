console.log('connect checkout.js');
var app = angular.module('myAppCart', []);
app.controller('myCtrlCart', function ($scope) {
  console.log('connect checkout.js controller');
  $scope.k = [];

  info();
  function info() {
    var settings = {
      "url": "http://localhost/php-api/index.php/api/User3/GetBookInCart",
      "method": "POST",
      "timeout": 0,
      "headers": {
        "X-Access-Token": localStorage.getItem("token")
      },
    };

    $.ajax(settings).done(function (response) {
      console.log(response);
      if (response) {
        let item = response;
        console.log(item);
        let k = response.Item;
        console.log(k);
        $scope.k = k;
        console.log($scope.k);
        $scope.name = item.Item[0].Fname;
        $scope.sumamount = item.amount
        $scope.sumprince = item.prince
        console.log($scope.sumprince);
        // $scope.x = item.Item.Fname;
        // console.log($scope.item);
        // $scope.image = "http://localhost/" + item.Item.image;
        $scope.$apply();
      }
    });
  }

  $scope.checkouttoconfirm = function () {
    var settings = {
      "url": "http://localhost/php-api/index.php/api/User3/checkouttoconfirm",
      "method": "POST",
      "timeout": 0,
      "headers": {
        "X-Access-Token": localStorage.getItem("token")
      },
    };

    $.ajax(settings).done(function (response) {
      console.log(response);
      if(response==='checked out'){
        Swal.fire({
          title: 'hi',
          text: '',
          icon: 'success'
        });
      }
    });
  }
});