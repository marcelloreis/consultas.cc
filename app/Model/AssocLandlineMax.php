<?php
App::uses('AppModelClean', 'Model');
/**
 * AssocLandlineMax Model
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
 * AssocLandlineMax Model
 *
 * @property Country $Country
 * @property City $City
 */
class AssocLandlineMax extends AppModelClean {
    public $useTable = 'assoc_landline_max';
	public $order = array('AssocLandlineMax.year' => 'desc');

	/**
	* Recursive
	*
	* @var integer
	*/
	public $recursive = -1;	


	public $belongsTo = array(
        'Address' => array(
            'className' => 'Address',
            'foreignKey' => 'address_id'
        ),
        'Landline' => array(
            'className' => 'Landline',
            'foreignKey' => 'landline_id'
        ),
        'Entity' => array(
            'className' => 'Entity',
            'foreignKey' => 'entity_id'
        )
	);
}
