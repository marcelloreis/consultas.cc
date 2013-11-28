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
	* hasAndBelongsToMany associations
	*
	* @var array
	*/
	public $hasAndBelongsToMany = array(
		'Package' => array(
			'className' => 'Package',
			'joinTable' => 'prices',
			'foreignKey' => 'product_id',
			'associationForeignKey' => 'package_id',
			'unique' => true,
		)
	);	

}
