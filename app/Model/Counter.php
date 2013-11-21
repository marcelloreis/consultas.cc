<?php
App::uses('AppModel', 'Model');
/**
 * Counter Model
 *
 * @property User $User
 */
class Counter extends AppModel {
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
	public $belongsTo = array('User', 'Product', 'Package', 'Invoice');
}
