<?php
App::uses('AppModel', 'Model');
/**
 * Client Model
 *
 * @property User $User
 */
class Client extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'fancy_name';

	/**
	* hasOne associations
	*
	* @var array
	*/
	public $belongsTo = array('Package', 'State');
}
