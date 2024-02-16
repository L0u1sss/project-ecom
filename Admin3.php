<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include("src/JWT.php");
use \Firebase\JWT\JWT;

class Admin3 extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('checkparams');
        $this->load->model('Api/Admin3models');
        $this->db->query("set names utf8mb4");
    }

    public function Base64Imgaes($Array) {
        $Array = json_decode($Array, true);

        $ArrayPath = [];
        
        for ($x = 0 ; $x < count($Array); $x++) {

            $base64_string = $Array[$x]["base64_string"];
            $status_item = $Array[$x]["status_item"];

            if($base64_string != "isemty" && $status_item == 1){
                $data_base64 = explode( ',', $base64_string );
                $data = base64_decode($data_base64[ 1 ]);
                $img_name = round(microtime(true) * 1000).".jpg";

                if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
                    // echo 'This code is running on a local machine';
                    $dirpath = realpath(dirname(getcwd()));
                    $path =  $dirpath."/php-api/imagefrombase64/";
                    $savepath ="/php-api/imagefrombase64/".$img_name;
                } else {
                    // echo 'This code is running on a server';
                    $path =  getcwd()."/imagefrombase64/";
                    $savepath = "/imagefrombase64/".$img_name;
                }

                if(!file_exists($path)){
                    $flgCreate = mkdir($path,0777,true);
                }
                if ( file_put_contents($path.$img_name, $data)) {  
                    array_push($ArrayPath,$savepath);
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
        return $ArrayPath;
    }
    
    public function CreateCategory_post()
    {
        $request = $this->post();
        $arrayImage = $this->Base64Imgaes($request["arrayBase64"]);
        $uuid = hash('sha512',Date('Y-m-d h:i:s'));
        $request['uuid'] = $uuid;
        $request['pathImage'] = $arrayImage[0];

        $data = $this->Admin3models->CreateCategory($request);
        
        $this->response($request, MY_Controller::HTTP_OK);
    }
    
    // public function InsertCategory_post()
    // {
    //     $request = $this->post();
    //     $data = $this->Admin3models->InsertCategory($request);
    //     $this->response($data, MY_Controller::HTTP_OK);
    // }

    public function UpdateCategory_post()
    {
        $request = $this->post();
        $pathImage = "";
        
        if(empty($request['arrayBase64'])){
            $statusImage = 0;
        }
        else{
            $statusImage = (json_decode($request['arrayBase64'], true))[0]['status_item'];
        }
        
        if ($statusImage == 1)
        {
            $arrayImage = $this->Base64Imgaes($request['arrayBase64']);
            $pathImage = $arrayImage[0];
        }
        $request['pathImage']=$pathImage;
        $data = $this->Admin3models->UpdateCategory($request);
        $this->response($data, MY_Controller::HTTP_OK);
    }

    public function DeleteCategory_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->DeleteCategory($request);
        if($data == true){
            $response = ['status'=>true,'error'=>false,'message'=>'Deleted Category'];
        }
        else{
            $response =['status'=>false,'error'=>true,'message'=>'can not Delete category'];
        }
        $this->response($response, MY_Controller::HTTP_OK);
    }

    public function EnableCategory_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->EnableCategory($request);
        
        $response =['status'=>true,'error'=>false,'message'=>'Enable category successful'];
        
        $this->response($response, MY_Controller::HTTP_OK);
    }

    public function DisableCategory_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->DisableCategory($request);
        
        $response =['status'=>true,'error'=>false,'message'=>'Disable category successful'];
        
        $this->response($response, MY_Controller::HTTP_OK);
    }
 
    public function CreateBook_post()
    {
        $request = $this->post();
        $arrayImage = $this->Base64Imgaes($request["arrayBase64"]);
        $uuid = hash('sha512',Date('Y-m-d h:i:s'));
        $request['uuid'] = $uuid;
        $request['pathImage'] = $arrayImage[0];

        $data = $this->Admin3models->CreateBook($request);
        
        $this->response($request, MY_Controller::HTTP_OK);
    }

    public function UpdateBook_post()
    {
        $request = $this->post();
        $pathImage = "";
        
        if(empty($request['arrayBase64'])){
            $statusImage = 0;
        }
        else{
            $statusImage = (json_decode($request['arrayBase64'], true))[0]['status_item'];
        }
        
        if ($statusImage == 1)
        {
            $arrayImage = $this->Base64Imgaes($request['arrayBase64']);
            $pathImage = $arrayImage[0];
        }
        $request['pathImage']=$pathImage;
        $data = $this->Admin3models->UpdateBook($request);
        $this->response($data, MY_Controller::HTTP_OK);
    }

    public function EnableBook_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->EnableBook($request);
        
        $response =['status'=>true,'error'=>false,'message'=>'Enable book successful'];
        
        $this->response($response, MY_Controller::HTTP_OK);
    }

    public function DisableBook_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->DisableBook($request);
        
        $response =['status'=>true,'error'=>false,'message'=>'Disable book successful'];
        
        $this->response($response, MY_Controller::HTTP_OK);
    }

    public function CreateAuthor_post()
    {
        $request = $this->post();
        
        $uuid = hash('sha512',Date('Y-m-d h:i:s'));
        $request['uuid'] = $uuid;
        

        $data = $this->Admin3models->CreateAuthor($request);
        
        $this->response($request, MY_Controller::HTTP_OK);
    }
    public function UpdateAuthor_post()
    {
        $request = $this->post();
        
        $uuid = hash('sha512',Date('Y-m-d h:i:s'));
        $request['uuid'] = $uuid;
        

        $data = $this->Admin3models->UpdateAuthor($request);
        
        $this->response($request, MY_Controller::HTTP_OK);
    }
    public function EnableAuthor_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->EnableAuthor($request);
        
        $response =['status'=>true,'error'=>false,'message'=>'Enable author successful'];
        
        $this->response($response, MY_Controller::HTTP_OK);
    }

    public function DisableAuthor_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->DisableAuthor($request);
        
        $response =['status'=>true,'error'=>false,'message'=>'Disable author successful'];
        
        $this->response($response, MY_Controller::HTTP_OK);
    }
    public function GetBooks_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->GetBooks($request);
        $this->response($data, MY_Controller::HTTP_OK);
    }

    public function GetBook_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->GetBook($request);
        $this->response($data, MY_Controller::HTTP_OK);
    }

    public function GetCart_post()
    {
        $request = $this->post();
        $data = $this->Admin3models->GetCart($request);
        $this->response($data, MY_Controller::HTTP_OK);
    }
}