<?php
App::uses('AppModelClean', 'Model');
/**
 * EntityLandlineAddress Model
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
 * EntityLandlineAddress Model
 *
 * @property Country $Country
 * @property City $City
 */
class EntityLandlineAddress extends AppModelClean {
	public $useTable = 'i_entities_landlines_addresses';

	public function findImport($type, $params){
		$hasCreated = $this->find($type, $params);				

		if(!count($hasCreated)){
			$this->setSource('entities_landlines_addresses');
			$hasCreated = $this->find($type, $params);
			$this->setSource('i_entities_landlines_addresses');
		}	

		return $hasCreated;		
	}	
}
