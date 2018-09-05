<?php 

namespace Classes\Order;
use Classes\DB\Sql;
use Classes\Model;
use Classes\Mailer;

class Order extends Model{

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

		$results = $sql->select("SELECT *
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
}

 ?>