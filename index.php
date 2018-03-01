<?php 

require_once("vendor/autoload.php");

use Classes\Page\Page;
use Classes\DB\Sql;
use Slim\Slim;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
	$page = new Page();
	$page->setTpl("index");
});

$app->run();

 ?>