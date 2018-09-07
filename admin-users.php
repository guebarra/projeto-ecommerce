<?php

use Classes\Page\PageAdmin;
use Classes\User\User;

$app->get('/admin/users/:iduser/password', function($iduser) {
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-password", [
		"user"=>$user->getData(),
		"msgError"=>$user->getError(),
		"msgSuccess"=>$user->getSuccess()
	]);

});

$app->post('/admin/users/:iduser/password', function($iduser) {
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	if(!isset($_POST['despassword']) || $_POST['despassword'] === ''){
		User::setError("Preencha a nova senha.");

		header("Location: /admin/users/$iduser/password");
		exit;
	}

	if(!isset($_POST['despassword-confirm']) || $_POST['despassword-confirm'] === ''){
		User::setError("Preencha a confirmação da nova senha.");

		header("Location: /admin/users/$iduser/password");
		exit;
	}

	if($_POST['despassword'] != $_POST['despassword-confirm']){
		User::setError("Confirme corretamente as senhas.");

		header("Location: /admin/users/$iduser/password");
		exit;
	}

	if(password_verify($_POST['despassword'], $user->getsenha())){
		User::setError("Digite uma senha diferente da atual.");

		header("Location: /admin/users/$iduser/password");
		exit;
	}

	$user->get((int)$iduser);

	$user->setPassword(User::getPasswordHash($_POST['despassword']));

	User::setSuccess("Senha alterada com sucesso!");

	header("Location: /admin/users/$iduser/password");
	exit;
});

$app->get('/admin/users', function() {
	User::verifyLogin();

	$search = (isset($_GET['search'])) ? $_GET['search'] : '';
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if($search != ''){
		$pagination = User::getSearch($search, $page);
	} else {
		$pagination = User::getUsersPage($page);
	}

	$pages = [];

	for ($i=0; $i < $pagination['pages']; $i++) { 
		array_push($pages, [
			'href'=>'/admin/users?'.http_build_query([
				'page'=>$i+1,
				'search'=>$search
			]),
			'text'=>$i+1
		]);
	}

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users" => $pagination['data'],
		"search"=>$search,
		"pages"=>$pages
	));
});

$app->get('/admin/users/create', function() {
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("users-create");
});

$app->get('/admin/users/:iduser', function($iduser) {
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$page = new PageAdmin();
	$page->setTpl("users-update", array("user" => $user->getData()));

});

$app->get('/admin/users/:iduser/delete', function($iduser){
	User::verifyLogin();
	$user = new User();
	$user->delete($iduser);
	header("Location: /admin/users");
	exit;
});

$app->post('/admin/users/create', function(){
	User::verifyLogin();
	$user = new User();
	$_POST["tipo_user"] = (isset($_POST["tipo_user"]))?1:0;
	$_POST["senha"] = password_hash($_POST["senha"], PASSWORD_BCRYPT);
	$user->setData($_POST);
	$user->save();
	header("Location: /admin/users");
	exit;
});

$app->post('/admin/users/:iduser', function($iduser) {
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$_POST["tipo_user"] = (isset($_POST["tipo_user"]))?1:0;
	$_POST["senha"] = password_hash($_POST["senha"], PASSWORD_BCRYPT);
	$user->setData($_POST);
	$user->update();
	header("Location: /admin/users");
	exit;
});

?>