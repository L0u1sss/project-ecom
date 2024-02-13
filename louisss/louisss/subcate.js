console.log('connect subcate.js');
var app = angular.module('myAppSub', []);
app.controller('myCtrlSub', function ($scope) {
  const queryString = window.location.search;
  console.log(queryString);
  const urlParams = new URLSearchParams(queryString);
  const uuidCate = urlParams.get('uuidCate')
  console.log(uuidCate);

  info();
  bookincate();
  function info() {

    var settings = {
        "url": "http://localhost/php-api/index.php/api/User3/Getcatefromuuid",
        "method": "POST",
        "timeout": 0,
        "headers": {
          "Content-Type": "application/x-www-form-urlencoded"
        },
      "data": {
        "uuid": uuidCate
      }
    };

    $.ajax(settings).done(function (response) {
        console.log(response);
        if (response) {
            let item = response;
            console.log(item);
            $scope.name = item[0].name;
            console.log(item[0].name);
            $scope.$apply();
          }
      });
  }
  function bookincate(){
    var settings = {
        "url": "http://localhost/php-api/index.php/api/User3/Getallbookfromcate",
        "method": "POST",
        "timeout": 0,
        "headers": {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        "data": {
          "uuid": uuidCate
        }
      };
      
      $.ajax(settings).done(function (response) {
        console.log(response);
        if (response) {
            let book = response;
            console.log(book);
            $scope.book = book;

            console.log($scope.bookname);
            $scope.$apply();
          }
      });
  }
});