console.log('connect password.js');
var app = angular.module('myApp2', []);
app.controller('myCtrl2', function($scope) {
  $scope.OTP = function(){
    console.log(document.getElementById("emailrepass").value);
    Swal.fire({
      title: 'Now Loading',
      html: 'Please wait...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    var settings = {
      "url": "http://localhost/php-api/index.php/api/User3/requestOTP",
      "method": "POST",
      "timeout": 0,
      "headers": {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      "data": {
        "email": document.getElementById("emailrepass").value
      }
    };
    
    $.ajax(settings).done(function (response) {
      console.log(response);
      
      if(response.message === "กรุณากรอกอีเมล"){
        Swal.fire({
          title: "ยังไม่ได้กรอกอีเมล",
          text: "กรุณากรอกอีเมล",
          icon: "error"
        });
      }
      if(response.message ==='กำลังส่ง OTP'){
        Swal.fire({
          title: 'กำลังส่ง OTP',
          text: 'ตรวจสอบในอีเมล',
          icon: 'success'
        });
      }
      if(response.message === "ไม่มีอีเมลในระบบ"){
        Swal.fire({
          title: "กรอกอีเมลไม่ถูกต้อง",
          text: "ไม่มีอีเมลในระบบ",
          icon: "error"
        })
      }
    });
  }

  $scope.Newpass = function(){
    console.log(document.getElementById("emailnewpass").value);
    console.log(document.getElementById("OTP").value);
    console.log(document.getElementById("ref").value);
    console.log(document.getElementById("newpassword").value);
    console.log(document.getElementById("newconpassword").value);
    Swal.fire({
      title: 'Now Loading',
      html: 'Please wait...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    var settings = {
      "url": "http://localhost/php-api/index.php/api/User3/resetpasswordOTP",
      "method": "POST",
      "timeout": 0,
      "headers": {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      "data": {
        "email": document.getElementById("emailnewpass").value,
        "OTP": document.getElementById("OTP").value,
        "ref": document.getElementById("ref").value,
        "password": document.getElementById("newpassword").value,
        "confirm_password": document.getElementById("newconpassword").value
      }
    };

    $.ajax(settings).done(function (response) {
      console.log(response);
      if(response.message === "กรอกข้อมูลให้ครบ"){
        Swal.fire({
          title: "กรอกข้อมูลไม่ครบ",
          text: "กรุณากรอกข้อมูลให้ครบ",
          icon: "error"
        });
      }
      if(response.message === "ไม่มีOTPหรือrefในระบบ"){
        Swal.fire({
          title: "ไม่มี OTP หรือ ref No. ในระบบ",
          text: "กรุณากรอก OTP หรือ ref No. ใหม่",
          icon: "error"
        });
      }
      if(response.message === "รหัสผ่านไม่ตรงกัน"){
        Swal.fire({
          title: "รหัสผ่านไม่ตรงกัน",
          text: "กรุณากรอกรหัสผ่านใหม่",
          icon: "error"
        });
      }
      if(response.message === "สำเร็จ"){
        Swal.fire({
          title: "เปลี่ยนรหัสผ่าน",
          text: "สำเร็จ",
          icon: "success"
        });
      }
    });
  }
  });