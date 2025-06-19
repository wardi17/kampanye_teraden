<?php

class Database{
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbnm = DB_NAME;
	private $dbh;
	private $stmt;
	public function __construct()
	{

		//$server ="(LOCAL)";
		$server="DESKTOP-1CEB0AJ\SQLEXPRESS";

		$dsn = 'Driver={SQL Server};Driver={SQL Server};Server='.$server.';Database='. $this->dbnm;
		// $dsn2 = 'Driver={SQL Server};Driver={SQL Server};Server='.$server.';Database='. $this->dbnm2;
		
	
		try{
			$this->dbh = odbc_connect($dsn,$this->user,$this->pass);
			// $this->dbh2 = odbc_connect($dsn2,$this->user,$this->pass);
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}


	public function query($query)
	{
	
		$this->stmt = odbc_exec($this->dbh,$query);
	}

	public function bind($param, $value, $type = null){
		if(is_null($type)){
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}

		$this->stmt->bindValue($param, $value, $type);
	}

	public function execute()
	{
		
		$this->stmt->execute();
	}

	public function resultSet()
	{

		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function single()
	{

		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function rowCount()
	{
		return $this->stmt->rowCount();
	}


	public function baca_sql($sql){
	
		$db =$this->dbh;
		$result = odbc_exec($db,$sql);
		return $result;
	
	}


	


	public function commit(){
		odbc_commit($this->dbh);
	}
}

