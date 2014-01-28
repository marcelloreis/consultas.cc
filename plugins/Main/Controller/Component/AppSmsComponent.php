<?php
/**
 * Application level Component
 *
 * Este componente é responsavel por todas as funcoes de importacao do banco de dados
 *
 * @link          https://developers.google.com/google-apps/calendar/instantiate
 * @package       app.Controller.Component
 */
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');

/**
 * Application Component
 *
 */
class AppSmsComponent extends Component {

	/**
	* Atributos da classe
	*/
	private $httpSocket;
	public $log;
	public $status;

	public function __construct(){
		$this->httpSocket = new HttpSocket();
	}

	/**
	* Método send
	* Este método é responsavel pelo envio de SMS
	*
	* @param string $params (Paramsetros do envio do SMS)
	* @return void
	*/
	public function send($params){
		/**
		* Inicializa o atributo $this->status com true, caso aconteca algo que impeça o envio do SMS a variavel recebera false
		*/
		$this->status = true;

		/**
		* Verifica se todos os parametros foram passados corretamente
		*/
		if(empty($params['subject'])){
			$this->status = false;
			$this->log = 'O título deve ser preenchido.';
		}

		if(empty($params['msg'])){
			$this->status = false;
			$this->log = 'A mensagem deve ser preenchida.';
		}

		if(empty($params['numbers'])){
			$this->status = false;
			$this->log = 'O remetente deve ser preenchido.';
		}

		/**
		* Carrega o usuario e senha para acesso ao REST
		*/
		$params['lgn'] = SMS_LOGIN;
		$params['pwd'] = SMS_PASSWORD;

		/**
		* Envia o SMS caso todos os campos estejam preenchidos corretamente
		*/
		if($this->status){
			$response = $this->httpSocket->get(SMS_REST, $params);

			/**
			* Verifica se o servidor esta online
			*/
			if($response->code != '200'){
				$this->log = 'O servidor de SMS esta fora do ar.';
				$this->status = false;
			}

			/**
			* Verifica se o envio foi bem sucedido
			*/
			if(preg_match('/^0:(.*)/si', $response->body, $vet)){
				$this->log = ucwords(strtolower($vet[1]));
				$this->status = false;
			}

			/**
			* Se a operacao for bem sucedida, grava o log de sucesso
			*/
			if($this->status){
				$this->log = 'SMS Enviado com sucesso.';
			}
		}
	}
}