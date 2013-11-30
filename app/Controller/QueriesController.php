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
		* Carega os tipos de consultas
		*/
		$tp_search = array(
			TP_SEARCH_ID => 'ID',
			TP_SEARCH_DOC => 'Documento',
			TP_SEARCH_PHONE => 'Telefone',
			TP_SEARCH_MOBILE => 'Tel. Móvel',
			TP_SEARCH_NAME => 'Nome',
			TP_SEARCH_ADDRESS => 'Endereço',
		);
		$this->set(compact('tp_search'));
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