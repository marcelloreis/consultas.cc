<?php
App::uses('AppModel', 'Model');
/**
 * Campaign Model
 *
 * @property User $User
 */
class Campaign extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'title';

	/**
	* hasMany
	*
	* @var array
	*/
	public $hasMany = array('SmsSent');

	/**
	* belongsTo
	*
	* @var array
	*/
	public $belongsTo = array('User', 'Client');



}