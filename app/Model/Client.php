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
	* Virtual fields
	*
	* @var string
	*/
	public $virtualFields = array(
    	'contract_ini_day' => "DATE_FORMAT(Client.contract_ini, '%d')",
    	'contract_ini_month' => "DATE_FORMAT(Client.contract_ini, '%m')",
    	'contract_ini_year' => "DATE_FORMAT(Client.contract_ini, '%Y')",
	);

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array('City', 'State', 'NaturesLegal', 'Package');

	/**
	* hasMany associations
	*
	* @var array
	*/
	public $hasMany = array('User', 'Billing', 'Invoice');
}
