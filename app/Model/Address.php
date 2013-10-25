<?php
App::uses('AppModel', 'Model');
/**
 * Address Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Address, 
 * é responsável também pela validação dos seus dados.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Model
 *
 * Address Model
 *
 * @property Address $Address
 * @property Address $Address
 */
class Address extends AppModel {

	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'street';

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
        'EntityLandlineAddress' => array(
            'className' => 'EntityLandlineAddress',
            'foreignKey' => 'address_id'
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
			'joinTable' => 'entities_landlines_addresses',
			'foreignKey' => 'entity_id',
			'associationForeignKey' => 'address_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

}
