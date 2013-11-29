<?php
App::uses('AppModel', 'Model');
/**
 * Contract Model
 *
 * @property User $User
 */
class Contract extends AppModel {
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
    	'contract_ini_day' => "DATE_FORMAT(Contract.contract_ini, '%d')",
    	'contract_ini_month' => "DATE_FORMAT(Contract.contract_ini, '%m')",
    	'contract_ini_year' => "DATE_FORMAT(Contract.contract_ini, '%Y')",
    	'contract_end' => "DATE_ADD(Contract.contract_ini, INTERVAL Contract.validity MONTH)",
    	'contract_end_day' => "DATE_FORMAT(DATE_ADD(Contract.contract_ini, INTERVAL Contract.validity MONTH), '%d')",
    	'contract_end_month' => "DATE_FORMAT(DATE_ADD(Contract.contract_ini, INTERVAL Contract.validity MONTH), '%m')",
    	'contract_end_year' => "DATE_FORMAT(DATE_ADD(Contract.contract_ini, INTERVAL Contract.validity MONTH), '%Y')",
	);

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array(
		'User' => array(
			'conditions' => array(
				'User.client_id' => null
				)
			), 
		'Client'
		);
}
