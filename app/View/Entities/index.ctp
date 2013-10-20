<?php 
/**
* Painel de pesquisas
*/
echo $this->element('Index/Entities/panel');

if($entity){
	/**
	* Atalhos
	*/
	// echo $this->element('Index/Entities/shortcuts');
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
	echo $this->element('Index/Entities/landline');
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




