<?php 

namespace Classes\Page;

Class PageAdmin extends Page{
	public function __construct($d = array(), $tpl_dir = "/views/admin/"){
		parent::__construct($d, $tpl_dir); //chama o construtor da classe pai, passando o caminho /views/admin
	}



}


 ?>