<?php
App::uses('AppModelClean', 'Model');
/**
 * Ientity Model
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
 * Ientity Model
 *
 * @property Country $Country
 * @property City $City
 */
class Ientity extends AppModelClean {
	public $useTable = 'i_entities';
	/**
	* Recursive
	*
	* @var integer
	*/
	public $recursive = -1;


	public function findImport($type, $params){
		$hasEntity = $this->find($type, $params);				

		if(!count($hasEntity)){
			$this->setSource('entities');
			$hasEntity = $this->find($type, $params);
			$this->setSource('i_entities');
		}	

		return $hasEntity;		
	}
}
