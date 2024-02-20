<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Bangkok');

class User3models extends CI_Model
{
    public function Register($request, $uuid)
    {
        $password=hash('sha512',$request['password']);
        $confirm_password=hash('sha512',$request['confirm_password']);
        
        $this->db->set('uuid',$uuid);
        $this->db->set('Fname',$request["Fname"]);
        $this->db->set('Lname',$request["Lname"]);
        $this->db->set('phone',$request["phone"]);
        $this->db->set('email',$request["email"]);
        $this->db->set('password',$password);
        $this->db->set('confirm_password',$confirm_password);
        $this->db->set('address',$request["address"]);
        $this->db->set('create_at',Date('Y-m-d h:i:s'));
        $this->db->set('create_by',$request["email"]);
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by',$request["email"]);
        $query = $this ->db->insert('user');

        $status = false;
        if ($query) {
            $status = true;
        } 
        
        return $status;
    }
    public function Login($request){
        $email=$request['email'];
        $password=hash('sha512',$request['password']);
        //เช็คในฐานข้อมูล
        $sql = "SELECT *
                FROM user
                WHERE email='$email' && password='$password'
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        return $res;
    }

    public function Uptokenlogin($request, $token){
        $this->db->set('token',$token);
        $this->db->where('email',$request["email"]);
        $query = $this ->db->update('user');
    }

    public function Updateprofile($request){
        $this->db->set('Fname',$request["Fname"]);
        $this->db->set('Lname',$request["Lname"]);
        $this->db->set('phone',$request["phone"]);
        $this->db->set('address',$request["address"]);
        $this->db->set('create_at',Date('Y-m-d h:i:s'));
        $this->db->set('create_by',$request["email"]);
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by',$request["email"]);
        $this->db->set('email',$request["email"]);
        $this->db->where('uuid',$request["uuid"]);

        $query = $this ->db->update('user');
        
        $status = false;
        if ($query) {
            $status = true;
        } 
        
        return $status;
    }
    public function requestOTP($request){
        $email=$request['email'];
        $sql = "SELECT *
                FROM user
                WHERE email='$email'
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        return $res;
        
    }
    public function getmyprofile($request){
        $uuid=$request['uuid'];
        $sql = "SELECT u.Fname as userFirstname, u.Lname as userLastname, u.phone as userphone, u.email as useremail, u.password as userpassword, u.address as useraddress
                FROM user u
                WHERE uuid='$uuid'
                ";
        
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }
    public function OTPref($request){
        $this->db->set('OTP',$request["OTP"]);
        $this->db->set('ref',$request["ref"]);
        $this->db->set('email',$request["email"]);
        $this->db->set('create_at',Date('Y-m-d h:i:s'));
        $this->db->set('create_by',$request["email"]);
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by',$request["email"]);
        $this->db->set('status','001');
        $query = $this ->db->insert('OTPref');

        $status = false;
        if ($query) {
            $status = true;
        } 
        
        return $status;
    }
    public function cheackotp($request){
        $email = $request['email'];
        $ref = $request['ref'];
        $OTP = $request['OTP'];
        $sql = "SELECT id
                FROM OTPref
                WHERE email='$email' && OTP='$OTP' && ref='$ref' && status='001'
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        $status = false;
        if(count($res)){
            $status = true;

        }
        return $status;
        
    }
    public function Resetpassword($request){
        $this->db->set('password',$request['password']);
        $this->db->set('confirm_password',$request["confirm_password"]);
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by',$request["email"]);
        $this->db->where('email',$request["email"]);
        $query = $this ->db->update('user');

        $this->db->set('status','002');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by',$request["email"]);
        $this->db->where('OTP',$request["OTP"]);
        $this->db->where('ref',$request["ref"]);
        $this->db->where('email',$request["email"]);
        $query = $this ->db->update('OTPref');
    }
    public function checkEmail($request){
        $email = $request['email'];
        $sql = "SELECT id
                FROM user
                WHERE email='$email'
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        return $res;
    }
    public function insertcart($request,$uuid,$user_uuid,$email){
        $this->db->set('uuid',$uuid);
        $this->db->set('user_uuid',$user_uuid);
        $this->db->set('book_uuid',$request['book_uuid']);
        $this->db->set('amount',$request['amount']);
        $this->db->set('price',$request['price']);
        $this->db->set('create_at',Date('Y-m-d h:i:s'));
        $this->db->set('create_by',$email);
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by',$email);
        $this->db->set('status','001');
        $query = $this ->db->insert('cart');
    }

    public function Updatescart($request,$cartuuid,$user_uuid,$email,$amountcart,$pricecart){
        $newamount = $amountcart + $request['amount'];
        $newprice = $pricecart + $request['price'];
        $this->db->set('amount',$newamount);
        $this->db->set('price',$newprice);
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by',$email);
        $this->db->where('user_uuid',$user_uuid);
        $this->db->where('uuid',$cartuuid);
        $this->db->where('status','001');
        $query = $this ->db->update('cart');
    }

    public function checkcartstatus($request,$user_uuid){
        $book_uuid=$request['book_uuid'];
        $sql = "SELECT book_uuid,status ,uuid ,amount ,price
                FROM cart
                WHERE user_uuid = '$user_uuid' AND book_uuid = '$book_uuid' AND status='001'
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }

