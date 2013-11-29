<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Clients/
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
 * @link http://.framework.nasza.com.br/2.0/controller/Clients.html
 */
class ClientsController extends AppController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'Clients';

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
            'conditions' => array('Client.contract_id NOT' => null)
            );

		//@override
		parent::index($params);
	}	

	/**
	* Método prospect
	* Este método contem regras de negocios para adicionar o cliente que vem do formulario do site
	*
	* @override Metodo ClientsController.edit
	* @return void
	*/
	public function prospects($id=null){
        $params = array(
            'conditions' => array('Client.contract_id' => null)
            );

		//@override
		parent::index($params);

		$packages = $this->Client->Billing->Package->find('list', array('fields' => array('Package.id', 'Package.name')));
		$this->set(compact('packages'));
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
        /**
        * Inicializa a variavel $account considerando que o usuario é de sistema e nao de conta de cliente
        */
        $isProspect = false;

        /**
        * Verifica se o usuario é de contas/clientes ou usuario do sistema
        */
        if(isset($this->params['named']['prospect_pkg_id'])){
            $isProspect = true;
        }

		//@override
		parent::edit($id);		

		$this->set(compact('isProspect'));
	}
}