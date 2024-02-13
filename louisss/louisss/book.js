console.log('connect book.js');
var app = angular.module('myApp3', []);
app.controller('myCtrl3', function ($scope) {
  const queryString = window.location.search;
  console.log(queryString);
  const urlParams = new URLSearchParams(queryString);
  const uuidBook = urlParams.get('uuidBook')
  console.log(uuidBook);

  info();
  function info() {

    var settings = {
      "url": "http://localhost/php-api/index.php/api/User3/Getbookfromuuid",
      "method": "POST",
      "timeout": 0,
      "headers": {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      "data": {
        "uuid": uuidBook
      }
    };

    $.ajax(settings).done(function (response) {
      console.log(response);
      
      console.log($scope.image);
      if (response.status == true) {
        let item = response.Item[0];
        console.log(item);
        $scope.name = item.name;
        $scope.author = item.author;
        $scope.category_name = item.category_name;
        $scope.price = Number(item.price);
        $scope.stock = item.stock;
        $scope.uuid = item.uuid;
        $scope.cateuuid = item.cateuuid
        $scope.image = "http://localhost/" + item.image;
        $scope.$apply();
      }

    })
  }

  $scope.Buy = function () {
    console.log(document.getElementById("quantity").value);
    console.log($scope.uuid);
    let quantity = Number(document.getElementById("quantity").value);
    console.log(quantity);
    console.log($scope.price);

    let price_sum = quantity * $scope.price;
    console.log(price_sum);
    if (quantity > 0 && quantity <= $scope.stock) {
      var settings = {
        "url": "http://localhost/php-api/index.php/api/User3/addtocart",
        "method": "POST",
        "timeout": 0,
        "headers": {
          "X-Access-Token": localStorage.getItem("token"),
          "Content-Type": "application/x-www-form-urlencoded"
        },
        "data": {
          "book_uuid": $scope.uuid,
          "amount": document.getElementById("quantity").value,
          "price": price_sum,
        }
      };

    }
    else {
    }
    $.ajax(settings).done(function (response) {
      console.log(response);
      if (quantity == 0) {
        Swal.fire({
          title: "ยังไม่ได้กรอกจำนวนสินค้า",
          text: "กรุณากรอกจำนวนสินค้า",
          icon: "error"
        });
      }
      if (quantity > $scope.stock) {
        Swal.fire({
          title: "ของในสต๊อกไม่พอ",
          text: "ขออภัย",
          icon: "error"
        });
      }
      if (quantity >= 1 && quantity <= $scope.stock && response.message == "successful") {
        Swal.fire({
          title: "สำเร็จ",
          text: "ใส่ตะกร้าเรียบร้อย",
          icon: "success"
        });
        setTimeout(function () {
          location.reload();
        }, 3000);
      }
      if (response.message === "โทเท็นหมดเวลาแล้ว") {
        Swal.fire({
          title: "กรุณาloginใหม่",
          text: "",
          icon: "error"
        });
      }
    });
  };
});