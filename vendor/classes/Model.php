<?php 

namespace Classes;

class Model {

	private $values = [];

	//Getters and Setters genéricos. Sempre que chamar um getter ou setter, essa função é disparada
	public function __call($name, $args){
		$method = substr($name, 0, 3);
		$att = substr($name, 3, strlen($name));

		switch ($method) {
			case 'get':
				return (isset($this->values[$att])) ? $this->values[$att] : NULL;
				break;

			case 'set':
				$this->values[$att] = $args[0];
				break;
		}
	}

	public function setData($data = array()){
		foreach ($data as $key => $value) {
			$this->{"set".$key}($value);
		}
	}

	public function getData(){
		return $this->values;
	}
}

 ?>