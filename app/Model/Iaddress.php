<?php
App::uses('AppModelClean', 'Model');
/**
 * Iaddress Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Estado, 
 * é responsável também pela validação dos seus dados.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Model
 *
 * Iaddress Model
 *
 * @property Country $Country
 * @property City $City
 */
class Iaddress extends AppModelClean {
	public $useTable = 'i_addresses';

	public function findImport($type, $params){
		$hasAddress = $this->find($type, $params);				

		if(!count($hasAddress)){
			$this->setSource('addresses');
			$hasAddress = $this->find($type, $params);
			$this->setSource('i_addresses');
		}	

		return $hasAddress;		
	}	
}
