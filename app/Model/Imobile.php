<?php
App::uses('AppModelClean', 'Model');
/**
 * Imobile Model
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
 * Imobile Model
 *
 * @property Country $Country
 * @property City $City
 */
class Imobile extends AppModelClean {
	public $useTable = 'i_mobiles';
	/**
	* Recursive
	*
	* @var integer
	*/
	public $recursive = -1;

	public function findImport($type, $params){
		$hasMobile = $this->find($type, $params);				

		if(!count($hasMobile)){
			$this->setSource('mobiles');
			$hasMobile = $this->find($type, $params);
			$this->setSource('i_mobiles');
		}

		return $hasMobile;		
	}	
}
