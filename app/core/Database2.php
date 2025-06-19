<?php

class Database2{
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	public $dbnm;

	private $dbh;
	private $stmt;

	public function __construct($schema)
	{
		
		// $connection = odbc_connect("Driver={SQL Server};Server=DESKTOP-PUJ0GAQ\MSSQLSERVER2;Database=bambi-bmi;","sa","123456");
		// $this->dbh =$connection;
		$dsn = 'Driver={SQL Server};Server=(LOCAL);Database='.$schema;
		
	
	
		try{
			$this->dbh = odbc_connect($dsn,$this->user,$this->pass);
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}





	public function baca_sql2($sql){

		$db =$this->dbh;
		$result = odbc_exec($db,$sql);
		// die(var_dump($result)); 
		// $this->confirm_query($result);
		return $result;
	
	}


	public function commit(){
		odbc_commit($this->dbh);
	}
}

