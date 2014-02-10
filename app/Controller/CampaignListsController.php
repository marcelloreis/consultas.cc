<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Lists/
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
 * Este controlador contem regras de negócio aplicadas ao model Country
 *
 * @package       app.Controller
 * @link http://.framework.nasza.com.br/2.0/controller/Lists.html
 */
class CampaignListsController extends AppController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'CampaignList';

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($params=array()){
		/**
		* Carrega os grupos apenas do usuario logado
		*/
		$params['conditions']['CampaignList.user_id'] = $this->Session->read('Auth.User.id');

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
