<?php
App::uses('AppModel', 'Model');
/**
 * Query Model
 *
 * @property User $User
 */
class Query extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'conditions';

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array('User', 'Billing', 'Price');
}
