<?php

namespace Classes\Order;
use Classes\DB\Sql;
use Classes\Model;
use Classes\Mailer;
use Classes\Cart\Cart;

class Order extends Model{

	const SUCCESS = "Order-Success";
	const ERROR = "Order-Error";

	public function save(){
		$sql = new Sql();

		$results = $sql->select("CALL sp_orders_save (:idorder, :idcart, :iduser, :idstatus, :idaddress, :vltotal)", [
			':idorder'=>$this->getidorder(),
			':idcart'=>$this->getidcart(),
			':iduser'=>$this->getiduser(),
			':idstatus'=>$this->getidstatus(),
			':idaddress'=>$this->getidaddress(),
			':vltotal'=>$this->getvltotal()
		]);

		if(count($results) > 0) $this->setData($results[0]);
	}

	public function get($idorder){
		$sql = new Sql();

		$results = $sql->select("
			SELECT *
			FROM pedido a
			INNER JOIN status_pedido b USING (idstatus)
			INNER JOIN carrinho c USING (idcart)
			INNER JOIN user d ON d.iduser = a.iduser
			INNER JOIN endereco e USING (idaddress)
			WHERE a.idorder = :idorder", [
				":idorder"=>$idorder
			]
		);

		if(count($results) > 0) $this->setData($results[0]);

	}

	public static function listAll(){
		$sql = new Sql();

		return $sql->select("
			SELECT *
			FROM pedido a
			INNER JOIN status_pedido b USING (idstatus)
			INNER JOIN carrinho c USING (idcart)
			INNER JOIN user d ON d.iduser = a.iduser
			INNER JOIN endereco e USING (idaddress)
			ORDER BY a.dtregister DESC
		");
	}

	public function delete(){
		$sql = new Sql();

		$sql->query("DELETE FROM pedido WHERE idorder = :idorder", [
			":idorder"=>$this->getidorder()
		]);
	}

	public function getCart(): Cart{
		$cart = new Cart();
		$cart->get($this->getidcart());

		return $cart;

	}

	public static function setError($msg){
		$_SESSION[Order::ERROR] = $msg;
	}

	public static function getError(){
		$msg = (isset($_SESSION[Order::ERROR]) && $_SESSION[Order::ERROR]) ? $_SESSION[Order::ERROR] : '';

		Order::clearError();

		return $msg;
	}

	public static function clearError(){
		$_SESSION[Order::ERROR] = NULL;
	}

	public static function setSuccess($msg){
		$_SESSION[Order::SUCCESS] = $msg;
	}

	public static function getSuccess(){
		$msg = (isset($_SESSION[Order::SUCCESS]) && $_SESSION[Order::SUCCESS]) ? $_SESSION[Order::SUCCESS] : '';

		Order::clearSuccess();

		return $msg;
	}

	public static function clearSuccess(){
		$_SESSION[Order::SUCCESS] = NULL;
	}
}

 ?>
