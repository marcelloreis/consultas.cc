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
	public $displayField = 'id';

	/**
	* Virtual fields
	*
	* @var string
	*/
	public $virtualFields = array(
    	'days_expired' => "DATEDIFF(now(), Invoice.maturity)",
	);

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array('Client', 'Package');
}