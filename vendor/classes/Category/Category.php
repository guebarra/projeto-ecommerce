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

		Category::updateFile();
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

		Category::updateFile();
	}

	public static function updateFile(){
		$categories = Category::listAll();

		$html = [];

		foreach ($categories as $row) {
			array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
		}

		//Substitui dados de categories-menu.html pelos dados na array $html
		file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."categories-menu.html", implode('', $html));
	}
}

 ?>