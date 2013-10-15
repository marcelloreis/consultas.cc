<?php
App::uses('AppModelClean', 'Model');
/**
 * Landline Model
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
 * Landline Model
 *
 * @property Country $Country
 * @property City $City
 */
class Landline extends AppModelClean {
	public $useTable = 'i_landlines';

	public function findImport($type, $params){
		$hasLandline = $this->find($type, $params);				

		if(!count($hasLandline)){
			$this->setSource('landlines');
			$hasLandline = $this->find($type, $params);
			$this->setSource('i_landlines');
		}	

		return $hasLandline;		
	}	
}
