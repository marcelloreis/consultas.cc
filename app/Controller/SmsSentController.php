<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/SmsSent/
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
 * @link http://.framework.nasza.com.br/2.0/controller/SmsSent.html
 */
class SmsSentController extends AppController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'SmsSent';

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
		* Verifica se o codigo da campanha foi passado por parametro
		*/
		if(!empty($this->params['named']['campaign_id']) && is_numeric($this->params['named']['campaign_id'])){
			/**
			* Busca o usuario que criou a campanha e verifica se é o mesmo que esta logado
			*/
			$this->SmsSent->Campaign->recursive = -1;
			$campaign = $this->SmsSent->Campaign->findById($this->params['named']['campaign_id']);
			if($campaign['Campaign']['user_id'] != $this->Session->read('Auth.User.id')){
				$this->Session->setFlash("{$this->userLogged['given_name']}, você não pode visualizar o relatório de uma campanha que não seja sua.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
				$this->redirect(array('controller' => 'sms_campaigns'));
			}
			
			/**
			* Carrega o o log de SMSs enviados somente da campanha passada por parametro
			*/
			$params['conditions'] = array(
				'SmsSent.campaign_id' => $this->params['named']['campaign_id']
				);
		}else{
			$this->Session->setFlash("{$this->userLogged['given_name']}, você informar para qual campanha deseja o relatório..", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
			$this->redirect(array('controller' => 'campaigns'));
		}


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
