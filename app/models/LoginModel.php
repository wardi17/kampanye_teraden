<?php

class LoginModel {
	
	private $table ="[um_db].[dbo].a_user";
	private $db;
	private $db2;
	public function __construct()
	{
		$this->db = new Database;
	}

	public function checkLogin($data)
	{
		
		$username =  addslashes($data["username"]);
		$pass = addslashes($data["password"]);
		$query = "SELECT pass,email,id_user as userid,id_cust as nama FROM $this->table WHERE email ='".$username."' AND pass ='".$pass."'";
	
		//die(var_dump($query));
		$sql =$this->db->baca_sql($query);
		$pass2=odbc_result($sql,"pass");
		$email=odbc_result($sql,"email");
		$userid=odbc_result($sql,"userid");
		$nama=odbc_result($sql,"nama");
		
		$datas =[];
		if($pass2 == $pass && $email == $username){
			$datas[] =[
				'username' =>$nama,
				'id_user' =>$userid
			];
		}
	
		
	
		if (empty($datas))
		{
			$userdata = null;
		}
		else
		{
			$userdata = $datas[0];
		} 
	
		return $userdata;
	}
	
	
	public function getDataDivisi(){
		$query ="SELECT DISTINCT divisi_budget FROM  $this->table WHERE divisi_budget <>'NULL'";
		$result =$this->db2->baca_sql2($query);
			
			$data =[];
			while(odbc_fetch_row($result)){
				$data[] = array(
					"divisi_budget"=>rtrim(odbc_result($result,'divisi_budget')),

				);
				
				}
				
		return $data;
	}

}