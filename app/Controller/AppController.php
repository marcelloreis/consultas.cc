<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	/**
	* Declaracao dos atributos privados da classe
	*/
	private $Model;
	protected $isRedirect = true;
	public $saveType = 'save';
	public $limit;
	public $userLogged;

	/**
	* Carrega os componentes que poderao ser usados em quaisquer controller desta framework
	*/
	public $components = array(
		'Auth', 
		'Acl', 
		'Session', 
		'Main.AppUtils',
		'RequestHandler',
		'Google.AppGoogle',
		'DebugKit.Toolbar' => array('autoRun' => true),
		'Facebook.AppFacebook',
		);
	/**
	* Carrega os helpers que poderao ser usados em quaisquer view desta framework
	*/
	public $helpers = array(
		'Js' => array('Jquery'), 
		'Session',
		'Main.AppGrid', 
		'Main.AppForm', 
		'Main.AppUtils', 
		'Main.AppPaginator', 
		'Main.AppPermissions'
		);
	/**
	* Carrega todos os tipos de relacionamento possiveis
	*/
	private $relationship = array(
		'hasOne',
		'hasMany',
		'belongsTo',
		'hasAndBelongsToMany',
		);




	/**
	* Método beforeFilter
	* Esta função é executada antes de todas ações do controlador. 
	* E no caso da framework, esta sendo usado para checar uma sessão ativa e inspecionar permissões.
	*
	* @override Metodo Controller.beforeFilter
	* @return void
	*/
	public function beforeFilter() {
		//@override
		parent::beforeFilter();

		/**
		 * Inicializa o atributo limit com os limit padrao do sistema setado no bootstrap
		 */
		$this->limit = LIMIT;

		/**
		 * Inicializa o atributo userLogged com os dados do usuario logado
		 */
		$this->userLogged = $this->Session->read('Auth.User');
    	$map = explode(' ', $this->userLogged['name']);
    	$this->userLogged['given_name'] = empty($this->userLogged['given_name'])?$map[0]:$this->userLogged['given_name'];
		

		/**
		 * Inicializa a variavel de ambiente que guardara o tipo da requisicao
		 */
		$requestHandler = 'post';

		/**
		 * Carrega o atributo $this->Model com o objeto do model requisitado
		 */
		$this->Model = $this->{$this->modelClass};

		/**
		* Carrega o Helper AppForm com todos os campos do model e e suas regras de validacao configuradas
		*/
		if(isset($this->Model->useTable) && $this->Model->useTable !== false){
			$this->helpers['Main.AppForm'] = array('fields' => $this->Model->getColumnTypes(), 'validate' => $this->Model->validate, 'modelClass' => $this->modelClass);
		}

	 	/**
	 	 * Regras de negocio executada quando a requisição é feita via Ajax
	 	 */
	 	if ($this->RequestHandler->isAjax()) {
			/**
			 * Carrega o layout ajax sem cabecalhos e rodape
			 */
			$this->layout = 'ajax';

			/**
			* Desabilita o carregamento automatico do layout
			*/
	        $this->autoLayout = false;

			/**
			* Desabilita o carregamento automatico da view
			*/
	        $this->autoRender = false;			

	 		/**
	 		 * Carrega a variavel de ambiente '$requestHandler' com a string 'ajax' indicando que a requisicao é via ajax
	 		 */
	 		$requestHandler = 'ajax';
	 		/**
	 		 * Cancela o redirecionamento
	 		 */
	 		$this->isRedirect = false;
	 		/**
	 		 * Desabilita o cache do browser
	 		 */
	 		$this->disableCache(); 	
	 	}

		/**
		 * Gera a variavel de ambiente '$requestHandler' indicando o tipo de requisicao efetuada
		 */
		$this->set('requestHandler', $requestHandler);

    	/**
    	 * Configurações do componente Auth
    	 */
		$this->Auth->authorize = array(
			'Controller',
			'Actions' => array('actionPath' => 'controllers')
		);
    	$this->Auth->userModel = 'User';
    	$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => false, 'plugin' => false);
    	$this->Auth->autoRedirect = true;
    	$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login', 'admin' => false, 'plugin' => false);
    	$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'dashboard', 'admin' => false, 'plugin' => false);

    	/**
    	* Condicoes para o usuario logar
    	*/
    	$this->Auth->authenticate = array(
    		'Form' => array(
    			'fields' => array(
    				'username' => 'email', 
    				'password' => 'password'
    				),
    			'scope' => array(
    				'User.status' => 1,
    				'User.expire >' => date('Y-m-d')
    				)
    			)
    		);

		/**
		 * Autorizações gerais
		 */
		$this->Auth->allow('login', 'logout', 'authentication', 'run');
		// $this->Auth->allow();
	}

    /**
    * Chamado depois controlador com as regras de negócio, mas antes da visão ser renderizada.
	*
	* @override Metodo Controller.beforeRender
	* @return void
     */
    public function beforeRender(){
		//@override
    	parent::beforeRender();

    	//Carrega a variavel de ambiente userLogged com as informações do usuario logado
    	$this->set('userLogged', $this->userLogged);
		//Carrega o nome do model nas variaveis de ambiente para ser acessado na view
		$this->set('modelClass', $this->modelClass);
		/**
		* Carrega todas as associacoes no model em variavel de ambiente
		*/
		$this->set('belongsTo', $this->Model->belongsTo);
		$this->set('hasAndBelongsToMany', $this->Model->hasAndBelongsToMany);

		/**
		* Verifica se existe uma tabela relacionada ao model
		*/
		if($this->Model->useTable){
			/**
			* Verifica se o model tem acesso a funcao 'getColumnTypes'
			*/
			if($this->Model->useTable){
				/**
				* Concatena os campos virtuais com os campos reais
				*/
				$fields = array_merge(array_keys($this->Model->getColumnTypes()), array_keys($this->Model->virtualFields));
				foreach ($fields as $v) {
					switch ($v) {
						case 'created':
						case 'modified':
						case 'trashed':
						case 'deleted':
							continue;
						break;
						
						default:
							$columns[$v] = ucfirst(__($v));
						break;
					}
				}
				$this->set(compact('columns'));
			}

			/**
			 * Gera a variavel de ambiente '$fieldText' com o nome do campo de texto do model/tabela
			 */
			if(method_exists($this->Model, 'getFieldText')){
				$this->set('fieldText', $this->Model->getFieldText());
			}else if(!empty($this->Model->displayField)){
				$this->set('fieldText', $this->Model->displayField);
			}
		}
    }

	/**
	* Método isAuthorized
	* Regras de autorização configurados para verificar se o usuário esta autorizado para a pagina solicitada. 
	* Cada regra será verificada na sequência, se o usuario logado atender a todas, então o sera retornado TRUE ao final e
	* e será autorizado para a solicitação.
	*
	* @param array $user (Dados do usuario logado)
	* @return boolean
	*/
    public function isAuthorized($user) {
    	/**
    	* Libera o retorno TRUE quando a aplicacao estiver em ambiente de homologacao/testes
    	*/
    	// return true;

    	/**
    	* Carrega o nome do usuario logado
    	*/
    	if(empty($user['given_name'])){
    		$map = explode(' ', $user['name']);
    		$user['given_name'] = $map[0];
    	}

		/**
		 * Verifica se o usuário esta logado, caso nao esteja sera redirecionado a pagina de login com a mensagen sessao expirada
		 */
		if(!$this->Auth->loggedIn()){
			$this->Session->setFlash("{$user['given_name']}, sua sessão expirou. Faça um novo login!", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}

		/**
		 * Verifica se ha permissao em cada action do controlador na sequência, $this->redirect();
		 * se alguma delas retornar true, então o usuário será autorizado para a solicitação.
		 */
		switch ($this->params['action']) {
	    	/**
	    	 * Verifica se o usuário tem permissão para acessar o action INDEX
	    	 */
			case 'index':
		    	$this->redirectOnPermissionDeny('index', "{$user['given_name']}, você não tem permissão para visualizar " . __(Inflector::pluralize($this->modelClass)) . ".", array('controller' => 'users', 'action' => 'dashboard'));
				break;
			
	    	/**
	    	 * Verifica se o usuário tem permissão para acessar o action EDIT
	    	 */
			case 'edit':

				/**
				* Libera o acesso para o usuario alterar os seus proprios dados
				*/
				if(isset($this->params['pass'][0]) && $this->params['pass'][0] == $this->Auth->User('id') && $this->action == 'edit' && $this->name == 'Users'){
					return true;
				}


		    	$this->redirectOnPermissionDeny('edit', "{$user['given_name']}, você não tem permissão para editar " . __(Inflector::pluralize($this->modelClass)) . ".");

		    	/**
		    	 * Verifica se o usuário tem permissão para visualizar o registro.
		    	 * se ele não tiver permissao para visualizar, entao não tem permissao para editar
		    	 */
		    	$this->redirectOnPermissionDeny('view', "{$user['given_name']}, você não tem permissão para visualizar os dados de " . __(Inflector::pluralize($this->modelClass)) . ".");

		    	/**
		    	 * Quando nao houver a chave primaria entao indica que a acao sera de adicionar o registro
		    	 * entao é verificado se o usuário tem permissão para adicionar registros
		    	 * se nao tiver ele sera redirecionado para o indice do controlador atual
		    	 */
		    	if(!count($this->params['pass'])){
		    		$this->redirectOnPermissionDeny('add', "{$user['given_name']}, você não tem permissão para adicionar " . __(Inflector::pluralize($this->modelClass)) . ".");
		    	}
				break;
	    	/**
	    	 * Verifica se o usuário tem permissão para acessar o action ADD
	    	 */
			case 'add':
		    		$this->redirectOnPermissionDeny('add', "{$user['given_name']}, você não tem permissão para adicionar " . __(Inflector::pluralize($this->modelClass)) . ".");
				break;
	    	/**
	    	 * Verifica se o usuário tem permissão para acessar o action VIEW
	    	 */
			case 'view':
		    	$this->redirectOnPermissionDeny('view', "{$user['given_name']}, você não tem permissão para visualizar " . __(Inflector::pluralize($this->modelClass)) . ".");
				break;
	    	/**
	    	 * Verifica se o usuário tem permissão para acessar o action TRASH
	    	 */
			case 'trash':
		    	$this->redirectOnPermissionDeny('trash', "{$user['given_name']}, você não tem permissão para mover " . __(Inflector::pluralize($this->modelClass)) . " para lixeira.");
				break;
	    	/**
	    	 * Verifica se o usuário tem permissão para acessar o action DELETE
	    	 */
			case 'delete':
		    	$this->redirectOnPermissionDeny('delete', "{$user['given_name']}, você não tem permissão para excluír " . __(Inflector::pluralize($this->modelClass)) . ".");
				break;
			
		}


		/**
		* Checa se o parametro trashed esta setados, caso esteja
		* verifica se o usuario tem permissao para a visualizacao de registros da lixeira
		*/
		if(
			(isset($this->params['named'][ACTION_TRASH]) && !empty($this->params['named'][ACTION_TRASH])) ||
			(isset($this->params->query['data'][$this->modelClass][ACTION_TRASH]) && !empty($this->params->query['data'][$this->modelClass][ACTION_TRASH]))
			){
				$this->redirectOnPermissionDeny('trash', "{$user['given_name']}, você não tem permissão para visualizar " . __(Inflector::pluralize($this->modelClass)) . " na lixeira.");
			}

		/**
		* Checa se o parametro deleted esta setados, caso esteja
		* verifica se o usuario logado é o usuario MASTER, pois ele é o unico q tem permissao para visualizar registros DELETADOS
		*/
		if(
			(isset($this->params['named'][ACTION_DELETE]) && !empty($this->params['named'][ACTION_DELETE])) ||
			(isset($this->params->query['data'][$this->modelClass][ACTION_DELETE]) && !empty($this->params->query['data'][$this->modelClass][ACTION_DELETE]))
			){
				if(ADMIN_USER != $this->Auth->User('id')){
					$this->Session->setFlash('você não tem permissão para visualizar registros excluídos.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
					$this->redirect($this->referer());
				}
			}


    	return true;
    }

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade/tabela do controlador
	*
	* @param Array $params
	* @return void
	*/
    public function index($params=array()) {
		/**
		* Controle de encapsulamento.
		* Independente do action, sempre que a funcao "index" for invocada
		* sera carregado a view "app/View/[Actions]/index.ctp"
		* a menos que a funcao "$this->render('action', 'layout', 'file')" seja invocada
		* no Controller
		*/
		$this->view = 'index';

		/**
		* Verifica se o index foi chamado apartir de uma grid de adicao de relacionamento de dados
		* Caso seja, sera excluído da consulta todos os registros que ja estao relacionado ao
		* id do model passado por parametro
		*/
		if(isset($this->params['named']['habtmModel']) && isset($this->params['named']['habtmId'])){
			//Carrega todos os atributos do relacionamento
			$habtm = $this->Model->hasAndBelongsToMany[$this->params['named']['habtmModel']];
			//Carrega todos os registros que ja estao relacionado entre os dois models
			$habtmList = $this->Model->$habtm['with']->find('list', array('fields' => array('id', $habtm['foreignKey']), 'conditions' => array($habtm['associationForeignKey'] => $this->params['named']['habtmId'])));
			//Retira da consulta os registros relacionados encontrados
			$params['conditions']["{$this->modelClass}.{$this->Model->primaryKey} NOT"] = $habtmList;
		}

		/**
		* Verifica se existe uma tabela relacionada ao model
		*/
		if($this->Model->useTable){
	    	/**
			 * Se o campo "q" for igual a 1, simula o envio do form por get
			 * redirecionando para http://[domain]/[controller]/[action]/seach:value1/namedN:valueN
	    	 */
	    	$this->__post2get();

	    	/**
	    	* Carrega a variavel $search verificando de onde esta vindo a quary
	    	*/
			if(!empty($this->params['named']['search'])){
				$search = $this->params['named']['search'];
			}else if(isset($this->params->query['data'][$this->modelClass]['search']) && !empty($this->params->query['data'][$this->modelClass]['search'])){
				$search = $this->params->query['data'][$this->modelClass]['search'];
			}

			/**
			* Caso a variavel padrao de busca 'search' esteja setada, monta as condicoes de busca
			*
			* PARA QUE A BUSCA DINAMICA FUNCIONE, É NECESSARIO QUE TODAS AS ASSOCIACOES ESTEJAM DEVIDAMENTE
			* DECLARADAS EM Model/NomeDoModel.php
			*/
			if(!empty($search)){
				//Guarda as condicoes montadas com os campos de texto padrao dos models
				$searchMap = array();
				//Monta as condicoes de busca do campo de texto principal dos models associados
				foreach ($this->Model->belongsTo as $k => $v) {
					if(method_exists($this->Model->$k, 'getFieldText')){
						$searchMap[]["{$k}.{$this->Model->$k->getFieldText()} LIKE"] = "%{$search}%";
					}else if(!empty($this->Model->$k->displayField)){
						$searchMap[]["{$k}.{$this->Model->$k->displayField} LIKE"] = "%{$search}%";
					}
				}

				//Monta as condicoes de busca do campo de texto principal do modelo/tabela
				if(method_exists($this->Model, 'getFieldText')){
					$searchMap[]["{$this->modelClass}.{$this->Model->getFieldText()} LIKE"] = "%{$search}%";
				}else if(!empty($this->Model->displayField)){
					$searchMap[]["{$this->modelClass}.{$this->Model->displayField} LIKE"] = "%{$search}%";
				}

				//Verifica se existem mais de uma condicao montada, caso exista, insere a clausula OR
				if(count($searchMap) > 1){
					$searchMap = array('OR' => $searchMap);
				}

				//Carrega o parametro 'conditions' com as condicoes montadas dinamicamente
				if(isset($params['conditions']) && is_array($params['conditions'])){
					if(isset($params['combine']) && $params['combine'] === false){
						unset($params['combine']);
					}else{
						array_push($params['conditions'], $searchMap);
					}
				}else{
					$params['conditions'] = $searchMap;
				}
			}

	    	/**
	    	* Verifica se foi passado algum valor na variavel trashed
	    	*/
			if(isset($this->params['named'][ACTION_TRASH]) && !empty($this->params['named'][ACTION_TRASH])){
				$params['conditions']["{$this->modelClass}." . ACTION_TRASH] = $this->params['named'][ACTION_TRASH];
			}else if(isset($this->params->query['data'][$this->modelClass][ACTION_TRASH]) && !empty($this->params->query['data'][$this->modelClass][ACTION_TRASH])){
				$params['conditions']["{$this->modelClass}." . ACTION_TRASH] = $this->params->query['data'][$this->modelClass][ACTION_TRASH];
			}			

	    	/**
	    	* Verifica se foi passado algum valor na variavel deleted
	    	*/
			if(isset($this->params['named'][ACTION_DELETE]) && !empty($this->params['named'][ACTION_DELETE])){
				$params['conditions']["{$this->modelClass}." . ACTION_DELETE] = $this->params['named'][ACTION_DELETE];
			}else if(isset($this->params->query['data'][$this->modelClass][ACTION_DELETE]) && !empty($this->params->query['data'][$this->modelClass][ACTION_DELETE])){
				$params['conditions']["{$this->modelClass}." . ACTION_DELETE] = $this->params->query['data'][$this->modelClass][ACTION_DELETE];
			}			

			//Configurações padrao da busca
			$defaults = array(
							'limit' => $this->limit
				);

			$params = array_merge($defaults, $params);
	    	$this->paginate = array($this->modelClass => $params);

	    	//Carrega os dados de acordo com os parametros montados ate aqui
	    	$map = $this->paginate();
	    	$this->set(Inflector::variable($this->modelClass), $map);

			/**
			* Conta quantos registros NAO foram enviados para a lixeiro e nem deletados
			*/
			// unset($params['conditions']["{$this->modelClass}." . ACTION_TRASH]);
			// unset($params['conditions']["{$this->modelClass}." . ACTION_DELETE]);
			// $inbox = $this->Model->find('count', $params);
			// $this->set(compact('inbox'));
			
			/**
			* Conta quantos registros enviados para lixeira existem com os mesmos parametros de busca
			*/
			// $params['conditions']["{$this->modelClass}." . ACTION_TRASH] = true;
			// $trashed = $this->Model->find('count', $params);
			// $this->set(compact('trashed'));
			
			/**
			* Conta quantos registros deletados existem com os mesmos parametros de busca
			*/
			// $params['conditions']["{$this->modelClass}." . ACTION_DELETE] = true;
			// $deleted = $this->Model->find('count', $params);
			// $this->set(compact('deleted'));

	    	return $map;
		}
    }

	/**
	* Método add
	* Encapsulamento da função EDIT para controle de acesso via ACL
	*
	* @return void
	*/
	public function add(){
		$this->edit();
	}

	/**
	* Método view
	* Encapsulamento da função EDIT para controle de acesso via ACL
	*
	* @param String $id
	* @return void
	*/
	public function view($id=null){
		$this->Model->id = $id;
		if (!$this->Model->exists()) {
			$this->Session->setFlash(sprintf('Não foi possível visualizar o %s, ou ele não existe no banco de dados.', __($this->modelClass)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
			$this->redirect(array('action' => 'index'));
		}	

		$this->edit($id);
	}

	/**
	* Método edit
	*
	* Este método contem regras de negocios para adicionar e editar registros na base de dados
	*
	* @param String $id
	* @return void
	*/
	public function edit($id=null){
		/**
		* Controle de encapsulamento.
		* Independente do action, sempre q a funcao "edit" for invocada
		* sera carregado a view "app/View/Actions/edit.ctp"
		*/
		$this->view = 'edit';

		/**
		 * Carrega o campo ID do model com o ID passado pelo parametro 
		 * para que o registro dessa chave primaria seja atualizado
		 * e
		 * Verifica se o id passado por parametro existe, caso não exista redireciona para o index.
		 */
		if($id){
			$this->request->data[$this->modelClass]['id'] = $id;
			$this->Model->id = $id;

			if (!$this->Model->exists()) {
				$this->Session->setFlash(sprintf('Não foi possível visualizar o %s [%s], ou ele não existe no banco de dados.', __($this->modelClass), $this->Model->id), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
				$this->redirect(array('action' => 'index'));
			}
		}

		/**
		 * Verifica se o formulário foi submetido por post
		 */
		if ($this->request->is('post') || $this->request->is('put')) {
			/**
			 * Reinicializa o estado do model para salvar novos dados.
			 * sem este metodo todas as funções do AppModel deixam de funcionar pois 
			 * o atributo $this->data só funciona quando este metodo é setado
			 */
			$this->Model->create();

			/**
			 * A criação ou atualização é controlada pelo campo id do model. 
			 * Se o $this->Model->id já estiver definido, o registro com esta chave primária será atualizado. 
			 * Caso contrário, um novo registro será criado.
			 */
			if($this->Model->{$this->saveType}($this->request->data)){
				if($this->isRedirect){
					$this->Session->setFlash(FLASH_SAVE_SUCCESS, FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
					$this->redirect(array('action' => 'edit', $this->Model->id));
				}
			}else{
				/**
				 * Carrega os erros encontrados ao tentar salvar o formulário
				 */
				$this->Model->set($this->request->data);
				$errors = $this->Model->invalidFields();
				$msgs = array();
				foreach ($errors as $k => $v) {
					if(isset($v[0])){
						$msgs[$k] = $v[0];
					}
				}
				$this->Session->setFlash(FLASH_SAVE_ERROR, FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR, 'multiple' => $msgs), FLASH_SESSION_FORM);
			}
		} 

		/**
		 * Pupula os dados do registro apartir da chave primário encontrada
		 */
		if(!empty($this->Model->id)){
			$this->data = $this->Model->read();
		}

		/**
		* Carrega as listas para selecao dos models relacionados
		*/
		foreach ($this->relationship as $v) {
			foreach ($this->Model->{$v} as $v2) {
				$this->set(Inflector::pluralize(Inflector::variable($v2['className'])), $this->Model->{$v2['className']}->find('list', array('fields' => $v2['fields'], 'conditions' => $v2['conditions'])));
			}
		}
	}

	/**
	* Método __remove
	*
	* Este método altera para true o campo trashed|deleted do(s) registro passado por parametro.
	*
	* @param String $id
	* @param String $action
	* @return boolean $removed
	*/
	protected function __remove($id=null, $action, $cascade=true, $value=true) {
		/**
		* Carrega o model com os dados vindo do post
		*/
		if(isset($this->request->data) && count($this->request->data)){
			$data = $this->request->data;
		}else if(isset($this->params->query['data']) && count($this->params->query['data'])){
			$data = $this->params->query['data'];
		}
		if(isset($data)){
			$this->Model->set($data);
		}

		/**
		* Carrega o id do model caso o ID do registro venho por GET
		*/
		if(!empty($id) && is_numeric($id)){
			/**
			* Carrega o Model com o ID q sera movido para a lixeira
			*/
			$this->Model->id = $id;

			/**
			* Verifica se o ID passado existe na base de dados
			*/
			if ($value && !$this->Model->exists()) {
				$this->Session->setFlash(sprintf('O %s não existe na base de dados.', __($this->modelClass)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
				$this->redirect($this->referer());
			}
		}

		/**
		* Remove os registros contidos no model
		*/
		$removed = $this->Model->remove($action, $cascade, $value);
		return $removed;
	}	

	/**
	* Método trash
	*
	* Esta funcao é um encapsulamento da funcao __remove, porem a funcao __remove sera chamada 
	* passando como parametro a acao ACTION_TRASH que força a atualizacao do campo trashed do registro
	*
	* @param String $id
	* @return void
	*/
	public function trash($id=null) {
		/**
		* Move os registros para a lixeira
		*/
		if ($this->__remove($id, ACTION_TRASH)) {
			$this->Session->setFlash(sprintf('%s movidos para lixeira.', __($this->name)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}else{
			$this->Session->setFlash(sprintf('Não foi possível mover o %s para a lixeira.', __($this->name)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}

		$this->redirect($this->referer());
	}	

	/**
	* Método delete
	*
	* Esta funcao é um encapsulamento da funcao __remove, porem a funcao __remove sera chamada 
	* passando como parametro a acao ACTION_DELETE que força a atualizacao do campo deleted do registro
	*
	* @param String $id
	* @return void
	*/
	public function delete($id=null) {
		/**
		* Move os registros permanentemente
		*/
		if ($this->__remove($id, ACTION_DELETE)) {
			$this->Session->setFlash(sprintf('%s excluído.', __($this->name)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}else{
			$this->Session->setFlash(sprintf('Não foi possível excluír o %s.', __($this->name)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}
		$this->redirect($this->referer());
	}	

	/**
	* Método restore
	*
	* Esta funcao é um encapsulamento da funcao __remove, porem a funcao __remove sera chamada 
	* passando os parametros necessarios para que o registro seja removido da lixeira
	*
	* @param String $id
	* @return void
	*/
	public function restore($id=null) {
		/**
		* Restaura os registros deletados
		*/
		if ($this->__remove($id, ACTION_DELETE, true, false)) {
			$this->Session->setFlash(sprintf('%s restaurado.', __($this->name)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}else{
			$this->Session->setFlash(sprintf('Não foi possível restaurar o %s.', __($this->name)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}

		/**
		* Restaura os registros movidos para a lixeira
		*/
		if ($this->__remove($id, ACTION_TRASH, true, false)) {
			$this->Session->setFlash(sprintf('%s restaurado da lixeira.', __($this->name)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}else{
			$this->Session->setFlash(sprintf('Não foi possível restaurar o %s da lixeira.', __($this->name)), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}


		$this->redirect($this->referer());
	}	


	/**
	 * Verifica que se usuário logado tem acesso ao controlador/ação passada por parametro
	 */
	private function hasPermission($actionPath){
		return $this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Session->read('Auth.User.id')), $actionPath);
	}

	/**
	 * Redireciona para index do controller quando o usuario não tiver permissao para acessar a funcao/action passado por paramtro
	 */
	private function redirectOnPermissionDeny($action, $msg, $redirect=null){
		if(!$this->hasPermission($this->name . "/{$action}")){
			$redirect = $redirect?$redirect:array('action' => 'index');
			$this->Session->setFlash($msg, FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
			$this->redirect($redirect);
		}
	}

	/**
	 * Se o campo "q" for igual a 1, simula o envio do form por get
	 * redirecionando para http://domain/controller/action/seach:value1/namedN:valueN
	 */
	protected function __post2get(){
    	if(isset($this->request->data['q']) && $this->request->data['q'] == 'post'){
			unset($this->request->data['q']);
			$redirect = array(
				'controller' => $this->params['controller'],
				'action' => $this->params['action']
				);

			foreach ($this->data[$this->modelClass] as $k => $v) {
				$redirect[$k] = $v;
			}

	        foreach ($this->params['named'] as $k => $v) {
	        	if(!preg_match('/(page|search)/si', $k)){
	            	$redirect[$k] = $v;
	        	}
	        }

	        foreach($this->params['pass'] as $k => $v){
	            array_push($redirect, $v);
	        }

			$this->redirect($redirect);    		
    	}		
	}	

    /**
     * Carrega todas as permissoes do usuario logado em sessions
     */
    public function __loadPermissionsOnSessions(){
        //Limpa todas as sessions das permissoes
    	$this->Session->delete('Auth.Permissions');

        //Carrega os dados do usuario logado
    	$user = $this->Auth->user();

        //Verifica se o usuario se logou corretamente
    	if (isset($user)) {
            //Carrega o ID do ARO/Grupo a qual o usuario pertence
    		$aro = $this->Acl->Aro->find('first', array(
    			'conditions' => array(
    				'Aro.model' => 'Group',
    				'Aro.foreign_key' => $user['group_id'],
    				),
    			));

            //Percorre por todos os ACOs(funcoes) existentes
    		$acos = $this->Acl->Aco->children();
    		foreach($acos as $aco){
    			if(isset($aro['Aro']['id']) && isset($aco['Aco']['id'])){
	    			$permission = $this->Acl->Aro->Permission->find('first', array(
	    				'conditions' => array(
	    					'Permission.aro_id' => $aro['Aro']['id'],
	    					'Permission.aco_id' => $aco['Aco']['id'],
	    					),
	    				));
    			}

                //Verifica se o usuario tem permissao para o ACO(funcao) atual do foreach
    			if(isset($permission['Permission']['id'])){
    				if ($permission['Permission']['_create'] == 1 || $permission['Permission']['_read'] == 1 || $permission['Permission']['_update'] == 1 || $permission['Permission']['_delete'] == 1) {

                    //Carrega a funcao do controller que o usuario tem permissao na sessao
    					if(!empty($permission['Aco']['parent_id'])){
    						$parentAco = $this->Acl->Aco->find('first', array(
    							'conditions' => array(
    								'id' => $permission['Aco']['parent_id']
    								)   
    							));
    						$this->Session->write("Auth.Permissions.{$parentAco['Aco']['alias']}.{$permission['Aco']['alias']}", true);
    					}
    				}
    			}
    		}
    	}
    }	
}