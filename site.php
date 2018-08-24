<?php

$app->get('/', function() {
	$page = new Page();
	$page->setTpl("index");
});

$app->get('/categories/:idcategory', function($idcategory) {
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new Page();

	$page->setTpl("category", [
		'category'=>$category->getData(),
		'products'=>[]
	]);
});

?>