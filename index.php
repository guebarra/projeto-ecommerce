<?php 

require_once("vendor/autoload.php");

use Classes\Page\Page;
use Classes\DB\Sql;
use Slim\Slim;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
	$page = new Page();
	$page->setTpl("teste", array("nome", "marco", "sobrenome", "guebarra", "profissao", "programador"));
});

$app->run();

 ?>