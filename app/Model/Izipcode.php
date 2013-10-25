<?php
App::uses('AppModelClean', 'Model');
/**
 * Izipcode Model
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
 * Izipcode Model
 *
 * @property Country $Country
 * @property City $City
 */
class Izipcode extends AppModelClean {
	public $useTable = 'i_zipcodes';

	public function findImport($type, $params){
		$hasZipcode = $this->find($type, $params);				

		if(!count($hasZipcode)){
			$this->setSource('zipcodes');
			$hasZipcode = $this->find($type, $params);
			$this->setSource('i_zipcodes');
		}	

		return $hasZipcode;		
	}		
}
