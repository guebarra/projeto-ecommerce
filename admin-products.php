<?php

use Classes\User\User;
use Classes\Product\Product;
use Classes\Page\PageAdmin;

$app->get("/admin/products", function(){
	User::verifyLogin();
	$page = new PageAdmin();
	$products = Product::listAll();

	$page->setTpl("products", [
		"products" => $products
	]);
});

$app->get("/admin/products/create", function(){
	User::verifyLogin();
	$page = new PageAdmin();

	$page->setTpl("products-create");
});

$app->post("/admin/products/create", function(){
	User::verifyLogin();
	$product = new Product();

	$product->setData($_POST);

	$product->save();

	header("Location: /admin/products");
	exit;
});

$app->get("/admin/products/:idproduct", function($idproduct){
	User::verifyLogin();
	$product = new Product();
	$page = new PageAdmin();

	$product->get((int)$idproduct);

	$product->setData($_POST);

	$product->save();

	$page->setTpl("products-update", [
		'product' => $product->getData()
	]);
});

$app->post("/admin/products/:idproduct", function($idproduct){
	User::verifyLogin();
	$product = new Product();

	$product->get((int)$idproduct);

	$product->setData($_POST);

	$product->save();

	$product->setPhoto($_FILES["file"]);

	header("Location: /admin/products");
	exit;
});

$app->get("/admin/products/:idproduct/delete", function($idproduct){
	User::verifyLogin();
	$product = new Product();

	$product->get((int)$idproduct);

	$product->delete();

	header("Location: /admin/products");
	exit;
});

?>