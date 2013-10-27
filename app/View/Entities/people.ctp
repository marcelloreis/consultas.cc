<?php 
/**
* Painel de pesquisas
*/
echo $this->element('Index/Entities/panel');

if(isset($people)){
	/**
	* Atalhos
	*/
	echo $this->element('Index/Entities/products');
	/**
	* Obito
	*/
	// echo $this->element('Index/Entities/death');
	/**
	* Dados do documento
	*/
	echo $this->element('Index/Entities/people');
	/**
	* Dados dos telefones
	*/
	// echo $this->element('Index/Entities/mobile');
	/**
	* Dados dos telefones
	*/
	if(isset($landline) && is_array($landline)){
		echo $this->element('Index/Entities/landline');
	}
	/**
	* Endereços que nao estao ligados a nenhum telefone fixo
	*/
	if(isset($locator) && is_array($locator)){
		echo $this->element('Index/Entities/locator');
	}
	/**
	* Participacao Societaria
	*/
	// echo $this->element('Index/Entities/society');
	/**
	* Parentesco
	*/
	echo $this->element('Index/Entities/family');
	/**
	* vizinhos
	*/
	echo $this->element('Index/Entities/neighborhood');
	
}

?>




