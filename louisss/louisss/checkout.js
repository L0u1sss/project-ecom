console.log('connect checkout.js');
var app = angular.module('myAppCart', []);
app.controller('myCtrlCart', function ($scope) {
  console.log('connect cart.js controller');
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

  $scope.cancel = function(uuid, book_uuid){
    console.log(uuid);
    console.log(book_uuid);

    var settings = {
      "url": "http://localhost/php-api/index.php/api/User3/CancelBookinCart",
      "method": "POST",
      "timeout": 0,
      "headers": {
        "X-Access-Token": localStorage.getItem("token"),
        "Content-Type": "application/x-www-form-urlencoded"
      },
      "data": {
        "book_uuid": book_uuid,
        "cart_uuid": uuid
      }
    };
    
    $.ajax(settings).done(function (response) {
      console.log(response);
      if(response.message === "updated"){
        Swal.fire({
          title: 'ลบสินค้าสำเร็จ',
          text: '',
          icon: 'success'
        });
        setTimeout(function(){
          location.reload();
      }, 1000);
      }
    });
  }
});