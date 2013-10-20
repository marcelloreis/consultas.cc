<?php
App::uses('ProjectController', 'Controller');
/**
 * Entities Controller
 *
 * O controller 'Entities' é responsável por gerenciar 
 * toda a lógica do model 'Entity' e renderizar o seu retorno na interface da aplicação.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Controller
 *
 * @property Entity $Entity
 */
class EntitiesController extends ProjectController {

	/**
	* Método index
	* Este método contem regras de negocios que permitem buscar na base de dados por quais quer parametro
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($params=array()){
    	/**
		 * Se o campo "q" for igual a 1, simula o envio do form por get
		 * redirecionando para http://[domain]/[controller]/[action]/seach:value1/namedN:valueN
    	 */
    	$this->__post2get();

    	/**
    	* Inicializa a variavel $entity com false
    	*/
    	$entity = false;

    	/**
    	* Verifica se o parametro 'doc' foi setado
    	*/
    	if($this->AppUtils->hasVal($this->params['named']['doc'])){
    		$entity = $this->byDoc($this->params['named']['doc']);
    		$landline = $this->byDoc($this->params['named']['doc']);
    	}

    	$this->set(compact('entity'));
	}	

	/**
	* Método byDoc
	* Este método retorna todos os dados da entidade a partir do documento passados por parametro
	*
	* @return void
	*/
	private function byDoc($doc){
		/**
		* Remove tudo que nao for numeros do documento
		*/
		$doc = preg_replace('/[^0-9]/', '', $doc);

		/**
		* Busca a entidade a partir do documento passado pelo parametro sem a verificacao dos trasheds e deleteds 'default_first'
		*/
		$map = $this->Entity->find('default_first', array('conditions' => array('Entity.doc' => $doc)));

		/**
		* Retorna FALSE caso nao encontre nenhum registro na base de dados
		*/
		$map = count($map)?$map:false;

		return $map;
	}



	/**
	* Método edit
	* Este método contem regras de negocios que permitem adicionar e editar registros na base de dados
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
