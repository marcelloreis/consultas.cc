<?php 
$this->start('title-view');
echo $this->element('Components/People/title-view');
$this->end();

$this->start('sidebar');
echo $this->element('Components/People/sidebar');
$this->end();
?>

<?php 
/**
* Painel de pesquisas
*/
echo $this->element('Index/Entities/panel');

/**
* Atalhos
*/
// echo $this->element('Index/Entities/products');
if(isset($people)){
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
	if(isset($mobile) && is_array($mobile)){
		echo $this->element('Index/Entities/mobile');
	}
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
		// echo $this->element('Index/Entities/locator');
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




