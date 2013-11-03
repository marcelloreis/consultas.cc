<?php
App::uses('AppModelClean', 'Model');
/**
 * Iassociation Model
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
 * Iassociation Model
 *
 * @property Country $Country
 * @property City $City
 */
class Iassociation extends AppModelClean {
	public $useTable = 'i_associations';
	/**
	* Recursive
	*
	* @var integer
	*/
	public $recursive = -1;

	public function findImport($type, $params){
		$hasCreated = $this->find($type, $params);				

		if(!count($hasCreated)){
			$this->setSource('associations');
			$hasCreated = $this->find($type, $params);
			$this->setSource('i_associations');
		}	

		return $hasCreated;		
	}	
}
