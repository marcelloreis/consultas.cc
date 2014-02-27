<?php
App::uses('AppModel', 'Model');
/**
 * Billing Model
 *
 * @property User $User
 */
class Billing extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'franchise';

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array('Client', 'Package');
}
