<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Bangkok');

class Admin3models extends CI_Model
{
    // public function InsertCategory($request)
    // {
    //     $this->db->set('uuid',md5(Date('Y-m-d h:i:s')));
    //     $this->db->set('name',$request["name"]);
    //     $this->db->set('create_at',Date('Y-m-d h:i:s'));
    //     $this->db->set('create_by','System');
    //     $this->db->set('update_at',Date('Y-m-d h:i:s'));
    //     $this->db->set('update_by','System');
    //     $query = $this ->db->insert('category');
    //     if($query){
    //         return true;
    //     }
    //     else{
    //         return false;
    //     }
    // }
    public function CreateCategory($request)
    {
        $this->db->set('uuid',$request["uuid"]);
        $this->db->set('name',$request["name"]);
        $this->db->set('pathfile',$request["pathImage"]);
        $this->db->set('create_at',Date('Y-m-d h:i:s'));
        $this->db->set('create_by','System');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by','System');
        $query = $this ->db->insert('category');
        if($query){
            return true;
        }
        else{
            return false;
        }
    }
    public function UpdateCategory($request)
    {
        $this->db->set('name',$request['name']);
        if($request['pathImage'] !== ""){
            $this->db->set('pathfile',$request['pathImage']);
        }
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by','System');
        $this->db->where('id',$request['id']);
        $query = $this ->db->update('category');
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function DeleteCategory($request)
    {
          $this->db->where('id',$request['id']);
        $query = $this ->db->delete('category');
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function EnableCategory($request)
    {
        $this->db->set('status','001');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->where('id',$request['id']);
        $query = $this ->db->update('category');
    }

    public function DisableCategory($request)
    {
        $this->db->set('status','002');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->where('id',$request['id']);
        $query = $this ->db->update('category');
    }

    public function CreateBook($request)
    {
        $this->db->set('uuid',$request["uuid"]);
        $this->db->set('name',$request["name"]);
        $this->db->set('category_uuid',$request["category_uuid"]);
        $this->db->set('image',$request["pathImage"]);
        $this->db->set('author_uuid',$request["author_uuid"]);
        $this->db->set('isbn',$request["isbn"]);
        $this->db->set('price',$request["price"]);
        $this->db->set('create_at',Date('Y-m-d h:i:s'));
        $this->db->set('create_by','System');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by','System');
        $query = $this ->db->insert('book');
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function UpdateBook($request)
    {
        $this->db->set('name',$request['name']);
        if($request['pathImage'] !== ""){
            $this->db->set('image',$request['pathImage']);
        }
        $this->db->set('price',$request["price"]);
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by','System');
        $this->db->where('id',$request['id']);
        $query = $this ->db->update('book');
        if($query){
            return true;
        }
        else{
            return false;
        }
    }
    public function EnableBook($request)
    {
        $this->db->set('status','001');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->where('id',$request['id']);
        $query = $this ->db->update('book');
    }

    public function DisableBook($request)
    {
        $this->db->set('status','002');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->where('id',$request['id']);
        $query = $this ->db->update('book');
    }
    public function CreateAuthor($request)
    {
        $this->db->set('uuid',$request["uuid"]);
        $this->db->set('name',$request["name"]);
        $this->db->set('phone',$request["phone"]);
        $this->db->set('email',$request["email"]);
        $this->db->set('create_at',Date('Y-m-d h:i:s'));
        $this->db->set('create_by','System');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by','System');
        $query = $this ->db->insert('author');
        if($query){
            return true;
        }
        else{
            return false;
        }
    }
    public function UpdateAuthor($request)
    {
        
        $this->db->set('name',$request["name"]);
        $this->db->set('phone',$request["phone"]);
        $this->db->set('email',$request["email"]);
        $this->db->set('create_at',Date('Y-m-d h:i:s'));
        $this->db->set('create_by','System');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->set('update_by','System');
        $this->db->where('id',$request['id']);
        $query = $this ->db->update('author');
        if($query){
            return true;
        }
        else{
            return false;
        }
    }
    public function EnableAuthor($request)
    {
        $this->db->set('status','001');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->where('id',$request['id']);
        $query = $this ->db->update('author');
    }

    public function DisableAuthor($request)
    {
        $this->db->set('status','002');
        $this->db->set('update_at',Date('Y-m-d h:i:s'));
        $this->db->where('id',$request['id']);
        $query = $this ->db->update('author');
    }
    public function GetBooks($request)
    {
        $sql = "SELECT b.uuid, b.name, c.name as category_name, a.name as author,b.image, b.price, b.stockAll, b.stock, b.sold, b.create_at
                FROM book b
                INNER JOIN category c
                ON b.category_uuid = c.uuid
                INNER JOIN author a
                ON b.author_uuid = a.uuid
                ORDER BY b.id asc
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

    public function GetBook($request)
    {
        $book_uuid = $request['book_uuid'];
        $sql = "SELECT b.uuid, b.category_uuid, b.name, b.image, b.price, b.stockAll, b.stock, b.sold, b.create_at
                FROM book b
                WHERE b.uuid= '$book_uuid'
                
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
    public function GetCart($request)
    {
        $data=($request['aaa']);
        return $data;
    }
}