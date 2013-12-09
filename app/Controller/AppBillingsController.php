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
	protected $package_id;
	protected $billing_id;
	protected $cache_id;
	protected $validity_orig;
	protected $balance;
	protected $price_id;
	protected $price;
	protected $tp_search;

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
		$this->balance = $this->Billing->balance($this->Session->read('Auth.User.Client.id'));
		$this->set('balance', $this->balance);

        /**
        * Carrega os dados da ultima bilhetagem do cliente
        */
        $this->billing_id = $this->Session->read('Client.billing_id');
        $this->package_id = $this->Session->read('Client.package_id');
        $this->validity_orig = $this->Session->read('Client.validity_orig');
		$this->set('validity_orig', $this->validity_orig);

		/**
		* Carrega o preço do produto consumido
		*/
		$this->price = $this->Session->read("Billing.prices_val.{$this->package_id}.{$this->product_id}");

		/**
		* Carrega o id do preço do produto consumido
		*/
		$this->price_id = $this->Session->read("Billing.prices_id.{$this->package_id}.{$this->product_id}");

		/**
		* Verificar se o usuario logado tem autorizacao para efetuar as consultas
		*/
		$this->security();
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
		* Efetua a cobraça
		*/
		$this->charge();
	}		

    /**
	* Método isInfinite
	* Regras de autorização configurados para verificar se o usuário tem permissoes Master, 
	* ou seja, caso o suario tenha acesso master, nao sera necessario a verificacao de segurança
	*
	* @return void
	*/
	private function isInfinite(){
		$return = false;
		if($this->userLogged['infinite']){
			$return = true;
		}

		return $return;
	}

    /**
	* Método security
	* Regras de autorização configurados para verificar se o usuário esta autorizado para realizar as consultas no sistema. 
	* Cada regra será verificada na sequência, se o usuario logado atender a todas, 
	* então ele nao sera redirecionado e a consulta ira presseguir normalmente
	*
	* @return void
	*/
	private function security(){
		/**
		* Caso o usuario nao tenho acesso ilimitado as consultas, 
		* verificar se o usuario logado tem autorizacao para efetuar as consultas
		*/
		if(!$this->isInfinite()){
			/**
			* Verifica se o usuario ja efetuou a compra dos creditos
			*/
			if(is_null($this->billing_id)){
				$this->Session->setFlash("{$this->userLogged['given_name']}, " . 'ainda não constam créditos em sua conta para realizar este tipo de consulta.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
				$this->redirect(array('controller' => 'packages', 'action' => 'pricing'));
			}

			/**
			* Verifica se o usuario tem saldo para efetuar a pesquisa
			*/
			if($this->AppUtils->num2db($this->price) > $this->balance){
				$this->Session->setFlash("{$this->userLogged['given_name']}, " . 'seu saldo é insuficiênte para realizar consultas.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
				$this->redirect(array('controller' => 'packages', 'action' => 'pricing'));
			}

			/**
			* Verifica se o saldo do usuario esta dentro da validade
			*/
			if($this->validity_orig < date('Y-m-d')){
				$this->Session->setFlash("{$this->userLogged['given_name']}, " . 'seu saldo expirou.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
				$this->redirect(array('controller' => 'packages', 'action' => 'pricing'));
			}

			/**
			* Verifica se o cliente ja esta ativo (se o contrato ja foi gerado)
			*/
			$contract_id = $this->Session->read('Client.contract_id');
			if(empty($contract_id)){
				$this->Session->setFlash("{$this->userLogged['given_name']}, " . 'Seu contrato ainda não foi gerado, procure o setor administrativo e regularize sua situação.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
				$this->redirect(array('controller' => 'packages', 'action' => 'pricing'));
			}
		}		
	}

    /**
	* Método changeRules
	* Regras de autorização para a bilhetagem da consulta.
	* Este metodo contem as verificacoes para liberar ou nao as conbraças por consultas
	*
	* @return void
	*/
	private function changeRules(){
		$enabled = true;

		/**
		* Desabilita a bilhetagem caso a pagina carregada seja a de localizacao no mapa
		*/
		if($this->view == 'map'){
			$enabled = false;
		}

		/**
		* Desabilita a bilhetagem quando o usuario estiver somente atualizando a pagina já bilhetada
		*/
		if($this->params->here == $this->Session->read('Billing.changedPage')){
			$enabled = false;
		}

		/**
		* Desabilita a bilhetagem quando a consulta realizada nao trouxer registros
		*/
		if(is_null($this->entity) || !count($this->entity)){
			$enabled = false;
		}

		/**
		* Desabilita a bilhetagem quando o usuario tiver acesso a consultas ilimitadas
		*/
		if($this->isInfinite()){
			$enabled = false;
		}

		return $enabled;
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
		* Desabilita o cache da bilhetagem
		*/
		$this->cacheAction = 0;

    	/**
    	* Verifica se a consulta realizada pode ser bilhetada
    	*/
    	if($this->changeRules()){
    		/**
    		* Recarrega o cache de paginas cobradas
    		*/
    		if(!$this->RequestHandler->isAjax()){
    			$this->Session->write('Billing.changedPage', $this->params->here);
    		}

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
					'billing_id' => $this->billing_id,
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
				array(
					"Billing.consumed" => "(ifnull(Billing.consumed, 0) + " . $this->AppUtils->num2db($this->price) . ")",
					"Billing.qt_queries" => "(ifnull(Billing.qt_queries, 0) + 1)",
					"Billing.modified" => "NOW()",
					),
				array('Billing.id' => $this->billing_id)
				);

			/**
			* Recarrega o saldo do cliente subtraindo o saldo atual com o valor da consulta realizada
			*/
			$this->balance = ($this->balance - $this->AppUtils->num2db($this->price));
			$this->set('balance', $this->balance);
		}
	}
}
