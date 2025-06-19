<?php

class Report extends Controller{


    protected $userid;
	protected $username;

    public function __construct()
	{	
		
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

		   $this->view('templates/header');		
			$data["pages"]="lap";
			$this->view('templates/sidebar', $data);
			 $this->view('report/index',$data);
			 $this->view('report/detailmodal');
			 $this->view('templates/footer');
	
    }


	public function ListLaporan(){
		
		$data= $this->model('ReportModel')->ListLaporan($_POST);
		if(empty($data)){
			$data = null;
			echo json_encode($data);
		}else{
			echo json_encode($data);
		}
	}
	 


	public function DetailPenilaian(){
	
		$data["userid"]= isset($this->userid) ? $this->userid : null;
		$data["datapost"] =$_POST;
		$status_supplier = $_POST["status_supplier"];
		$supplier_list = isset($_POST['supplier_list']) ? $_POST['supplier_list'] : null;
		$id_penilaian = isset($_POST['id_penilaian']) ? $_POST['id_penilaian'] : null;
		$id_mspropotest = isset($_POST['id_mspropotest']) ? $_POST['id_mspropotest'] : null;
		$data["supplier"] = $this->model('ReportModel')->getDetailListSudahApproveSupplier($supplier_list,$this->userid,$id_penilaian,$id_mspropotest);
		
	
		$this->view('templates/header2');
		if($status_supplier =="Exist"){
		$this->view('report/detailpenilian_exist',$data);
		}else{
			$this->view('report/detailpenilian',$data);
		}
        $this->view('templates/footer2');
	}
 

	//untuk hasil laporan grapik dan table 
	public function hasil(){
		$data["userid"]= $this->userid;
		$data["username"]= $this->username;

		   $this->view('templates/header');		
			$data["pages"]="laphasil";
			$this->view('templates/sidebar', $data);
			 $this->view('reporthasil/index',$data);
			 $this->view('templates/footer');
	}



		public function ListLaporanHasil(){
			$data= $this->model('ReportModel')->ListLaporanHasil($_POST);
			if(empty($data)){
				$data = null;
				echo json_encode($data);
			}else{
				echo json_encode($data);
			}
		}
	//and 

}