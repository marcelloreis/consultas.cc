<?php
App::uses('AppModel', 'Model');
/**
 * SmsTemplate Model
 *
 * @property User $User
 */
class SmsTemplate extends AppModel {
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