<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Invoices/
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
 * @link http://.framework.nasza.com.br/2.0/controller/Invoices.html
 */
class InvoicesController extends AppController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'Invoices';

	/**
	* Carrega os componentes que poderao ser usados em quaisquer controller desta framework
	*/
	public $components = array('Boletos.BoletoBb');

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
            'client_id' => $this->Invoice->Client->find('list', array('id', 'name')),
            'is_paid' => array(true => 'Sim', false => 'Não'),
            'is_signature' => array(true => 'Sim', false => 'Não'),
            'is_separete' => array(true => 'Sim', false => 'Não'),
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
		/**
		 * Verifica se o formulário foi submetido por post
		 */
		if ($this->request->is('post') || $this->request->is('put')) {
			/**
			* Caso nao seja informado o pacote, considera q o boleto é avulso
			*/
			if(empty($this->request->data['Invoice']['package_id'])){
				$this->request->data['Invoice']['is_separete'] = true;
			}else{
				$this->request->data['Invoice']['is_separete'] = false;
			}
		}

		//@override
		parent::edit($id);	

		/**
		* Gera o token do boleto
		*/
		if(!empty($this->data['Invoice']['id']) && empty($this->data['Invoice']['token'])){
			$package_id = !empty($this->data['Invoice']['package_id'])?$this->data['Invoice']['package_id']:'0';
			$token = preg_replace('/[^0-9]/si', '', "{$id}{$this->data['Invoice']['client_id']}{$package_id}{$this->data['Invoice']['maturity_db']}" . substr(uniqid(), -4));
			$this->Invoice->updateAll(array('token' => $token), array('Invoice.id' => $id));
		}
	}

	/**
	* Método send
	* Este método reenvia para o cliente o boleto passado pelo parametro
	*
	* @param string $id
	* @return void
	*/
	public function send($id){
		$this->Invoice->id = $id;

		/**
		* Verifica se o boleto existe
		*/
		if(!$this->Invoice->exists()){
			$this->Session->setFlash('Não foi possível encontrar o boleto, ou ele não existe no banco de dados.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
			$this->redirect($this->referer());
		}

		/**
		* Carrega os dados do boleto
		*/
		$invoice = $this->Invoice->read();

		/**
		* Carrega os dados do cliente
		*/
		$this->Invoice->Client->recursive = -1;
		$client = $this->Invoice->Client->findById($invoice['Invoice']['client_id']);

		/**
		* Monta o link para download do boleto
		*/
		$link = PROJECT_LINK . "invoices/" . BANK_ACTIVE . "/{$invoice['Invoice']['token']}";

		/**
		* Dispara um email para o usuario responsavel do boleto
		*/
		$obj_email = new CakeEmail('apps');
		$obj_email->template('invoice-default');
		$obj_email->emailFormat('html');
		$obj_email->viewVars(array('link' => $link, 'client' => $client));

		$obj_email->sender(array(EMAIL_NO_REPLAY => TITLE_APP));
		$obj_email->from(array(EMAIL_NO_REPLAY => TITLE_APP));
		// $obj_email->to($client['Client']['email']);
		$obj_email->to('marcello@marcelloreis.com');
		$obj_email->subject('Instruções para pagamento do boleto.');
		if($obj_email->send()){
			$this->Session->setFlash("Um email com o boleto da assinatura foi enviados para o endereço: <strong>{$client['Client']['email']}</strong>", FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}else{
			$this->Session->setFlash("Não foi possível enviar o boleto da assinatura para o endereço: <strong>{$client['Client']['email']}</strong>", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
		}		

		$this->redirect($this->referer());
	}

	/**
	* Método build
	* Este método gera boletos do banco do brasil a partir do token informado pelo parametro
	*
	* @param string $token
	* @return void
	*/
	public function bb($token, $count=true){
		$this->autoRender = false;

		/**
		* Carrega o boleto a partir do token passado pelo parametro
		*/
		$invoice = $this->Invoice->findByToken($token);

		/**
		* Verifica se o boleto existe
		*/
		if(empty($invoice['Invoice'])){
			$this->Session->setFlash("Desculpe, este link esta incorreto ou não existe mais.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
			throw new NotFoundException();
		}

		/**
		* Verifica se o vencimento do boleto expirou
		*/
		if($invoice['Invoice']['days_expired'] > 0){
			$this->Session->setFlash("Desculpe, este boleto já expirou, entre em contato com o nosso pessoal de apoio.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
			throw new NotFoundException();
		}


		/**
		* Contabiliza a quantidade de vezes q o boleto foi baixado
		*/
		if($count){
			/**
			* Carrega a quantidade de vezes o boleto foi baixado
			*/
			$download_qt = empty($invoice['Invoice']['download_qt'])?1:$invoice['Invoice']['download_qt']+1;

			/**
			* Carrega os valores q serao atualizados
			*/
			$values = array(
				'download_date' => 'NOW()',
				'download_qt' => $download_qt,
				);
			
			/**
			* Carrega as condicoes da atualozacao
			*/
			$condition = array('Invoice.id' => $invoice['Invoice']['id']);
			
			/**
			* Atualiza os dados da campanha
			*/
			$this->Invoice->updateAll($values, $condition);
		}

		/**
		* Carrega a cidade
		*/
		$city = $this->Invoice->Client->City->findById($invoice['Client']['city_id']);
		$city = $city['City']['name'];

		/**
		* Carrega o estado
		*/
		$state = $this->Invoice->Client->State->findById($invoice['Client']['state_id']);
		$state = $state['State']['uf'];

		/**
		* Carrega o valor do pacote
		*/
		$value_package = $this->AppUtils->num2db($invoice['Invoice']['value']);

		/**
		* Carrega o valor excedido
		*/
		$value_exceeded = $this->AppUtils->num2db($invoice['Invoice']['value_exceeded']);

		/**
		* Carrega o valor total do boleto
		*/
		$valor_cobrado = ($value_package + $value_exceeded);

		$dados = array(
			'sacado' => $invoice['Client']['corporate_name'],
			'endereco1' => "{$invoice['Client']['street']}, {$invoice['Client']['number']} - {$invoice['Client']['neighborhood']}",
			'endereco2' => "{$city}/{$state}",
			'valor_cobrado' => $valor_cobrado,
			'pedido' => $token // Usado para gerar o número do documento e o nosso número.
		);
		$this->BoletoBb->render($dados);		
	}
}