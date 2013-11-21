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
	* hasOne associations
	*
	* @var array
	*/
	public $belongsTo = 'Group';
}
