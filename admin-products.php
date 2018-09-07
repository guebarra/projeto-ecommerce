<?php

use Classes\User\User;
use Classes\Product\Product;
use Classes\Page\PageAdmin;

$app->get("/admin/products", function(){
	User::verifyLogin();

	$search = (isset($_GET['search'])) ? $_GET['search'] : '';
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if($search != ''){
		$pagination = Product::getSearch($search, $page);
	} else {
		$pagination = Product::getProductsPage($page);
	}

	$pages = [];

	for ($i=0; $i < $pagination['pages']; $i++) { 
		array_push($pages, [
			'href'=>'/admin/products?'.http_build_query([
				'page'=>$i+1,
				'search'=>$search
			]),
			'text'=>$i+1
		]);
	}

	$page = new PageAdmin();

	$page->setTpl("products", [
		"products" => $pagination['data'],
		"search"=>$search,
		"pages"=>$pages
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