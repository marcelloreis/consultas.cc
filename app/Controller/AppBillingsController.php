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

	protected $user_id;
	protected $product_id;
	protected $package_id;
	protected $billing_id;
	protected $cache_id;
	protected $hasSignature;
	protected $limit_exceeded;
	protected $client_status;
	protected $query;
	protected $balance;
	protected $price_id;
	protected $price;
	protected $tp_search;
	protected $user_name;

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
        * Carrega os dados da ultima bilhetagem do cliente
        */
        $this->user_id = $this->Session->read('Auth.User.id');
        $this->billing_id = $this->Session->read('Client.billing_id');
        $this->hasSignature = $this->Session->read('Client.signature');
        $this->package_id = $this->Session->read('Client.package_id');
        $this->client_status = $this->Session->read('Client.status');
        $this->query = $this->here;
        
        $this->limit_exceeded = $this->Session->read('Client.limit_exceeded');
		$this->set('limit_exceeded', $this->limit_exceeded);

		/**
		* Carrega o saldo atual do cliente
		*/
		$this->loadModel('Billing');
		$map = $this->Billing->findById($this->billing_id);
		$this->balance = 0;
		if(!empty($map['Billing'])){
			$this->balance = ($map['Billing']['franchise'] - $map['Billing']['qt_queries']);
			$this->balance = ($this->balance < 0)?0:$this->balance;
		}
		$this->set('balance', $this->balance);

		/**
		* Carrega o valor das consultas excedidas
		*/
		if(!empty($map['Billing']) && $this->balance <= 0){	
			$this->value_exceeded = $map['Billing']['value_exceeded'];
			$this->set('value_exceeded', $this->value_exceeded);
		}

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
		if($this->security()){
			throw new NotFoundException();
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
		* Efetua a cobraça
		*/
		$this->charge();
	}		

    /**
	* Método isUnlimited
	* Regras de autorização configurados para verificar se o usuário tem acesso a consultas ilimitadas, 
	* ou seja, caso o suario tenha acesso master, nao sera necessario a verificacao de segurança
	*
	* @return void
	*/
	private function isUnlimited(){
		$return = false;
		if(!empty($this->userLogged['unlimited']) && $this->userLogged['unlimited']){
			$return = true;
		}

		return $return;
	}

    /**
	* Método security
	* Regras de autorização configurados para verificar se o usuário esta autorizado para realizar as consultas no sistema. 
	* Cada regra será verificada na sequência, se o usuario logado atender a todas, 
	* então retorna false e o sistema ira presseguir normalmente
	*
	* @return void
	*/
	protected function security(){
		$return = false;
		$user_name = !empty($this->userLogged['given_name'])?$this->userLogged['given_name']:$this->user_name;

		/**
		* Caso o usuario nao tenho acesso ilimitado as consultas, 
		* verificar se o usuario logado tem autorizacao para efetuar as consultas
		*/
		if(!$this->isUnlimited()){

			/**
			* Verifica se o cliente já efetuou o pagamento da assinatura
			*/
			if(!$this->hasSignature){
				$this->Session->setFlash("{$user_name}, " . 'ainda não identificamos o pagamento da assinatura do seu contrato.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
				return true;
			}

			/**
			* Verifica se o cliente esta com o pagamento em dia e se ele nao esta bloqueado por qq outro motivo
			*/
			switch ($this->client_status) {
				case CLIENT_STATUS_BLOCK:
					$this->Session->setFlash("{$user_name}, " . 'seu contrato esta bloqueado temporáriamente, entre em contato com a nossa equipe de apoio.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
					return true;
					break;
				case CLIENT_STATUS_NO_PAID:
					$this->Session->setFlash("{$user_name}, " . 'ainda não identificamos o pagamento da sua última fatura.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
					return true;
					break;
			}

			/**
			* Verifica se o usuario tem saldo para efetuar a pesquisa
			*/
			if($this->balance <= 0 && !$this->limit_exceeded){
				$this->Session->setFlash("{$user_name}, " . 'seu saldo é insuficiênte para realizar consultas, Ligue para nosso pessoal de apoio.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
				$return = true;
			}

			/**
			* Verifica se o usuario tem saldo para efetuar a pesquisa
			*/
			if($this->balance <= 0 && $this->limit_exceeded){
				$this->Session->setFlash('ATENÇÃO!!! <br>O saldo do seu plano já se esgotou, neste momento você esta pagando o valor excedente.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
			}
		}

		return $return;		
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
		if($this->query == $this->Session->read('Billing.changedPage')){
			$enabled = false;
		}

		/**
		* Desabilita a bilhetagem quando a consulta realizada nao trouxer registros
		*/
		if(empty($this->entity)){
			$enabled = false;
		}


		/**
		* Desabilita a bilhetagem quando o usuario tiver acesso a consultas ilimitadas
		*/
		if($this->isUnlimited()){
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
    protected function charge(){
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
    			$this->Session->write('Billing.changedPage', $this->query);
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
					'user_id' => $this->user_id,
					'product_id' => $this->product_id,
					'billing_id' => $this->billing_id,
					'price_id' => $this->price_id,
					'tp_search' => $this->tp_search,
					'query' => $this->query,
					)
				);

			/**
			* Salva os dados da consulta
			*/
			$this->Query->create();
			$this->Query->save($data);

			/**
			* Debita a consulta no saldo do cliente caso ele ainda nao tenha ultrapassado o limite do seu pacote
			*/
			if($this->balance){
				$this->Billing->updateAll(
					array(
						"Billing.qt_queries" => "(ifnull(Billing.qt_queries, 0) + 1)",
						"Billing.modified" => "NOW()",
						),
					array('Billing.id' => $this->billing_id)
					);

				/**
				* Recarrega o saldo do cliente subtraindo o saldo atual com o valor da consulta realizada
				*/
				$this->balance = ($this->balance < 1)?0:--$this->balance;
			}

			/**
			* Debita a consulta com o valor de consulta exedente
			*/
			if($this->balance <= 0 && $this->limit_exceeded){
				$this->Billing->updateAll(
					array(
						"Billing.value_exceeded" => "(ifnull(Billing.value_exceeded, 0) + " . $this->AppUtils->num2db($this->price) . ")",
						"Billing.qt_exceeded" => "(ifnull(Billing.qt_exceeded, 0) + 1)",
						"Billing.modified" => "NOW()",
						),
					array('Billing.id' => $this->billing_id)
					);

				$map = $this->Billing->findById($this->billing_id);
				$this->value_exceeded = $map['Billing']['value_exceeded'];
				
				$this->set('value_exceeded', $this->value_exceeded);
			}

			$this->set('balance', $this->balance);
		}
	}
}
