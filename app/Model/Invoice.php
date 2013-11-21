<?php
App::uses('AppModel', 'Model');
/**
 * Invoice Model
 *
 * @property User $User
 */
class Invoice extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'value';

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array('Client');
}
