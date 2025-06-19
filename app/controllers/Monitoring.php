<?php

class Monitoring extends Controller{


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



 public function index()
    {
    
    
        $data["userid"]= $this->userid;
        $data["username"]= $this->username;
        $data["pages"]="mont";
        $this->view('templates/header');
        $this->view('templates/sidebar', $data);
        $this->view('monitoring/manual/index',$data);
        $this->view('templates/footer');
    }


	public function getmonio(){
		$data= $this->model('MonitoringModel')->GetMonio($_POST);
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
	}

    public function ValidasiSave(){
        $data= $this->model('MonitoringModel')->ValidasiSave($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }  
    }
    public function SaveData(){
      	$data= $this->model('MonitoringModel')->SaveData($_POST);
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }  
    }



	public function MsPertanyaan(){
		$data= $this->model('MonitoringModel')->MsPertanyaan($_POST);
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
	}


    public function Listkampanye(){
        $data["userid"]= $this->userid;
        $data["username"]= $this->username;
        $data["pages"]="listmont";
        $this->view('templates/header');
        $this->view('templates/sidebar', $data);
        $this->view('monitoring/manual/listkampanye',$data);
        $this->view('templates/footer');
    }


    public function ListDataManual(){
        $data= $this->model('MonitoringModel')->ListDataManual($_POST);
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }


    public function DataEdit(){
        $data= $this->model('MonitoringModel')->DataEdit($_POST);
        if(empty($data)){
            $data = null;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }

    public function ValidasiEdit(){
            $data= $this->model('MonitoringModel')->ValidasiEdit($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
    }

    public function UpdateData(){
        $data= $this->model('MonitoringModel')->UpdateData($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
    }


    public function DeleteData(){
           $data= $this->model('MonitoringModel')->DeleteData($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
    }


    //untuk input digital
    public function digital(){
        $data["userid"]= $this->userid;
        $data["username"]= $this->username;
        $data["pages"]="dgtl";
        $this->view('templates/header');
        $this->view('templates/sidebar', $data);
        $this->view('monitoring/digital/index',$data);
        $this->view('templates/footer');
    }


    public function GetDigital(){
                $data= $this->model('DigitalModel')->GetDigital($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
    }


    public function SaveDataDigital(){
                $data= $this->model('DigitalModel')->SaveDataDigital($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
    }

    public function ValidasiSaveDigital(){
                $data= $this->model('DigitalModel')->ValidasiSaveDigital($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
    }
    public function ListDigital(){
        $data["userid"]= $this->userid;
        $data["username"]= $this->username;
        $data["pages"]="listdgtl";
        $this->view('templates/header');
        $this->view('templates/sidebar', $data);
        $this->view('monitoring/digital/listDigital',$data);
        $this->view('templates/footer');
    }


    public function ListDataDigital(){
          $data= $this->model('DigitalModel')->ListDataDigital($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
    }

    public function dataDigitalEdit(){
     $data= $this->model('DigitalModel')->dataDigitalEdit($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
    }

    public function ValidasiEditDigital(){
            $data= $this->model('DigitalModel')->ValidasiEditDigital($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
            }
    }

    
    public function UpdateDataDigital(){
         $data= $this->model('DigitalModel')->UpdateDataDigital($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
             
        }
    }

     public function DeleteDataDigital(){
          $data= $this->model('DigitalModel')->DeleteDataDigital($_POST);
            if(empty($data)){
                $data = null;
                echo json_encode($data);
            }else{
                echo json_encode($data);
             
        }      
    }
    //and untuk input digital
}