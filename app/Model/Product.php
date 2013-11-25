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
		'AcoPro' => array(
			'className' => 'AcoPro',
			'foreignKey' => 'aco_id',
			'conditions' => '',
			// 'fields' => array('AcoPro.alias', 'AcoPro.alias'),
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
		)
	);	

}
