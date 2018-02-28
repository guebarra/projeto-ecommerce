<?php 

namespace classes\DB;

class Sql{
	private $conn;

	public function __construct(){
		$this->conn = new \PDO("mysql:host=127.0.0.1;dbname=db_ecommerce", "root", "");
	}

	public function setParam($stmt, $params = array()){
		foreach ($params as $key => $value) {
			$stmt->bindParam($key, $value);
		}
	}

	public function query($rawQuery, $params = array()){
		$stmt = $this->conn->prepare($rawQuery);
		$this->setParam($stmt, $params);
		$stmt->execute();

		return $stmt;
	}

	public function select($rawQuery, $params = array()){
		$stmt = $this->query($rawQuery, $params);
		
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}
}

?>