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
      let sum = response.res2;
      console.log(sum);
      let item = response.res1;
      console.log(item);
    });
  }
});