    public function decreasestock($request){
        $uuid=$request['book_uuid'];
        
        $sql1 = "SELECT sum(stockAll)
                FROM book
                WHERE uuid='$uuid'
                ";
        $query1 = $this ->db->query($sql1);
        $res1 = $query1->result_array();
        
        $sql2 = "SELECT sum(amount)
                FROM cart
                WHERE book_uuid='$uuid'
                ";
        $query2 = $this ->db->query($sql2);
        $res2 = $query2->result_array();
        
        $data = ["res1" => $res1, "res2" => $res2];
        return $data;
    }
    public function Updatestock($request,$stock,$amount){
        $uuid=$request['book_uuid'];
        $this->db->set('stock',$stock);
        $this->db->set('sold',$amount);
        $this->db->where('uuid',$uuid);
        $query = $this ->db->update('book');
    }

    public function Getbookfromuuid($request){
        $uuid=$request['uuid'];
        $sql = "SELECT b.uuid, b.name, c.name as category_name, a.name as author,b.image, b.price, b.stockAll, b.stock, b.sold, b.create_at , c.uuid as cateuuid
                FROM book b
                INNER JOIN category c
                ON b.category_uuid = c.uuid
                INNER JOIN author a
                ON b.author_uuid = a.uuid
                WHERE b.uuid='$uuid'
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }

    public function getbookcart($request,$uuid){
        $sql = "SELECT u.email as username, sum(ca.amount), sum(ca.price)
                FROM cart ca
                INNER JOIN user u
                ON u.uuid = ca.user_uuid
                WHERE ca.user_uuid = '$uuid' AND ca.status = '001'
                ";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }
    public function getallbook(){
        $sql = "SELECT *
                FROM book
                ORDER BY id desc
                ";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }
    public function getallcate(){
        $sql = "SELECT *
                FROM category
                ORDER BY id asc
                ";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }

    public function Getcatefromuuid($request){
        $uuid=$request['uuid'];
        $sql = "SELECT *
                FROM category
                WHERE uuid='$uuid'
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }

    public function Getallbookfromcate($request){
        $uuid=$request['uuid'];
        $sql = "SELECT b.id , b.uuid ,b.name , b.image
                FROM book b
                INNER JOIN category ca
                ON b.category_uuid = ca.uuid
                WHERE b.category_uuid='$uuid'
                ORDER BY b.id desc
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }
    public function Getallbookincart($request,$uuid){
        $sql1 = "SELECT ca.book_uuid, ca.amount, ca.price, b.name, b.image, u.Fname, ca.uuid
                FROM cart ca
                INNER JOIN book b
                ON b.uuid = ca.book_uuid
                INNER JOIN user u
                ON u.uuid = ca.user_uuid
                WHERE ca.user_uuid='$uuid' AND ca.status ='001'
                ORDER BY ca.update_at DESC
                ";
        $query1 = $this->db->query($sql1);
        $res1 = $query1->result_array();
        
        $sql2 = "SELECT sum(ca.amount), sum(ca.price)
                FROM cart ca
                WHERE ca.user_uuid='$uuid' AND ca.status ='001'
                ";
        $query2 = $this->db->query($sql2);
        $res2 = $query2->result_array();
        
        $data = ["res1" => $res1, "res2" => $res2];
        return $data;
    }

    public function CancelThisBookinCart($request){
        $cart_uuid = $request['cart_uuid'];
        $book_uuid = $request['book_uuid'];
        $this->db->set('status','009');
        $this->db->where('uuid',$cart_uuid);
        $this->db->where('book_uuid',$book_uuid);
        $query = $this ->db->update('cart');
    }

    public function CanceltoStock($request){
        $book_uuid=$request['book_uuid'];
        $cart_uuid=$request['cart_uuid'];
        $sql = "SELECT b.uuid as book_uuid, b.stock as book_stock, b.sold as book_sold, ca.amount as cart_amount, ca.price as cart_price
                FROM book b
                INNER JOIN cart ca
                ON b.uuid = ca.book_uuid
                WHERE b.uuid ='$book_uuid' AND ca.uuid ='$cart_uuid' AND ca.status = '009'
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }

    public function ReturnBookfromCart($request,$upbook_stock,$upbook_sold){
        $uuid=$request['book_uuid'];
        $this->db->set('stock',$upbook_stock);
        $this->db->set('sold',$upbook_sold);
        $this->db->where('uuid',$uuid);
        $query = $this ->db->update('book');
    }

    public function Updatecartcheckouttoconfirm($cart_uuid){
        $this->db->set('status','002');
        $this->db->where('uuid',$cart_uuid);
        $query = $this ->db->update('cart');
    }

    public function GetOrderincart($user_uuid){
        $sql = "SELECT sureorder_uuid
                FROM cart
                WHERE cart.status = '002' AND cart.user_uuid = '$user_uuid'
                ";
        $query = $this ->db->query($sql);
        $res = $query->result_array();
        if($res){
            return $res;
        }
        else{
            return false;
        }
    }

    public function GenOrderuuid($request,$user_uuid,$user_email){
        $sureorder_uuid = hash('sha512',Date('Y-m-d h:i:s'));
        $this->db->set('sureorder_uuid',$sureorder_uuid);
        $this->db->where('status','002');
        $this->db->where('user_uuid',$user_uuid);
        $this->db->where('sureorder_uuid','');
        $query = $this ->db->update('cart');

        $this->db->set('uuid',$sureorder_uuid);
        $this->db->set('Fname',$request['Fname']);
        $this->db->set('Lname',$request['Lname']);
        $this->db->set('phone',$request['phone']);
        $this->db->set('email',$request['email']);
        $this->db->set('address',$request['address']);
        $this->db->set('create_at',Date('Y-m-d h:i:s'));
        $this->db->set('create_by',$user_email);
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by',$user_email);
        $query = $this ->db->insert('sureorder');
    }
}