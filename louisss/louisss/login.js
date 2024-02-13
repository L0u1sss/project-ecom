console.log('connect login.js');
var app = angular.module('myApp1', []);
app.controller('myCtrl1', function($scope) {
    $scope.Login = function(){
        console.log(document.getElementById("logemail").value);
        console.log(document.getElementById("logpassword").value);
    var settings = {
        "url": "http://localhost/php-api/index.php/api/User3/Login",
        "method": "POST",
        "timeout": 0,
        "headers": {
          "Content-Type": "application/x-www-form-urlencoded",
          "X-Access-Token": localStorage.getItem("token")
        },
        "data": {
          "email": document.getElementById("logemail").value,
          "password": document.getElementById("logpassword").value
        }
      };
      
      $.ajax(settings).done(function (response) {
        console.log(response);
        if(response.message === "กรุณากรอกข้อมูลให้ครบ"){
          Swal.fire({
            title: "กรอกข้อมูลไม่ครบ",
            text: "กรุณากรอกข้อมูลให้ครบ",
            icon: "error"
          });
        }
        if(response.message === "ยินดีต้อนรับสู่ระบบ"){
          localStorage.setItem("token", response.token);
          Swal.fire({
            title: 'ยินดีต้อนรับ',
            text: 'เข้าสู่เว็บไซต์',
            icon: 'success'
          });
          setTimeout(function(){
            location.reload();
        }, 3000);
        }
        if(response.message === "อีเมลและรหัสผ่านผิด"){
          Swal.fire({
            title: "อีเมลหรือรหัสผ่านไม่ถูกต้อง",
            text: "กรุณากรอกอีเมลหรือรหัสผ่านให้ถูกต้อง",
            icon: "error"
          })
        }
      });
    };

    $scope.Register = function(){
      console.log(document.getElementById("reFname").value);
      console.log(document.getElementById("reLname").value);
      console.log(document.getElementById("rephone").value);
      console.log(document.getElementById("readdress").value);
      console.log(document.getElementById("reemail").value);
      console.log(document.getElementById("repassword").value);
      console.log(document.getElementById("reconpassword").value);
  
      var settings = {
          "url": "http://localhost/php-api/index.php/api/User3/Register",
          "method": "POST",
          "timeout": 0,
          "headers": {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          "data": {
            "Fname": document.getElementById("reFname").value,
            "Lname": document.getElementById("reLname").value,
            "phone": document.getElementById("rephone").value,
            "email": document.getElementById("reemail").value,
            "password": document.getElementById("repassword").value,
            "confirm_password": document.getElementById("reconpassword").value,
            "address": document.getElementById("readdress").value,
          }
        };
        
        $.ajax(settings).done(function (response) {
          // console.log(response);
          if(response.message === "กรุณากรอกข้อมูลให้ครบ"){
            Swal.fire({
              title: "กรอกข้อมูลไม่ครบ",
              text: "กรุณากรอกข้อมูลให้ครบ",
              icon: "error"
            });
          }
          if(response.message === "อีเมลนี้ถูกใช้งานแล้ว"){
            Swal.fire({
              title: "อีเมลนี้ถูกใช้งานแล้ว",
              text: "กรุณาใช้อีเมลใหม่",
              icon: "error"
            });
          }
          if(response.message === "สมัครสมาชิกสำเร็จ"){
            Swal.fire({
              title: 'สมัครสมาชิกสำเร็จ',
              text: 'ยินดีต้อนรับ',
              icon: 'success'
            });
            setTimeout(function(){
              location.reload();
          }, 3000);
          }
          if(response.message === "รหัสผ่านไม่ตรงกัน"){
            Swal.fire({
              title: "รหัสผ่านไม่ตรงกัน",
              text: "กรุณากรอกรหัสผ่านให้ตรงกัน",
              icon: "error"
            })
          }
          if(response.message === "สมัครสมาชิก ไม่สำเร็จ"){
            Swal.fire({
              title: "สมัครสมาชิก ไม่สำเร็จ",
              icon: "error"
            })
          }
        });
  };
  });