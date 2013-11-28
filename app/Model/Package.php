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
	* hasMany associations
	*
	* @var array
	*/
	public $hasMany = 'Price';

	/**
	* hasAndBelongsToMany associations
	*
	* @var array
	*/
	public $hasAndBelongsToMany = array(
		'Product' => array(
			'className' => 'Product',
			'joinTable' => 'prices',
			'foreignKey' => 'package_id',
			'associationForeignKey' => 'product_id',
			'order' => array('created'),
			'unique' => true,
		)
	);	
}
