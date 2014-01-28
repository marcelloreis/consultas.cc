<?php
App::uses('AppModel', 'Model');
/**
 * SmsSent Model
 *
 * @property User $User
 */
class SmsSent extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'log';

	/**
	* Table
	*
	* @var string
	*/
	public $useTable = 'sms_sent';

	/**
	* belongsTo
	*
	* @var array
	*/
	public $belongsTo = array('SmsCampaign');



}