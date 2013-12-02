<?php
App::uses('AppModel', 'Model');
/**
 * Billing Model
 *
 * @property User $User
 */
class Billing extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'consumed';

	/**
	* Virtual fields
	*
	* @var string
	*/
	public $virtualFields = array(
    	'balance' => "SUM((Billing.paid)-ifnull(Billing.consumed, 0))",
    	'validity_txt' => "DATE_FORMAT(Billing.validity , '%d de %b, %Y' )",
    	'validity_orig' => "Billing.validity",
	);

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array('Client', 'Package');

	/**
	* Esta funcao retorno o saldo atual do cliente/contas logado
	*/
	public function balance($client_id){
		$this->recursive = -1;

		/**
		* Carrega o saldo atual do cliente
		*/
		$billing = $this->findByClientId($client_id);

		return (!empty($billing['Billing']['balance']))?$billing['Billing']['balance']:0;
	}
}
