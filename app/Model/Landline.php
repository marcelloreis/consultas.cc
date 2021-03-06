<?php
App::uses('AppModelClean', 'Model');
/**
 * Landline Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Landline, 
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
 * @property Address $Address
 * @property Landline $Landline
 */
class Landline extends AppModelClean {

	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'tel_full';

	/**
	* Virtual fields
	*
	* @var string
	*/
	public $virtualFields = array(
    	'tel_txt' => "concat('(', ifnull(Landline.ddd, 0), ') ', left(Landline.tel, 4) , '-' , right(Landline.tel, 4))",
	);

	/**
	* Recursive
	*
	* @var integer
	*/
	public $recursive = -1;	

	/**
	* hasMany associations
	*
	* @var array
	*/
	public $hasMany = array(
        'Association' => array(
            'className' => 'Association',
            'foreignKey' => 'landline_id',
            'order' => array('Association.year' => 'desc'),
            'type' => 'INNER'
        )
	);	

	/**
	* hasAndBelongsToMany associations
	*
	* @var array
	*/
	public $hasAndBelongsToMany = array(
		'Entity' => array(
			'className' => 'Entity',
			'joinTable' => 'associations',
			'foreignKey' => 'landline_id',
			'associationForeignKey' => 'entity_id',
			'unique' => 'keepExisting',
		),
		'Address' => array(
			'className' => 'Address',
			'joinTable' => 'associations',
			'foreignKey' => 'landline_id',
			'associationForeignKey' => 'address_id',
			'unique' => 'keepExisting',
		),
	);

	/**
	* Procura os telefones a partir do DDD e Telefone passados por parametro
	*/
	public function _findLandline($ddd, $tel){
		$this->recursive = 1;

		$phones = $this->find('all', array(
				'conditions' => array(
					'OR' => array(
						'Landline.tel' => $tel,
						'Landline.tel_full' => $tel,
						)
					),
				'limit' => LIMIT_SEARCH
				));

		return $phones;
	}

}
