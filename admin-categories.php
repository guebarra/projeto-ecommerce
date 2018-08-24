<?php

use Classes\Page\PageAdmin;
use Classes\User\User;
use Classes\Category\Category;

$app->get('/admin/categories', function() {
	User::verifyLogin();
	$categories = Category::listAll();
	$page = new PageAdmin();

	$page->setTpl("categories", [
		'categories'=>$categories
	]);
});

$app->get('/admin/categories/create', function() {
	User::verifyLogin();
	$page = new PageAdmin();

	$page->setTpl("categories-create");
});

$app->post('/admin/categories/create', function() {
	User::verifyLogin();
	$category = new Category();
	$category->setData($_POST);
	$category->save();

	header("Location: /admin/categories");
	exit;
});

$app->get('/admin/categories/:idcategory/delete', function($idcategory) {
	$category = new Category();

	$category->get((int)$idcategory);
	var_dump($category->getidcategory());
	$category->delete();

	header("Location: /admin/categories");
	exit;
});

$app->get('/admin/categories/:idcategory', function($idcategory) {
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new PageAdmin();

	$page->setTpl("categories-update", [
		'category'=>$category->getData()
	]);
});

$app->post('/admin/categories/:idcategory', function($idcategory) {
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$category->setData($_POST);
	$category->save();

	header("Location: /admin/categories");
	exit;
});

?>