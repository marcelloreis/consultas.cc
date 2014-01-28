<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Billings/
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Controller
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Este controlador contem regras de negócio aplicadas ao model Group
 *
 * @package       app.Controller
 * @link http://.framework.nasza.com.br/2.0/controller/Billings.html
 */
class BillingsController extends AppController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'Billings';

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($params=array()){
		$params = array(
			'group' => 'Billing.client_id',
			'order' => array('Billing.created' => 'desc'),
			);

		/**
		* Carrega os filtros do painel de buscas
		*/
		$this->filters = array(
			'client_id' => $this->Billing->Client->find('list', array('id', 'name')),
			'package_id' => $this->Billing->Package->find('list', array('id', 'name')),
			);
		
		//@override
		parent::index($params);
	}	

	/**
	* Método edit
	* Este método contem regras de negocios para adicionar e editar registros na base de dados
	*
	* @override Metodo AppController.edit
	* @param string $id
	* @return void
	*/
	public function edit($id=null){

		//@override
		parent::edit($id);

		/**
		* Atualiza o prazo de validade dos creditos comprados com base na validade do pacote comprado
		*/
		if(!empty($this->request->data['Billing']['package_id'])){
			/**
			* Carrega o prazo de validade do pacote selecionado
			*/
			$this->Billing->Package->recursive = -1;
			$package = $this->Billing->Package->findById($this->request->data['Billing']['package_id']);
			$validity_days = $package['Package']['validity_days'];

			/**
			* Carrega a validade dos creditos de acordo com a validade do pacote adiquirido
			*/
			$day = substr($this->request->data['Billing']['created_db'], 8, 2);
			$month = substr($this->request->data['Billing']['created_db'], 5, 2);
			$year = substr($this->request->data['Billing']['created_db'], 0, 4);

			$validity = date('Y-m-d', mktime(0, 0, 0, $month, ($day + $validity_days), $year));

			/**
			* Salva a validade dos creditos
			*/
			$this->Billing->saveField('validity', $validity);
		}
	}
}