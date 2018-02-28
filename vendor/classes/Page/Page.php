<?php 

namespace Classes\Page;

use Rain\Tpl;

class Page{

	private $tpl; //variável tpl, usada para tomar decições do framework TPL
	private $dados = []; //array vazio que receberá as variáveis com chave "data"
	private $empty = [ "data" => [] ]; //array vazio com chave data

	public function __construct($d = array()){
		$this->tpl = new Tpl();

		$this->dados = array_merge($this->empty, $d); //sobrescreve a variável data com os dados passados pelo usuário

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
		);

		Tpl::configure($config); //configura o RainTPL (padrão)

		$this->assignData($this->dados["data"]);

		//desenha a tela
		$this->tpl->draw("header");
	}

	public function setTpl($name, $data = array()){
		$this->assignData($data);
		$this->tpl->draw($name);
	}

	public function __destruct(){
		$this->tpl->draw("footer");
	}

	//atribui os valores passados pelo usuário a variável do RainTPL
	public function assignData($d = array()){
		foreach ($d as $key => $value) {
			$this->tpl->assign($key, $value);
		}
	}
}

 ?>