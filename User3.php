<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/PHPMailerAutoload.php';

include("src/JWT.php");
use \Firebase\JWT\JWT;

class User3 extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('checkparams');
        $this->load->model('Api/User3models');
        $this->db->query("set names utf8mb4");
    }
    //ลงทะเบียน
    public function Register_post(){
        $request = $this->post();
        $uuid = hash('sha512',Date('Y-m-d h:i:s'));
        
        if (empty($request["Fname"]) || empty($request["Lname"]) || empty($request["phone"]) || empty($request["email"]) || empty($request["password"]) || empty($request["address"]))
        {
            $data = ['status'=>false,'error'=>true,'message'=>'กรุณากรอกข้อมูลให้ครบ'];
            
            $this->response($data, MY_Controller::HTTP_OK);
        }
        if($request["password"] !== $request["confirm_password"])
        {
            $data = ['status'=>false,'error'=>true,'message'=>'รหัสผ่านไม่ตรงกัน'];
            $this->response($data, MY_Controller::HTTP_OK);
        }
        $res = $this-> User3models ->checkEmail($request);
        if($res !== empty($res)){
            $data = ['status'=>false,'error'=>true,'message'=>'อีเมลนี้ถูกใช้งานแล้ว'];
            $this->response($data, MY_Controller::HTTP_OK);
        }

        $data = $this-> User3models ->Register($request,$uuid);
        if ($data) {
            $data = ['status'=>true,'error'=>false,'message'=>'สมัครสมาชิกสำเร็จ'];
        }
        else {
            $data = ['status'=>false,'error'=>true,'message'=>'สมัครสมาชิก ไม่สำเร็จ'];
        }
        $this->response($data, MY_Controller::HTTP_OK);
    }
    //ล็อคอิน
    public function Login_post(){
        $request = $this->post();
        if(empty($request["email"]) || empty($request["password"])){
            $data = ['status'=>false,'error'=>true,'message'=>'กรุณากรอกข้อมูลให้ครบ'];
            $this->response($data, MY_Controller::HTTP_OK);
        }
        $data = $this-> User3models ->Login($request);
        if(count($data)){
            $token = $this->GETJWTtoken($data[0]);
            $data = ['status'=>true,'error'=>false,'message'=>'ยินดีต้อนรับสู่ระบบ','token' => $token];
            $this->User3models->Uptokenlogin($request, $token);
        }
        else{
            $data = ['status'=>false,'error'=>true,'message'=>'อีเมลและรหัสผ่านผิด'];
        }
        $this->response($data, MY_Controller::HTTP_OK);
    }
        
    //สร้างโทเท็น
    public function GETJWTtoken($data){
        $payload = array(
            'uuid'=>$data["uuid"],
            'email'=>$data['email'],
            'phone'=>$data['phone'],
            'timestamp'=> strtotime(date("Y-m-d H:i:s",strtotime("+24 hours"))),
        );
        $key = "SBTVC888";
        $token = JWT::encode($payload, $key);
        return $token;
    }
    //ถอดรหัสโทเท็น
    public function DecodeJWTtoken($token){
        $key = "SBTVC888";
        $payload = JWT::decode($token,$key,array('HS256'));
        return $payload;
    }
    //ถอดเวลาโทเท็น
    public function CheckTokenTime($timestamp){
        $time = strtotime(date('Y/m/d h:i:sa'));
        if($time>$timestamp){
            return true;
        }
        else{
            return false;
        }
    }
    //ตรวจโทเท็น
    public function CheckJWTtoken($token){
        $payload = $this -> DecodeJWTtoken($token);
        if($this-> CheckTokenTime($payload->timestamp)){
            $data = ['status'=>false,'error'=>true,'message'=>'โทเท็นหมดเวลาแล้ว'];
            $this->response($data, MY_Controller::HTTP_OK);
        }
        return $payload;
    }
    //อัพเดตโปร
    public function Updateprofile_post(){
        $request = $this->post();
        $header = apache_request_headers();
        $token = $header["X-Access-Token"];
        $payload = $this -> CheckJWTtoken($token);

        if (empty($request["Fname"]) || empty($request["Lname"]) || empty($request["phone"]) || empty($request["email"]) || empty($request["address"]))
        {
            $data = ['status'=>false,'error'=>true,'message'=>'กรุณากรองข้อมูลให้ครบ'];
            $this->response($data, MY_Controller::HTTP_OK);
        }

        $data = $this-> User3models ->Updateprofile($request,$payload -> email);
        
        $this->response($data, MY_Controller::HTTP_OK);
    }

    public function requestOTP_post(){
        $request = $this->post();
        if(empty($request["email"])){
            $data = ['status'=>false,'error'=>true,'message'=>'กรุณากรอกอีเมล'];
            $this->response($data, MY_Controller::HTTP_OK);
        }
        $data = $this-> User3models ->requestOTP($request);
        if(count($data)){
            $data = ['status'=>true,'error'=>false,'message'=>'กำลังส่ง OTP'];
            $this->otpchecktest($request["email"]);
            $this->SendEmail($request["email"]);
            $this->response($data, MY_Controller::HTTP_OK);
        }
        else{
            $data = ['status'=>false,'error'=>true,'message'=>'ไม่มีอีเมลในระบบ'];
        }
        $this->response($data, MY_Controller::HTTP_OK);
    }

    public function otpchecktest($email){
        $request["email"] = $email;
        $sql = "SELECT email , status , update_at
                FROM OTPref
                WHERE email='$email' AND status='001'
        ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        if($res !== empty($res)){
            $this->db->set('status','002');
            $this->db->where('email',$email);
            $query = $this ->db->update('OTPref');
        }
    }

    public function SendEmail($email) {

        $mail = new PHPMailer();
        $mail->CharSet = "utf-8";
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->port = 584;
        $mail->SMTPSecure = "tls";
        $mail->SMTPAuth = true;
        // port 465 584;

        $gmail_username = "phpmailer.555@gmail.com";
        $gmail_password = "pjyzylmjhwxegtjb";

        $sender = "One Time Password and Referrence No."; //หัวข้อ
        $email_sender = "phpmailer.555@gmail.com"; // ชื่อผู้ส่ง

        $email_user_send = $email;
        $email_receiver = $email_user_send;
        $subject = "Louisss book"; 

        $mail->Username = $gmail_username;
        $mail->Password = $gmail_password;
        $mail->setFrom($email_sender,$sender);
        $mail->addAddress($email_receiver);
        $mail->Subject = $subject;

        $OTP=sprintf("%06d", mt_rand(0, 999999));
        $ref=sprintf("%06d", mt_rand(0, 999999));
        $date=date('Y/m/d h:i:sa');
        $request["email"] = $email;
        $request["OTP"] = $OTP;
        $request["ref"] = $ref;
        $request["date"] = $date;
        $email_content = "
                            <!DOCTYPE html>
                            <html>
                            <body>
                                <p>email $email</p>
                                <p>OTP is $OTP</p>
                                <p>ref no. is $ref</p>
                                <p>date $date</p>
                            </body>
                            </html>

                        "; // เนื้อหาใน gmail

        $data = $this-> User3models ->OTPref($request);
        if($email_receiver){
            $mail->msgHTML($email_content);

            if(!$mail->send()){ // แสดง response email
                $this->response('false', MY_Controller::HTTP_OK);
            }else{
                // $this->response($request, MY_Controller::HTTP_OK);
            }

        }
    }

    public function resetpasswordOTP_post(){
        $request = $this->post();
        $data = $this->User3models ->cheackotp($request);
        if(empty($request["email"]) || empty($request["OTP"]) || empty($request["ref"]) || empty($request["password"]) || empty($request["confirm_password"])){
            $data = ['status'=>false,'error'=>true,'message'=>'กรอกข้อมูลให้ครบ'];
            $this->response($data, MY_Controller::HTTP_OK); 
        }
        if($data == false){
            $data = ['status'=>false,'error'=>true,'message'=>'ไม่มีOTPหรือrefในระบบ'];
            $this->response($data, MY_Controller::HTTP_OK);    
        }
        if($request['password'] !== $request["confirm_password"]){
            $data = ['status'=>false,'error'=>true,'message'=>'รหัสผ่านไม่ตรงกัน'];
            $this->response($date, MY_Controller::HTTP_OK);
        }
        $request['password'] = hash('sha512',$request['password']);
        $request['confirm_password'] = hash('sha512',$request['confirm_password']);
        $data = $this->User3models ->Resetpassword($request);
        $response =['status'=>true,'error'=>false,'message'=>'สำเร็จ'];
        $this->response($response, MY_Controller::HTTP_OK);

    }

    public function getmyprofile_post(){
        $request = $this->post();
        $data = $this->User3models->getmyprofile($request);
        if($data == true){
            $response = ['status'=>true,'error'=>false,'message'=>'get profile successful','Item'=>$data];
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'can not get profile'];
        }
        $this->response($response, MY_Controller::HTTP_OK);
    }

    public function addtocart_post(){
        $request = $this->post();
        $uuid = hash('sha512',Date('Y-m-d h:i:s'));
        $header = apache_request_headers();
        $token = $header["X-Access-Token"];
        $payload = $this -> CheckJWTtoken($token);
        $data = $this->User3models->checkcartstatus($request,$payload->uuid);
        $statuscart = $data["0"]["status"];
        $cartuuid = $data["0"]["uuid"];
        $amountcart = $data["0"]["amount"];
        $pricecart = $data["0"]["price"];
        if($statuscart==="001"){
            $data = $this-> User3models ->Updatescart($request ,$cartuuid,$payload->uuid ,$payload->email,$amountcart,$pricecart);
        }
        if($statuscart!=="001"){
            $data = $this-> User3models ->insertcart($request ,$uuid,$payload->uuid ,$payload->email);
        }
        $data = $this->User3models->decreasestock($request);
        $stockall = $data["res1"]["0"]["sum(stockAll)"];
        $amount = $data["res2"]["0"]["sum(amount)"];
        $stock = $stockall - $amount;
        if($data == true){
            $response = ['status'=>true,'error'=>false,'message'=>'successful','stockall'=>$stockall, 'amount'=>$amount, 'stock'=>$stock];
            $this->User3models->Updatestock($request,$stock,$amount);
            $this->response($response, MY_Controller::HTTP_OK);
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'not successful'];
            $this->response($response, MY_Controller::HTTP_OK);
        }
    }

    public function checkcartstatus_post(){
        $request = $this->post();
        $header = apache_request_headers();
        $token = $header["X-Access-Token"];
        $payload = $this -> CheckJWTtoken($token);
        $data = $this->User3models->checkcartstatus($request,$payload->uuid);
        $cc = $data["0"]["uuid"];
        $this->response($data, MY_Controller::HTTP_OK);
        if($data == true){
            $response = ['status'=>true,'error'=>false,'message'=>'successful'];
            $this->response($data, MY_Controller::HTTP_OK);
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'not successful'];
            $this->response($data, MY_Controller::HTTP_OK);
        }
    }

    public function Getbookfromuuid_post(){
        $request = $this->post();
        $data = $this->User3models->Getbookfromuuid($request);
        if($data == true){
            $response = ['status'=>true,'error'=>false,'message'=>'get book','Item'=>$data];
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'can not get book'];
        }
        $this->response($response, MY_Controller::HTTP_OK);
    }

    public function headercart_post(){
        $request = $this->post();
        $header = apache_request_headers();
        $token = $header["X-Access-Token"];
        $payload = $this -> CheckJWTtoken($token);
        $data = $this->User3models->getbookcart($request,$payload -> uuid);
        $amount = $data["0"]["sum(ca.amount)"];
        $price = $data["0"]["sum(ca.price)"];
        if($data == true){
            $response = ['status'=>true,'error'=>false,'message'=>'get cart head','amount'=>$amount,'prince'=>$price];
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'cannot get cart head'];
        }
        
        $this->response($response, MY_Controller::HTTP_OK);
    }

    public function getallbook_post(){
        $request = $this->post();
        $Actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        $data = $this->User3models->getallbook();

        for ($x = 0 ; $x < count($data) ; $x++) {
            $data[$x]["urlImage"] = $Actual_link.''.$data[$x]["image"];
        }

        $this->response($data, MY_Controller::HTTP_OK);
    }

    public function getallcate_post(){
        $request = $this->post();
        $Actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        $data = $this->User3models->getallcate();

        for ($x = 0 ; $x < count($data) ; $x++) {
            $data[$x]["urlImage"] = $Actual_link.''.$data[$x]["pathfile"];
        }
        $this->response($data, MY_Controller::HTTP_OK);
    }
    public function Getcatefromuuid_post(){
        $request = $this->post();
        $Actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $data = $this->User3models->Getcatefromuuid($request);
        for ($x = 0 ; $x < count($data) ; $x++) {
            $data[$x]["urlImage"] = $Actual_link.''.$data[$x]["pathfile"];
        }
        if($data == true){
            $response = ['status'=>true,'error'=>false,'message'=>'get book'];
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'can not get book'];
        }
        $this->response($data, MY_Controller::HTTP_OK);
    }
    public function Getallbookfromcate_post(){
        $request = $this->post();
        $Actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $data = $this->User3models->Getallbookfromcate($request);
        for ($x = 0 ; $x < count($data) ; $x++) {
            $data[$x]["urlImage"] = $Actual_link.''.$data[$x]["image"];
        }
        if($data == true){
            $response = ['status'=>true,'error'=>false,'message'=>'get book'];
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'can not get book'];
        }
        $this->response($data, MY_Controller::HTTP_OK);
    }
    public function GetBookInCart_post(){
        $request = $this->post();
        $header = apache_request_headers();
        $token = $header["X-Access-Token"];
        $payload = $this -> CheckJWTtoken($token);
        $Actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        
        $data = $this->User3models->Getallbookincart($request,$payload -> uuid);
        $amount = $data["res2"]["0"]["sum(ca.amount)"];
        $price = $data["res2"]["0"]["sum(ca.price)"];
        $data = $data["res1"];
        for ($x = 0 ; $x < count($data) ; $x++) {
            $data[$x]["urlImage"] = $Actual_link.''.$data[$x]["image"];
        }
        if($data == true){
            $response = ['status'=>true,'error'=>false,'message'=>'get cart','Item'=>$data,'amount'=>$amount,'prince'=>$price];
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'cannot get cart'];
        }
        
        $this->response($response, MY_Controller::HTTP_OK);
    }
    public function CancelBookinCart_post(){
        $request = $this->post();
        $header = apache_request_headers();
        $token = $header["X-Access-Token"];
        $payload = $this -> CheckJWTtoken($token);
        $data = $this->User3models->CancelThisBookinCart($request);
        $data = $this->User3models->CanceltoStock($request);
        $book_stock=$data["0"]["book_stock"];
        $book_sold=$data["0"]["book_sold"];
        $cart_amount=$data["0"]["cart_amount"];
        $upbook_stock= $book_stock + $cart_amount;
        $upbook_sold= $book_sold - $cart_amount;
        
        if($data !== empty($data)){
            $response = ['status'=>true,'error'=>false,'message'=>'updated'];
            $data = $this->User3models->ReturnBookfromCart($request,$upbook_stock,$upbook_sold);
            $this->response($response, MY_Controller::HTTP_OK);
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'cannot updated'];
        }
        $this->response($data, MY_Controller::HTTP_OK);
    }

    public function testcancel_post(){
        $request = $this->post();
        $data = $this->User3models->CanceltoStock($request);
        $book_stock=$data["0"]["book_stock"];
        $book_sold=$data["0"]["book_sold"];
        $cart_amount=$data["0"]["cart_amount"];
        $upbook_stock= $book_stock + $cart_amount;
        $upbook_sold= $book_sold - $cart_amount;
        $this->response($upbook_sold, MY_Controller::HTTP_OK);
    }
}
