<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Queries/
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
 * @link http://.framework.nasza.com.br/2.0/controller/Queries.html
 */
class QueriesController extends AppController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'Queries';

	private $tp_search;

	/**
	* Método beforeFilter
	* Esta função é executada antes de todas ações do controlador. 
	* E no caso da framework, esta sendo usado para checar uma sessão ativa e inspecionar permissões.
	*
	* @override Metodo AppController.beforeFilter
	* @return void
	*/
	public function beforeFilter() {
		//@override
		parent::beforeFilter();

		/**
		* Carega os tipos de consultas
		*/
		$this->tp_search = array(
			TP_SEARCH_ID => 'ID',
			TP_SEARCH_DOC => 'Documento',
			TP_SEARCH_NAME => 'Nome',
			TP_SEARCH_PHONE => 'Telefone',
			TP_SEARCH_MOBILE => 'Tel. Móvel',
			TP_SEARCH_ADDRESS => 'Endereço',
			TP_SEARCH_EXTRA_MOBILE => 'Extra - Tel. Móvel',
			TP_SEARCH_EXTRA_LANDLINE => 'Extra - Telefone',
			TP_SEARCH_EXTRA_LOCATOR => 'Extra - Localizador',
			TP_SEARCH_EXTRA_FAMILY => 'Extra - Família',
			TP_SEARCH_EXTRA_NEIGHBORS => 'Extra - Vizinhos',
			TP_SEARCH_SMS => 'SMS',
			TP_SEARCH_MAILING => 'Mailing',
		);
	}

    /**
    * Chamado depois controlador com as regras de negócio, mas antes da visão ser renderizada.
	*
	* @override Metodo AppController.beforeRender
	* @return void
     */
    public function beforeRender(){
		//@override
    	parent::beforeRender();

    	/**
    	* Carrega os tipos de buscas para a view
    	*/
		$this->set('tp_search', $this->tp_search);
	}	

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($params=array()){
		unset($this->Query->Billing->virtualFields['balance']);

		/**
		* Carrega os filtros do painel de buscas
		*/
		$this->filters = array(
			'user_id' => $this->Query->User->find('list', array('id', 'name')),
			'tp_search' => $this->tp_search,
			);

		/**
		* Inverte a ordenaçao da lista de consultas
		*/
		$params['order'] = array('Query.id' => 'desc');

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
	}
}