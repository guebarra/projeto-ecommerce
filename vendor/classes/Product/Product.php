<?php 

namespace Classes\Product;
use Classes\DB\Sql;
use Classes\Model;
use Classes\Mailer;

class Product extends Model{

	public static function listAll(){
		$sql = new Sql();
		return $sql->select("SELECT * FROM produto ORDER BY desproduct");
	}

	public static function checkList($list){
		foreach ($list as &$row) {
			$p = new Product();
			$p->setData($row);
			$row = $p->getData();
		}

		return $list;
	}

	public function save(){
		$sql = new Sql();

		$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
			":idproduct" => $this->getidproduct(),
			":desproduct" => $this->getdesproduct(),
			":vlprice" => $this->getvlprice(),
			":vlwidth" => $this->getvlwidth(),
			":vlheight" => $this->getvlheight(),
			":vllength" => $this->getvllength(),
			":vlweight" => $this->getvlweight(),
			":desurl" => $this->getdesurl()
		));

		$this->setData($results[0]);
	}

	public function get($idProduct){
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM produto WHERE idproduct = :idproduct", array(
			":idproduct" => $idProduct
		));

		$this->setData($results[0]);
	}

	public function delete(){
		$sql = new Sql();

		$sql->query("DELETE FROM produto WHERE idproduct = :idproduct", [
			":idproduct" => $this->getidproduct()
		]);
	}

	public function checkPhoto(){
		if(file_exists(
			$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.
			'res'.DIRECTORY_SEPARATOR.
			'site'.DIRECTORY_SEPARATOR.
			'img'.DIRECTORY_SEPARATOR.
			'products'.DIRECTORY_SEPARATOR.
			$this->getidproduct().".jpg"
		)){
			$url = "/res/site/img/products/".$this->getidproduct().".jpg";
		}
		else{
			$url = "/res/site/img/product.jpg";
		}

		return $this->setdesphoto($url);
	}

	public function getData(){
		$this->checkPhoto();
		$values = parent::getData();
		return $values;
	}

	public function setPhoto($file){
		$extension = explode('.', $file["name"]);
		$extension = end($extension);

		switch($extension){
			case "jpg":
			case "jpeg":
				$image = imagecreatefromjpeg($file["tmp_name"]);
			break;

			case "gif":
				$image = imagecreatefromgif($file["tmp_name"]);
			break;

			case "png":
				$image = imagecreatefrompng($file["tmp_name"]);
			break;
		}

		$dist = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.
			'res'.DIRECTORY_SEPARATOR.
			'site'.DIRECTORY_SEPARATOR.
			'img'.DIRECTORY_SEPARATOR.
			'products'.DIRECTORY_SEPARATOR.
			$this->getidproduct().".jpg";

		imagejpeg($image, $dist);

		imagedestroy($image);

		$this->checkPhoto();
	}

	public function getFromURL($url){
		$sql = new Sql();

		$rows = $sql->select("SELECT * FROM produto WHERE desurl = :url LIMIT 1", [
			':url' => $url
		]);

		$this->setData($rows[0]);
	}

	public function getCategories(){
		$sql = new Sql();

		return $sql->select("SELECT * FROM categoria a INNER JOIN categoria_produto b ON a.idcategory = b.idcategory WHERE b.idproduct = :idproduct", [
			':idproduct' => $this->getidproduct()
		]);
	}

	public static function getProductsPage($page = 1, $itemsPerPage = 10){
		$sql = new Sql();

		$start = ($page - 1) * $itemsPerPage;

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM produto
			ORDER BY idproduct
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
			FROM produto
			WHERE desproduct LIKE :search
			ORDER BY idproduct
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