<?php 
session_start();
require_once("vendor/autoload.php");

use Classes\Page\Page;
use Classes\Page\PageAdmin;
use Classes\DB\Sql;
use Slim\Slim;
use Classes\User\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
	$page = new Page();
	$page->setTpl("index");
});

$app->get('/admin', function() {
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("index");
});

$app->get('/admin/login', function() {
	$page = new PageAdmin(["header" => false, "footer" => false]);
	$page->setTpl("login");
});

$app->post('/admin/login', function() {
	User::login($_POST["email"], $_POST["password"]);
	header("Location: /admin");
	exit;
});

$app->run();

 ?>