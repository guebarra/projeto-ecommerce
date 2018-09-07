<?php 

namespace Classes\Category;
use Classes\DB\Sql;
use Classes\Model;
use Classes\Mailer;
use Classes\Product\Product;

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

	public function getProducts($related = true){
		$sql = new Sql();

		if($related){
			return $sql->select("
				SELECT * FROM produto WHERE idproduct IN(
					SELECT a.idproduct
					FROM produto a
					INNER JOIN categoria_produto b ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
				);"
			, [
				":idcategory" => $this->getidcategory()
			]);
		}
		else {
			return $sql->select("
				SELECT * FROM produto WHERE idproduct NOT IN(
					SELECT a.idproduct
					FROM produto a
					INNER JOIN categoria_produto b ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
				);"
			, [
				":idcategory" => $this->getidcategory()
			]);
		}
	}

	public function getProductsPage($page = 1, $itemsPerPage = 3){
		$sql = new Sql();

		$start = ($page - 1) * $itemsPerPage;

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM produto a
			INNER JOIN categoria_produto b
			ON a.idproduct = b.idproduct
			INNER JOIN categoria c 
			ON c.idcategory = b.idcategory
			WHERE c.idcategory = :idcategory
			LIMIT $start, $itemsPerPage;
		", [
			':idcategory' => $this->getidcategory()
		]);

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

		return [
			'data' => Product::checkList($results),
			'total' => (int)$resultTotal[0]["nrtotal"],
			'pages' => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
	}

	public function addProduct(Product $product){
		$sql = new Sql();
		$sql->query("INSERT INTO categoria_produto (idcategory, idproduct) VALUES (:idcategory, :idproduct)",[
				":idcategory" => $this->getidcategory(),
				":idproduct" => $product->getidproduct()
			]
		);
	}

	public function removeProduct(Product $product){
		$sql = new Sql();
		$sql->query("DELETE FROM categoria_produto WHERE idcategory = :idcategory AND idproduct = :idproduct",[
				":idcategory" => $this->getidcategory(),
				":idproduct" => $product->getidproduct()
			]
		);
	}

	public static function getCategoriesPage($page = 1, $itemsPerPage = 10){
		$sql = new Sql();

		$start = ($page - 1) * $itemsPerPage;

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM categoria
			ORDER BY idcategory
			LIMIT $start, $itemsPerPage;
		");

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

		return [
			'data' => $results,
			'total' => (int)$resultTotal[0]["nrtotal"],
			'pages' => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
	}

	public static function getSearch($search, $page = 1, $itemsPerPage = 10){
		$sql = new Sql();

		$start = ($page - 1) * $itemsPerPage;

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM categoria
			WHERE descategory LIKE :search
			ORDER BY idcategory
			LIMIT $start, $itemsPerPage;
		", [
			':search'=>'%'.$search.'%'
		]);

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

		return [
			'data' => $results,
			'total' => (int)$resultTotal[0]["nrtotal"],
			'pages' => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
	}
}

 ?>