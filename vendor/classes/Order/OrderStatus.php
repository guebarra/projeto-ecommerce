<?php

namespace Classes\Order;
use Classes\DB\Sql;
use Classes\Model;
use Classes\Mailer;

class OrderStatus extends Model{
	const EM_ABERTO = 1;
	const AGUARDANDO_PAGAMENTO = 2;
	const PAGO = 3;
	const ENTREGUE = 4;

	public static function listAll(){
		$sql = new SQL();

		return $sql->select("SELECT * FROM status_pedido ORDER BY desstatus");
	}
}

 ?>
