<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Contracts/
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
 * @link http://.framework.nasza.com.br/2.0/controller/Contracts.html
 */
class ContractsController extends AppController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'Contracts';

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($params=array()){
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
		$this->isRedirect = false;

		//@override
		parent::edit($id);		

		/**
		 * Verifica se o formulário foi submetido por post
		 */
		if ($this->request->is('post') || $this->request->is('put')) {
			/**
			* Salva o id do contrato no registro do cliente
			*/
			if(!empty($this->Contract->id)){
	            $data['Client']['id'] = $this->data['Contract']['client_id'];
	            $data['Client']['contract_id'] = $this->Contract->id;
	            $this->Contract->Client->save($data);				

				$this->Session->setFlash(FLASH_SAVE_SUCCESS, FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
				$this->redirect(array('action' => 'edit', $this->Contract->id));
			}
		}	
	}

	private function buildContract($id){
		$this->Contract->recursive = 2;
		$contract = $this->Contract->findById($id);

		$this->set(compact('contract'));
	}

	/**
	* Método view
	* Encapsulamento da função EDIT para controle de acesso via ACL
	*
	* @override Metodo AppController.view
	* @param String $id
	* @return void
	*/
	public function view($id=null){
		$this->layout = 'default-clean';
		$this->buildContract($id);
	}

	public function download($id=null){
		$this->buildContract($id);

	    $params = array(
	        'download' => true,
	        // 'name' => 'nome-do-contrato.pdf',
	        'paperOrientation' => 'portrait',
	        'paperSize' => 'legal'
	    );
	    $this->set($params);		
	}

}