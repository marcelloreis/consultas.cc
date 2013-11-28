<?php
App::uses('AppModelClean', 'Model');
/**
 * Mobile Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Mobile, 
 * é responsável também pela validação dos seus dados.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Model
 *
 * Mobile Model
 *
 * @property Address $Address
 * @property Mobile $Mobile
 */
class Mobile extends AppModelClean {

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
    	'tel_txt' => "concat('(', ifnull(Mobile.ddd, 0), ') ', left(Mobile.tel ,5) , '-' , right(Mobile.tel, 4))",
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
            'foreignKey' => 'mobile_id',
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
			'foreignKey' => 'mobile_id',
			'associationForeignKey' => 'entity_id',
			'unique' => 'keepExisting',
		),
		'Address' => array(
			'className' => 'Address',
			'joinTable' => 'associations',
			'foreignKey' => 'mobile_id',
			'associationForeignKey' => 'address_id',
			'unique' => 'keepExisting',
		)
	);

	/**
	* Procura os telefones a partir do DDD e Telefone passados por parametro
	*/
	public function _findMobile($ddd, $tel){
		$this->recursive = 1;

		$phones = $this->find('all', array(
				'conditions' => array(
					'OR' => array(
						'Mobile.tel' => $tel,
						'Mobile.tel_full' => $tel,
						)
					),
				'limit' => LIMIT_SEARCH
				));

		return $phones;
	}

}
