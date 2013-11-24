<?php
App::uses('AppModel', 'Model');
/**
 * Package Model
 *
 * @property User $User
 */
class Package extends AppModel {
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
	public $belongsTo = 'Group';

	/**
	* hasMany associations
	*
	* @var array
	*/
	public $hasMany = 'Client';

	/**
	* hasAndBelongsToMany associations
	*
	* @var array
	*/
	public $hasAndBelongsToMany = array(
		'Product' => array(
			'className' => 'Product',
			'joinTable' => 'packages_products',
			'foreignKey' => 'package_id',
			'associationForeignKey' => 'product_id',
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
