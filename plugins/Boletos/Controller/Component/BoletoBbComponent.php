<?php
class BoletoBbComponent extends Object {

	/**
	 * Armazena as opções padrões do boleto.
	 */
	public $options = array(
		'sacado',
		'endereco1',
		'endereco2',
		'valor_cobrado',
		'pedido'
	);	

	public function initialize(Controller $controller){

	}

	public function startup(Controller $controller){

	}

	public function setup(array $options){
		$this->options = Set::merge($this->options, $options);
	}

	/**
	 * Renderiza o boleto
	 */
	public function render($options = false){
		if($options){
			$this->setup($options);
		}
		$dadosboleto = $this->options;
		require_once App::pluginPath('Boletos') . 'Vendor' . DS . 'boletophp' . DS . 'boleto_bb.php';
	}

	public function beforeRedirect(Controller $controller){

	}

	public function beforeRender(Controller $controller){

	}

	public function shutdown(Controller $controller){

	}
}