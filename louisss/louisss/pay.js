console.log('connect pay.js');
var app = angular.module('myAppPay', []);
app.controller('myCtrlPay', function ($scope) {
  console.log('connect pay.js controller');
  const queryString = window.location.search;
  console.log(queryString);
  const urlParams = new URLSearchParams(queryString);
  const uuidSureorder = urlParams.get('uuidSureorder')
  console.log(uuidSureorder);
  info();
  function info() {
    var settings = {
      "url": "http://localhost/php-api/index.php/api/User3/GetInfoinSureOrder",
      "method": "POST",
      "timeout": 0,
      "headers": {
        "X-Access-Token": localStorage.getItem("token"),
        "Content-Type": "application/x-www-form-urlencoded"
      },
      "data": {
        "sureorder": uuidSureorder
      }
    };
    
    $.ajax(settings).done(function (response) {
      console.log(response);
      let address = response.res3;
      console.log(address);
      $scope.name = address[0].Fname;
      $scope.lname = address[0].Lname;
      $scope.address = address[0].address;
      $scope.phone = address[0].phone;
      let sum = response.res2;
      console.log(sum);
      $scope.amount = sum[0].amount;
      $scope.price = sum[0].price;
      let item = response.res1;
      $scope.item = item;
      console.log(item);
      $scope.$apply();
    });
  }
});