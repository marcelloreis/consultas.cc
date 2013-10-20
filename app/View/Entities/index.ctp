<?php 
/**
* Painel de pesquisas
*/
echo $this->element('Index/Entities/panel');

if($entity){
	/**
	* Atalhos
	*/
	echo $this->element('Index/Entities/goto');
	/**
	* Obito
	*/
	// echo $this->element('Index/Entities/death');
	/**
	* Dados do documento
	*/
	echo $this->element('Index/Entities/entity');
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
	if(isset($address) && is_array($address)){
		echo $this->element('Index/Entities/address');
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




