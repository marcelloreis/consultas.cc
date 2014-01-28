<?php
App::uses('AppModel', 'Model');
/**
 * SmsGroup Model
 *
 * @property User $User
 */
class SmsGroup extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'title';

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array('Client', 'User');
}