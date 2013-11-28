<?php
App::uses('AppController', 'Controller');
/**
 * Application level Controller
 *
 * Area destinada a funcoes especificas do projeto, estas funcoes nao pertencem a framework
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppBillingsController extends AppController {
	/**
	* Declaracao dos componentes
	*/
	public $components = array('AppImport');

	/**
	* Este atributo guarda todas as informacoes encontrada do consultado
	*/
	protected $entity;
	protected $phone;
	protected $address;

	protected $product_id;
	protected $price_id;
	protected $price;
	protected $tp_search;
	private $billing;

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
		* Carrega o saldo atual do cliente
		*/
		$this->loadModel('Billing');
		$this->billing = $this->Billing->balance($this->Session->read('Auth.User.Client.id'));
		$this->set('billing', $this->billing['Billing']);

		/**
		* Carrega o preço do produto consumido
		*/
		$this->price = $this->Session->read("Billing.prices_val.{$this->billing['Billing']['package_id']}.{$this->product_id}");

		/**
		* Carrega o id do preço do produto consumido
		*/
		$this->price_id = $this->Session->read("Billing.prices_id.{$this->billing['Billing']['package_id']}.{$this->product_id}");

		/**
		* Verifica se o usuario tem saldo para efetuar a pesquisa
		*/
		if($this->AppUtils->num2db($this->price) > $this->billing['Billing']['balance']){
			$this->Session->setFlash("{$this->userLogged['given_name']}, " . __('Seu saldo é insuficiênte para realizar esta consulta.'), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
			$this->redirect(array('controller' => 'packages', 'action' => 'pricing'));
		}

		if($this->billing['Billing']['validity_orig'] < date('Y-m-d')){
			$this->Session->setFlash("{$this->userLogged['given_name']}, " . __('Seu saldo expirou.'), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
			$this->redirect(array('controller' => 'packages', 'action' => 'pricing'));
		}
	}

    /**
	* Método beforeRender
    * Chamado depois controlador com as regras de negócio, mas antes da visão ser renderizada.
	*
	* @override Metodo AppController.beforeRender
	* @return void
	*/
	public function beforeRender(){
		//@override
		parent::beforeRender();

		/**
		* Executa a cobrança caso a busca tenha retornado dados
		*/
		if(!is_null($this->entity)){
			/**
			* Carrega o tipo da consulta
			*/
			switch ($this->action) {
				case 'index':
					$this->tp_search = TP_SEARCH_ID;
					break;
				case 'doc':
					$this->tp_search = TP_SEARCH_DOC;
					break;
				case 'name':
					$this->tp_search = TP_SEARCH_NAME;
					break;
				case 'landline':
					$this->tp_search = TP_SEARCH_PHONE;
					break;
				case 'mobile':
					$this->tp_search = TP_SEARCH_MOBILE;
					break;
				case 'address':
					$this->tp_search = TP_SEARCH_ADDRESS;
					break;
			}

			/**
			* Efetua a cobraça
			*/
			$this->charge();
		}
	}	
    
	/**
	* Método charge
	* Esta função é responsavel pela bilhetagem das consultas, ou seja, a cada consulta realizada a 
	* funcao charge (cobrar) é chamada para subtrair o valor da consulta no saldo do cliente
	*
	* @return void
	*/
    private function charge(){
    	/**
    	* Verifica se o usuario nao esta somente atualizando a pagina já bilhetada
    	* e se a pagina nao é o mapa de localizacao de entidades
    	*/
    	if($this->view != 'map' && $this->Session->read('Location.referer') != $this->params->here){
    		/**
    		* Registra a pagina acessada na session para a verificacao acima
    		*/
    		$this->Session->write('Location.referer', $this->params->here);

			/**
			* Carrega o modulo de registro de consultas
			*/
			$this->loadModel('Query');

			/**
			* Prepara os dados para bilhetar a consulta realizada
			*/
			$data = array(
				'Query' => array(
					'user_id' => $this->Session->read('Auth.User.id'),
					'billing_id' => $this->billing['Billing']['id'],
					'price_id' => $this->price_id,
					'tp_search' => $this->tp_search,
					'query' => $this->here,
					)
				);

			/**
			* Salva os dados da consulta
			*/
			$this->Query->save($data);

			/**
			* Debita a consulta no saldo do cliente
			*/
			$this->Billing->updateAll(
				array("Billing.consumed" => "(ifnull(Billing.consumed, 0) + " . $this->AppUtils->num2db($this->price) . ")"),
				array('Billing.id' => $this->billing['Billing']['id'])
				);

			/**
			* Recarrega o saldo do cliente subtraindo o saldo atual com o valor da consulta realizada
			*/
			$this->billing['Billing']['balance'] = ($this->billing['Billing']['balance'] - $this->AppUtils->num2db($this->price));
			$this->set('billing', $this->billing['Billing']);
		}
	}
}