<?php

class Kampanye extends Controller{


    private $userid;
    private $username;
      
    public function __construct()
	{	
	
     
		// $this->userid ="123";
		// $this->username ="wardi";
		if($_SESSION['session_login'] != 'sudah_login') {
			Flasher::setMessage('Login','Tidak ditemukan.','danger');
			header('location: '. base_url . '/login');
			exit;
		}else{
			$this->userid = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : "";
			$this->username =isset($_SESSION['username']) ? $_SESSION['username'] : "";
			if (empty($this->userid) || empty($this->username)) {
				header('location: '. base_url . '/login');
				exit(); // Pastikan script berhenti setelah redirect
			}
		}
		

	} 

    public function GetlistToday(){
        $data= $this->model('KampanyeModel')->GetlistToday();
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }

	public function index()
    {
    
    
        $data["userid"]= $this->userid;
        $data["username"]= $this->username;
        $data["pages"]="kamp";
        $this->view('templates/header');
        $this->view('templates/sidebar', $data);
        $this->view('kampanye/index',$data);
        $this->view('templates/footer');
    }



    public function getkategori(){
        $data= $this->model('KampanyeModel')->getkategori($_POST);
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }


    public function getmedia(){
        $data= $this->model('KampanyeModel')->getmedia($_POST);
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }


    public function SaveDataManual(){
           $data= $this->model('KampanyeModel')->SaveDataManual($_POST);
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }


    public function SaveDataDigital(){
        $data= $this->model('KampanyeModel')->SaveDataDigital($_POST);
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }



    public function Tampildata(){
       
        $data= $this->model('KampanyeModel')->Tampildata();
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
        }


     public function DeleteData(){

           $data= $this->model('KampanyeModel')->DeleteData($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
     }



     public function UpdateDataManual(){
      $data= $this->model('KampanyeModel')->UpdateDataManual($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
     }


     public function UpdateDataDigital(){
              $data= $this->model('KampanyeModel')->UpdateDataDigital($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            } 
     }
}