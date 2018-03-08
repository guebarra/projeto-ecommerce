<?php 

namespace Classes\Page;

use Rain\Tpl;

class Page{

	private $tpl; //variável tpl, usada para tomar decições do framework TPL
	private $dados = []; //array vazio que receberá as variáveis com chave "data"
	private $empty = [
		"header" => true,
		"footer" => true,
		"data" => []
	]; //array vazio com chave data
	private $aux;

	public function __construct($d = array(), $tpl_dir = "/views/"){
		$this->tpl = new Tpl();

		$this->dados = array_merge($this->empty, $d); //sobrescreve a variável data com os dados passados pelo usuário

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
		);

		Tpl::configure($config); //configura o RainTPL (padrão)

		$this->assignData($this->dados["data"]);

		//desenha a tela
		if($this->dados["header"]) $this->tpl->draw("header");
	}

	public function setTpl($name, $data = array()){
		$this->assignData($data);
		$this->tpl->draw($name);
	}

	public function __destruct(){
		if($this->dados["footer"]) $this->tpl->draw("footer");
	}

	//atribui os valores passados pelo usuário a variável do RainTPL
	public function assignData($d = array()){
		foreach ($d as $key => $value) { //para cada chave de d
			if($key % 2 == 0){ //se for número par (nome das variáveis)
				$this->aux = $value; //aux = valor
				$value = $d[$key+1]; //valor = prox item do array
				$key = $this->aux; //chave = aux
				$d[$key] = $value; //array[aux] = prox item do array
				$this->tpl->assign($key, $value); //faz o assign para o TPL reconhecer
			}
		}
	}
}

 ?>