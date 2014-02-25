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
            'package_id' => $this->Invoice->Package->find('list', array('id', 'name')),
            'client_id' => $this->Invoice->Client->find('list', array('id', 'name')),
            'is_paid' => array(true => 'Sim', false => 'Não'),
            'is_signature' => array(true => 'Sim', false => 'Não'),
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
	}

	/**
	* Método resend
	* Este método reenvia para o cliente o boleto passado pelo parametro
	*
	* @param string $id
	* @return void
	*/
	public function resend($id){
		$this->Invoice->id = $id;

		/**
		* Verifica se o boleto existe
		*/
		if(!$this->Invoice->exists()){
			$this->Session->setFlash('Não foi possível encontrar o boleto, ou ele não existe no banco de dados.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
			$this->redirect($this->referer());
		}
	}

	/**
	* Método build
	* Este método gera boletos do banco do brasil a partir do token informado pelo parametro
	*
	* @param string $token
	* @return void
	*/
	public function bb($token){
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
		* Calcula quantas vezes o boleto foi baixado
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
		* Formata o valor cobrado
		*/
		$valor_cobrado = substr(preg_replace('/[^0-9]/si', '', $invoice['Package']['price']), 0, -2);

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