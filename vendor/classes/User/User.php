<?php 

namespace Classes\User;
use Classes\DB\Sql;
use Classes\Model;
use Classes\Mailer;

class User extends Model{

	const SESSION = "User";
	const SECRET = "HcodePhp7_Secret";
	const CIFRA = "AES-256-CBC";
	const IV = "0123456789123456";
	const ERROR = "UserError";
	const ERROR_REGISTER = "UserErrorRegister";
	const SUCCESS = "UserSuccess";

	public static function getFromSession(){
		$user = new User();

		if(isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0){
			$user->setData($_SESSION[User::SESSION]);
		}

		return $user;
	}

	public static function checkLogin($inadmin = true){
		if(
			!isset($_SESSION[User::SESSION])
			|| !$_SESSION[User::SESSION]
			|| !(int)$_SESSION[User::SESSION]["iduser"] > 0
		){
			//não está logado
			return false;
		} else {
			if($inadmin === true && (bool)$_SESSION[User::SESSION]['inadmin'] === true){
				return true;
			} else if($inadmin === false){
				return true;
			} else {
				return false;
			}
		}
	}

	public static function login($email, $pass){
		$sql = new Sql();
		$result = $sql->select("SELECT * FROM user WHERE email = :EMAIL", [":EMAIL" => $email]);

		if(count($result) == 0)
			throw new \Exception("Usuário ou senha inválidos.");

		$data = $result[0];

		if(password_verify($pass, $data["senha"])){
			$user = new User();
			$data['nome'] = utf8_encode($data['nome']);
			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getData();
		}
		else {
			throw new \Exception("Usuário ou senha inválidos!");
			
		}
	}

	public static function verifyLogin($inadmin = true){
		if(!User::checkLogin($inadmin)){
			if($inadmin){
				header("Location: /admin/login");
			} else {
				header("Location: /login");
			}
			exit;
		}
	}

	public static function logout(){
		$_SESSION[User::SESSION] = NULL;
	}

	public static function listAll(){
		$sql = new Sql();
		return $sql->select("SELECT * FROM user ORDER BY iduser");
	}

	public function save(){
		$sql = new Sql();

		$results = $sql->select("CALL save_user(:nome, :sobrenome, :CPF, :email, :senha, :tel, :tipo_user)", array(
			":nome" => utf8_decode($this->getnome()),
			":sobrenome" => utf8_decode($this->getsobrenome()),
			":CPF" => $this->getCPF(),
			":email" => $this->getemail(),
			":senha" => User::getPasswordHash($this->getsenha()),
			":tel" => $this->gettel(),
			":tipo_user" => $this->gettipo_user()
		));

		$this->setData($results[0]);
	}

	public function get($iduser){
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM user WHERE user.iduser = :ID", array(":ID" => $iduser));

		$results[0]['nome'] = utf8_encode($results[0]['nome']);
		$this->setData($results[0]);
	}

	public function update(){
		$sql = new Sql();
		$results = $sql->query("UPDATE user SET
				nome = :nome,
		        sobrenome = :sobrenome,
		        CPF = :CPF,
		        email = :email,
		        senha = :senha,
		        tel = :tel,
	        	tipo_user = :tipo_user
        	WHERE user.iduser = :iduser", array(
			":iduser" => $this->getiduser(),
			":nome" => utf8_decode($this->getnome()),
			":sobrenome" => utf8_decode($this->getsobrenome()),
			":CPF" => $this->getCPF(),
			":email" => $this->getemail(),
			":senha" => $this->getsenha(),
			":tel" => $this->gettel(),
			":tipo_user" => $this->gettipo_user()
		));
	}

	public function delete($id){
		$sql = new Sql();
		$sql->query("DELETE FROM user WHERE iduser = :id", array(":id" => $id));
	}

	public static function getForgot($email, $inadmin = true){
		$sql = new Sql();
		$result = $sql->select("SELECT * FROM user WHERE email = :email", array(":email"=>$email));

		if(count($result) == 0){
			throw new \Exception("Não foi possível recuperar a senha.");
		}
		else{
			$data = $result[0];

			$sql->select("CALL sp_recovery_password (:iduser)", array(
				":iduser" =>$data["iduser"]
			)); 

			$code = base64_encode(openssl_encrypt($data["iduser"], User::CIFRA, User::SECRET, 0, User::IV));

			if($inadmin){
				$link = "http://www.ecommerce.com.br/admin/forgot/reset?code=$code";
			} else {
				$link = "http://www.ecommerce.com.br/forgot/reset?code=$code";
			}

			$mailer = new Mailer($data["email"], $data["nome"], "Redefinir Senha E-commerce",
				"forgot", array("name" => $data["nome"], "link" => $link));

			$mailer->send();
		}
	}

	public static function validForgotDecrypt($code){
		$idrecovery = openssl_decrypt(base64_decode($code), User::CIFRA, User::SECRET, 0,
			User::IV);

		$sql = new Sql();

		$result = $sql->select("
			SELECT *
			FROM senha_recuperada a
			INNER JOIN user b USING (iduser)
			WHERE a.iduser  = :idrecovery
			AND a.dtrecovery IS NULL
			AND DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();
		", array(
			":idrecovery"=>$idrecovery
		));

		if(count($result) == 0){
			throw new \Exception("Não foi possível recuperar a senha");
		} else {
			return $result[0];
		}
	}

	public static function setForgotUsed($idRecovery){
		$sql = new Sql();
		$sql->query("UPDATE senha_recuperada SET dtrecovery = NOW() WHERE iduser = :idrecovery", array(
			":idrecovery"=>$idRecovery
		));

	}

	public function setPassword($password){
		$sql = new Sql();
		$sql->query("UPDATE user SET senha = :password WHERE iduser = :iduser", array(
			":password" => $password,
			":iduser" => $this->getiduser()
		));
	}

	public static function setError($msg){
		$_SESSION[User::ERROR] = $msg;
	}

	public static function getError(){
		$msg = (isset($_SESSION[User::ERROR]) && $_SESSION[User::ERROR]) ? $_SESSION[User::ERROR] : '';

		User::clearError();

		return $msg;
	}

	public static function clearError(){
		$_SESSION[User::ERROR] = NULL;
	}

	public static function setSuccess($msg){
		$_SESSION[User::SUCCESS] = $msg;
	}

	public static function getSuccess(){
		$msg = (isset($_SESSION[User::SUCCESS]) && $_SESSION[User::SUCCESS]) ? $_SESSION[User::SUCCESS] : '';

		User::clearSuccess();

		return $msg;
	}

	public static function clearSuccess(){
		$_SESSION[User::SUCCESS] = NULL;
	}

	public static function getErrorRegister(){
		$msg = (isset($_SESSION[User::ERROR_REGISTER]) && $_SESSION[User::ERROR_REGISTER]) ? $_SESSION[User::ERROR_REGISTER] : '';

		User::clearErrorRegister();

		return $msg;
	}

	public static function setErrorRegister($msg){
		$_SESSION[User::ERROR_REGISTER] = $msg;
	}

	public static function clearErrorRegister(){
		$_SESSION[User::ERROR_REGISTER] = NULL;
	}

	public static function getPasswordHash($password){
		return password_hash($password, PASSWORD_DEFAULT, [
				'cost'=>12
		]);
	}

	public static function checkLoginExists($email){
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM user WHERE email = :email", [
			':email'=>$email
		]);

		return (count($results) > 0);
	}

	public static function checkCpfExists($cpf){
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM user WHERE cpf = :cpf", [
			':cpf'=>$cpf
		]);

		return (count($results) > 0);
	}
}

 ?>