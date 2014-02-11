<?php
App::uses('AppModel', 'Model');
/**
 * Contact Model
 *
 * @property User $User
 */
class Contact extends AppModel {
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