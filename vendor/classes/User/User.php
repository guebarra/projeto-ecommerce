<?php 

namespace Classes\User;
use Classes\DB\Sql;
use Classes\Model;

class User extends Model{

	const SESSION = "User";
	
	public static function login($email, $pass){
		$sql = new Sql();
		$result = $sql->select("SELECT * FROM user WHERE email = :EMAIL", [":EMAIL" => $email]);

		if(count($result) == 0)
			throw new \Exception("Usuário ou senha inválidos.");

		$data = $result[0];

		if(password_verify($pass, $data["senha"]) /*$pass == $data["senha"]*/){
			$user = new User();
			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getData();

			return $user;
		}
		else {
			var_dump($data["senha"]);
			var_dump(password_hash($pass, PASSWORD_BCRYPT));
			throw new \Exception("Usuário ou senha inválidos!");
			
		}
	}

	public static function verifyLogin(){
		if(!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION] ||
		!(int)$_SESSION[User::SESSION]["iduser"] > 0 || (int)$_SESSION[User::SESSION]["tipo_user"] != 1){
			header("Location: /admin/login");
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
		$results = $sql->query("CALL save_user(:nome, :sobrenome, :CPF, :email, :senha, :tel, :tipo_user)", array(
			":nome" => $this->getnome(),
			":sobrenome" => $this->getsobrenome(),
			":CPF" => $this->getCPF(),
			":email" => $this->getemail(),
			":senha" => $this->getsenha(),
			":tel" => $this->gettel(),
			":tipo_user" => $this->gettipo_user()
		));
	}

	public function get($iduser){
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM user WHERE user.iduser = :ID", array(":ID" => $iduser));
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
			":nome" => $this->getnome(),
			":sobrenome" => $this->getsobrenome(),
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
}

 ?>