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
App::uses('CakeEmail', 'Network/Email');

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
        /**
        * Carrega os filtros do painel de buscas
        */
        $this->filters = array(
            'status' => array(true => 'Ativos', false => 'Inativos'),
            'state_id' => $this->Client->State->find('list', array('id', 'uf')),
            'natures_legal_id' => $this->Client->NaturesLegal->find('list', array('id', 'name')),
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
		 * Verifica se o formulário foi submetido por post
		 */
		if ($this->request->is('post') || $this->request->is('put')) {
			/**
			* Carrega os valores padrao para todo novo cliente
			*/
			if(empty($this->request->data['Client']['id'])){
				$this->request->data['Client']['status'] = CLIENT_STATUS_NO_PAID;
			}
		}		

		/**
		* Adiciona a conta administradora do cliente
		*/
		if(!empty($id)){
			/**
			* Verifica se o cliente ja tem uma conta principal/administradora
			*/
			$hasAccount = $this->Client->User->find('count', array(
				'recursice' => -1,
				'conditions' => array(
					'User.client_id' => $id,
					'User.master_account' => true
					)
				));
			if(!$hasAccount){
				$name = !empty($this->data['Client']['contact_name'])?$this->data['Client']['contact_name']:"Cliente - {$id}";
				$given_name = substr($name, 0, strpos("{$name} ", ' '));
				$email = !empty($this->data['Client']['email'])?$this->data['Client']['email']:Inflector::variable($given_name) . '@' . SHORT_LINK;
				$data = array(
					'User' => array(
						'group_id' => CLIENT_GROUP,
						'client_id' => $id,
						'name' => $name,
						'given_name' => $given_name,
						'password' => 123456,
						'email' => $email,
						'master_account' => true,
						'status' => true,
						)
					);
				$this->Client->User->create();
				if($this->Client->User->save($data)){
					/**
					* Dispara um email para o usuario que criou a campanha, avisando que os arquivos ja estao disponiveis
					*/
					$obj_email = new CakeEmail('apps');
					$obj_email->template('master-account');
					$obj_email->emailFormat('html');
					$obj_email->viewVars(array('user' => $data));

					$obj_email->sender(array(EMAIL_NO_REPLAY => TITLE_APP));
					$obj_email->from(array(EMAIL_NO_REPLAY => TITLE_APP));
					// $obj_email->to($email);
					$obj_email->to('marcello@marcelloreis.com');
					$obj_email->subject("Bem vindo ao " . TITLE_APP);
					if($obj_email->send()){
						$this->Session->setFlash("Um email com o login e senha foi enviados para o endereço: <strong>{$email}</strong>", FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
					}else{
						$this->Session->setFlash("Não foi possível enviar um email com login e senha para o endereço: <strong>{$email}</strong>", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
					}
				}
			}

			/**
			* Verifica se ja foi emitido o boleto da assinatura para o cliente
			*/
            $hasSignature = $this->Client->Invoice->find('count', array(
                'recursive' => -1,
                'conditions' => array(
                    'Invoice.client_id' => $id,
                    )
                ));

			if(!$hasSignature){
				/**
				* Carrega o banco de cobrança ativo
				*/
				$maturity = date('Y-m-d', mktime(0, 0, 0, date('m'), (date('d') + MATURITY_DAY_DEFAULT), date('Y')));
				$data = array(
					'Invoice' => array(
						'client_id' => $id,
						'package_id' => $this->data['Package']['id'],
						'value' => $this->AppUtils->num2db($this->data['Package']['price']),
						'maturity' => $maturity,
						'is_signature' => true,
						)
					);
				$this->Client->Invoice->create();
				if($this->Client->Invoice->save($data)){
					/**
					* Gera o token do boleto 
					*/
					$token = preg_replace('/[^0-9]/si', '', "{$this->Client->Invoice->id}{$id}{$this->data['Package']['id']}{$maturity}" . substr(uniqid(), -4));
					$this->Client->Invoice->updateAll(array('token' => $token), array('Invoice.id' => $this->Client->Invoice->id));
					
					/**
					* Monta o link para download do boleto
					*/
					$link = PROJECT_LINK . "invoices/" . BANK_ACTIVE . "/{$token}";

					/**
					* Dispara um email para o usuario que criou a campanha, avisando que os arquivos ja estao disponiveis
					*/
					$obj_email = new CakeEmail('apps');
					$obj_email->template('invoice-signature');
					$obj_email->emailFormat('html');
					$obj_email->viewVars(array('link' => $link, 'client' => $this->data));

					$obj_email->sender(array(EMAIL_NO_REPLAY => TITLE_APP));
					$obj_email->from(array(EMAIL_NO_REPLAY => TITLE_APP));
					// $obj_email->to($this->data['Client']['email']);
					$obj_email->to('marcello@marcelloreis.com');
					$obj_email->subject('Assinatura | Instruções para pagamento do boleto.');
					if($obj_email->send()){
						$this->Session->setFlash("Um email com o boleto da assinatura foi enviados para o endereço: <strong>{$this->data['Client']['email']}</strong>", FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
					}else{
						$this->Session->setFlash("Não foi possível enviar o boleto da assinatura para o endereço: <strong>{$this->data['Client']['email']}</strong>", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
					}
				}
			}
		}		

		/**
		* Carrega todos as cidades cadastradas a partir do estado passado pelo parametro
		*/
		$state_id = !empty($this->data['Client']['state_id'])?$this->data['Client']['state_id']:null;
		$cities = $this->Client->City->loadByState($state_id);

		$this->set(compact('cities'));		
	}

	/**
	* Método contract
	* Gera o contrato do cliente a partir dos seus dados
	*
	* @param string $id
	* @return void
	*/
	public function contract($id){
		$this->layout = 'default-clean';

		$this->Client->recursive = 1;
		$client = $this->Client->findById($id);

		/**
		* Carrega todos os produtos disponiveis
		*/
		$products = $this->Client->Package->Product->find('list');

		/**
		* Carrega os valores dos produtos disponiveis de acordo com o pacote do contrato
		*/
		$prices = $this->Client->Package->Product->Price->find('list', array(
			'recursive' => -1,
			'fields' => array('Price.product_id', 'Price.price'),
			'conditions' => array(
				'Price.package_id' => $client['Client']['package_id'],
				'Price.product_id' => array_flip($products),
				)
			));
		$this->set(compact('client', 'products', 'prices'));
	}

	public function download($id){
		$this->contract($id);

	    $params = array(
	        'download' => true,
	        // 'name' => 'nome-do-contrato.pdf',
	        'paperOrientation' => 'portrait',
	        'paperSize' => 'legal'
	    );
	    $this->set($params);		
	}
}