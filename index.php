<?php 

require_once("vendor/autoload.php");

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/select', function() {
    
	$sql = new DB\Sql();
	$results = $sql->select("SELECT * FROM produto");

	echo json_encode($results);

});

$app->run();

 ?>