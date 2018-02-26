<?php 

require_once("vendor/autoload.php");

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/select', function() {
    
	$sql = new DB\Sql();
	$results = $sql->select("SELECT nome_prod, descricao FROM produto, categoria WHERE categoria.idcategoria = produto.categoria_idcategoria AND produto.categoria_idcategoria = 1");

	echo json_encode($results);

});

$app->run();

 ?>