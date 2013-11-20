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

}
