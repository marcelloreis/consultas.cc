<?php
App::uses('AppModel', 'Model');
/**
 * Product Model
 *
 * @property User $User
 */
class Product extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'name';

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array(
		'Aco' => array(
			'className' => 'Aco',
			'foreignKey' => 'aco_id',
			'conditions' => array('Aco.is_product' => true),
			'fields' => array('Aco.id', 'Aco.alias'),
		)
	);

	/**
	* hasAndBelongsToMany associations
	*
	* @var array
	*/
	public $hasAndBelongsToMany = array(
		'Package' => array(
			'className' => 'Package',
			'joinTable' => 'packages_products',
			'foreignKey' => 'product_id',
			'associationForeignKey' => 'package_id',
			'unique' => true,
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
