<?php 

namespace Classes\Category;
use Classes\DB\Sql;
use Classes\Model;
use Classes\Mailer;

class Category extends Model{

	public static function listAll(){
		$sql = new Sql();
		return $sql->select("SELECT * FROM categoria ORDER BY descategory");
	}

	public function save(){
		$sql = new Sql();

		$results = $sql->select("CALL sp_categories_save(:idcategoria, :des_cat)", array(
			":idcategoria" => $this->getidcategory(),
			":des_cat" => $this->getdescategory()
		));

		$this->setData($results[0]);
	}

	public function get($idCategory){
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM categoria WHERE idcategory = :idcategory", array(
			":idcategory" => $idCategory
		));

		$this->setData($results[0]);
	}

	public function delete(){
		$sql = new Sql();

		$sql->query("DELETE FROM categoria WHERE idcategory = :idcategory", [
			":idcategory" => $this->getidcategory()
		]);
	}
}

 ?>