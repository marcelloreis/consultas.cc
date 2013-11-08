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
	* Declaracao dos componentes
	*/
	public $components = array('AppImport');

    /**
    * Chamado depois controlador com as regras de negócio, mas antes da visão ser renderizada.
	*
	* @override Metodo AppController.beforeRender
	* @return void
     */
    public function beforeRender(){
		//@override
    	parent::beforeRender();

		/**
		* Carrega todos os estados cadastrados
		*/
		$states = $this->Entity->Address->State->find('list');

		/**
		* Carrega todos os estados cadastrados
		*/
		$uf = $this->Entity->Address->State->find('list', array('fields' => array('State.id', 'State.uf')));

		/**
		* Carrega todos as cidades cadastrados
		*/
		$cities = array();

    	/**
    	* Carrega as variaveis de ambiente
    	*/
    	$this->set(compact('states', 'uf', 'cities'));
	}

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($params=array()){
		$this->limit = 10;

		$params['contain'] = "Address";

		/**
		* Carrega os parametros de busca comun a todos os produtos
		*/
		$joins = array(
			array('table' => 'associations',
		        'alias' => 'Association',
		        'type' => 'INNER',
		        'conditions' => array(
		            'Association.entity_id = Entity.id',
		        )
	    	),
			array('table' => 'addresses',
		        'alias' => 'Address',
		        'type' => 'INNER',
		        'conditions' => array(
		            'Association.address_id = Address.id',
		        )
		    )
		);
		if(isset($this->params['named']['state_id']) && !empty($this->params['named']['state_id'])){
			unset($params['contain']);
			$params['joins'] = $joins;
			$params['conditions']['Address.state_id'] = $this->params['named']['state_id'];
			$params['group'] = array('Entity.id');
			$params['fields'] = array('Entity.*', 'Address.*');
		}
		if(isset($this->params['named']['city_id']) && !empty($this->params['named']['city_id'])){
			unset($params['contain']);
			$params['joins'] = $joins;
			$params['conditions']['Address.city_id'] = $this->params['named']['city_id'];
			$params['group'] = array('Entity.id');
			$params['fields'] = array('Entity.*', 'Address.*');
		}




		$this->view = 'index';

		/**
		* Carrega todas as entidades econtradas
		*/
		$entity = $this->Entity->find('all', $params);

		/**
		* Conta quantas entidades foram encontradas
		*/
		$qt_entities = count($entity);

		/**
		* Redireciona para pagina de exibicao de dados da entidade caso só retorne um entidade
		*/
		if(count($params) && $qt_entities == 1){
			$this->redirect(array('action' => 'people', $entity[0]['Entity']['id']));
		}

		/**
		* Monta as URLs contidas no mapa
		*/
		$url = $this->params['named'];
		unset($url['page']);
		$map = array();
		$group_by = array();
		foreach ($entity as $k => $v) {
				$v['Address'] = isset($v['Address'][0])?$v['Address']:array($v['Address']);
				foreach ($v['Address'] as $k2 => $v2) {
					if(!in_array($v['Entity']['id'], $group_by)){
						$group_by[] = $v['Entity']['id'];
						$url['state_id'] = $v2['state_id'];
						$url['city_id'] = $v2['city_id'];
						$map[$v2['state_id']][$v2['city_id']]['city'] = $v2['city'];
						$map[$v2['state_id']][$v2['city_id']]['url'] = $url;
						if(!isset($map[$v2['state_id']][$v2['city_id']]['qt'])){
							$map[$v2['state_id']][$v2['city_id']]['qt'] = 1;
						}else{
							$map[$v2['state_id']][$v2['city_id']]['qt']++;
						}
					}
				}
		}	

		/**
		* Organiza as urls montadas
		*/
		foreach ($map as $k => $v) {
			foreach ($v as $k2 => $v2) {
				$map_found[$k]["{$v2['qt']}-{$k2}"] = $v2;
			}
			krsort($map_found[$k], SORT_NUMERIC);
			$map_found[$k] = array_slice($map_found[$k], 0, 10);
		}

		$this->set(compact('map_found', 'entity'));
	}		

	/**
	* Método people
	* Este método contem regras de negocios que permitem buscar na base de dados por quais quer parametro
	*
	* @param string $id (ID da entidade)
	* @return void
	*/
	public function people($id=null){
    	/**
		 * Se o campo "q" for igual a 1, simula o envio do form por get
		 * redirecionando para http://[domain]/[controller]/[action]/seach:value1/namedN:valueN
    	 */
    	$this->__post2get();

    	/**
    	* Carrega todas parametros de busca passado pelo formulario
    	*/
    	$params = $this->params['named'];

    	/**
    	* Inicializa a variavel $people que armazena os dados da entidade 
    	* encontrada a partir dos parametros passados pelo formulario
    	*/
    	$people = false;

    	/**
    	* Carrega os dados da entidade a partir do id informado
    	*/
    	if($id){
	    	$people = $this->Entity->findById($id);
	    	
    	/**
    	* Carrega os dados da entidade de acordo com os parametros passados
    	*/
    	}else if(isset($params['doc'])){
			$people = $this->Entity->findByDoc($params['doc']);
			$msg = 'No records found with the typed document';
		}else if(isset($params['name'])){
			$people = $this->getByName($params);
			$msg = 'No records found with the typed name';
		}else if(isset($params['landline'])){
			$people = $this->getByLandline($params);
			$msg = 'No records found with the typed landline';
		}else if(isset($params['zipcode']) || isset($params['street'])){
			$people = $this->getByAddress($params);
			$msg = 'No records found with the typed address';
		}

    	/**
    	* Carrega todos os demais dados a partir da entidade encontrada
    	*/
    	if($people){
			/**
			* Carrega todas as chaves associativas da entidade
			*/
	 		$associations = $this->loadAssociations($people);

	    	/**
	    	* Carrega os dados pertinentes ao produto 'Telefone Fixo'
	    	*/
	    	$landline = $this->landline($associations);

	    	/**
	    	* Carrega os dados pertinentes ao produto 'Endereços'
	    	*/
	    	$address = $this->address($associations);
	
	    	/**
	    	* Carrega os dados pertinentes ao produto 'Localizador'
	    	*/
	    	$locator = $address;
	
	    	/**
	    	* Carrega os dados pertinentes ao produto 'Vizinhos'
	    	*/
	    	$neighborhood = $this->neighborhood($people, $address);
	    	/**
	    	* Carrega os dados pertinentes ao produto 'Familia'
	    	*/
	    	$family = $this->family($people, $address);
	
	    	$this->set(compact('people', 'landline', 'address', 'locator', 'family', 'neighborhood'));
    	}else{
    		if(isset($msg) && $this->view == $this->action){
				$this->Session->setFlash(__($msg) , FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
    		}
    	}
	}	

	/**
	* Método getByName
	* Este método as regras de negocio responsaveis por encontrar a entidade a partir do nome
	*
	* @param array $query (parametros de busca)
	* @return array $people (dados da entidade encontrada)
	*/
	private function getByName($query){

		/**
		* Inicializa a variavel que contera os dados encontrados da entidade
		*/
		$people = false;

		/**
		* Gera o hash do nome da entidade e
		*/
		$hash = $this->AppImport->getHash($this->AppImport->clearName($query['name']));

		/**
		* Verifica se o nome pesquisado é unico, ou seja, se foi passado somente o nome sem o sobre nome
		*/
		if(!$hash['h2']){
			/**
			* Caso nao encontre nenhuma entidade com o mesmo nome pesquisado
			* procura por outras entidades com o mesmo sobre nome
			*/
			$params = array(
				'conditions' => array(
				'OR' => array(
					'Entity.h1' => $hash['h1'],
					'Entity.h2' => $hash['h1'],
					'Entity.h3' => $hash['h1'],
					'Entity.h4' => $hash['h1'],
					'Entity.h5' => $hash['h1'],
					)
				)
			);					
			$this->index($params);
		}else{

			/**
			* Procura por outras entidades com o nome identico ao passado por parametro
			*/
			$params = array(
				'conditions' => array(
					'Entity.h_all' => $hash['h_all'],
					),
				);					
			if($this->Entity->find('count', $params)){
				$this->index($params);
			}else{

				/**
				* Caso nao encontre nenhuma entidade com o mesmo nome pesquisado
				* procura por outras entidades com o mesmo sobre nome
				*/
				$params = array(
					'conditions' => array(
						'Entity.h2' => $hash['h2'],
						'Entity.h3' => $hash['h3'],
						'Entity.h4' => $hash['h4'],
						'Entity.h5' => $hash['h5'],
						),
					);					
				if($this->Entity->find('count', $params)){
					$this->index($params);
				}else{

					/**
					* Caso nao encontre nenhuma entidade com o mesmo sobre nome
					* procura por outras entidades os ultimos 2 nomes iguais
					*/
					$params = array(
						'conditions' => array(
							'Entity.h_last1_last2' => $hash['h_last1_last2'],
							),
						);					
					if($this->Entity->find('count', $params)){
						$this->index($params);
					}else{
			
						/**
						* Caso nao encontre nenhuma entidade com os ultimos 2 nomes iguais
						* procura por entidades com qualquer semelhança no nome
						*/			
						$params = array(
							'conditions' => array(
								'OR' => array(
									'Entity.h_all' => $hash['h_all'],
									'Entity.h_last' => $hash['h_last'],
									'Entity.h_first_last' => $hash['h_first_last'],
									'Entity.h_last1_last2' => $hash['h_last1_last2'],
									'Entity.h_first1_first2' => $hash['h_first1_first2'],
									)
								),
							);					
						$this->index($params);
					}

				}
			}
		}


		return $people;
	}

	/**
	* Método getByLandline
	* Este método as regras de negocio responsaveis por encontrar a entidade a partir do telefone fixo
	*
	* @param array $query (parametros de busca)
	* @return array $people (dados da entidade encontrada)
	*/
	private function getByLandline($query){
		/**
		* Inicializa a variavel que contera os dados encontrados da entidade
		*/
		$people = false;

		/**
		* Uni o ddd ao telefone pesquisado
		*/
		if(isset($query['ddd']) && !empty($query['ddd']) && isset($query['landline']) && !empty($query['landline'])){
			$tel = "{$query['ddd']}{$query['landline']}";
		}else if(isset($query['landline']) && !empty($query['landline'])){
			$tel = $query['landline'];
		}

		$people_found = $this->Entity->Landline->find('list', array(
			'fields' => array('Association.entity_id', 'Association.entity_id'),
			'joins' => array(
				array('table' => 'associations',
			        'alias' => 'Association',
			        'type' => 'INNER',
			        'conditions' => array(
			            'Association.landline_id = Landline.id',
			        )
			    )
				),
			'conditions' => array(
				'OR' => array(
					'Landline.tel' => $tel,
					'Landline.tel_full' => $tel,
					)
				),
			'order' => array('Association.year' => 'desc', 'Association.entity_id')
			));

		$params = array(
			'conditions' => array(
				'Entity.id' => $people_found,
				),
			);						
		$this->index($params);

		return $people;
	}

	/**
	* Método getByAddress
	* Este método as regras de negocio responsaveis por encontrar a entidade a partir do endereço
	*
	* @param array $query (parametros de busca)
	* @return array $people (dados da entidade encontrada)
	*/
	private function getByAddress($query){
		/**
		* Inicializa a variavel que contera os dados encontrados da entidade
		*/
		$people = false;

		/**
		* Inicializa a variavel que guardara as condicoes de busca por endereco
		*/
		$cond = array();
		/**
		* Verifica se a numeracao do endereco foi preenchida
		*/
		if(isset($query['number_ini']) && !empty($query['number_ini'])){
			$cond['Address.number >'] = $query['number_ini'];
			if(isset($query['number_end']) && !empty($query['number_end'])){
				unset($cond['Address.number >']);
				$cond['Address.number BETWEEN ? AND ?'] = array($query['number_ini'], $query['number_end']);
			}
		}
		
		/**
		* Verifica se o cep do endereço foi prennchido
		*/
		if(isset($query['zipcode']) && !empty($query['zipcode'])){
			$zipcode = $this->Entity->Address->Zipcode->find('first', array('conditions' => array('Zipcode.code' => $query['zipcode'])));
			if(count($zipcode)){
				$cond['Address.zipcode_id'] = $zipcode['Zipcode']['id'];
			}
		}else{
			/**
			* Verifica se o nome da rua do endereço foi prenchido
			*/
			if(isset($query['street']) && !empty($query['street'])){
				/**
				* Gera o hash do nome da rua
				*/
				$hash = $this->AppImport->getHash($query['street']);
				$hasAddress = $this->Entity->Address->find('count', array('conditions' => array('h_all' => $hash['h_all'])));
				if($hasAddress){
					$cond['Address.h_all'] = $hash['h_all'];
				}else{
					$cond['OR']['Address.h_all'] = $hash['h_all'];
					$cond['OR']['Address.h_first_last'] = $hash['h_first_last'];
					$cond['OR']['Address.h_last'] = $hash['h_last'];
					$cond['OR']['Address.h_first1_first2'] = $hash['h_first1_first2'];
					$cond['OR']['Address.h_last1_last2'] = $hash['h_last1_last2'];
				}
			}

			/**
			* Verifica se o estado foi setado
			*/
			if(isset($query['state_id']) && !empty($query['state_id'])){
				$cond['Address.state_id'] = $query['state_id'];
			}

			/**
			* Verifica se a cidade foi setado
			*/
			if(isset($query['city_id']) && !empty($query['city_id'])){
				$cond['Address.city_id'] = $query['city_id'];
			}
		}

		$people_found = $this->Entity->Address->find('list', array(
			'fields' => array('Association.entity_id', 'Association.entity_id'),
			'joins' => array(
				array('table' => 'associations',
			        'alias' => 'Association',
			        'type' => 'INNER',
			        'conditions' => array(
			            'Association.address_id = Address.id',
			        )
			    ),
				),
			'conditions' => $cond,
			));

		$params = array(
			'conditions' => array(
				'Entity.id' => $people_found,
				),
			);						
		$this->index($params);

		return $people;
	}

	/**
	* Método landline
	* Este método corresponde ao produto 'Telefones Fixos' e retorna todos os telefones fixos da entidade a partir dos id's contidos em $associations
	*
	* @return array
	*/
	public function landline($associations){
		$map = array();
		$assoc_landlines = array();
    	if($associations){
			/**
			* Agrupa os telefones por ANO e ID
			*/
			foreach ($associations as $k => $v) {
				if(!empty($v['Association']['landline_id'])){
					$assoc_landlines["{$v['Association']['year']}{$v['Association']['landline_id']}"] = $v['Association'];
				}
			}

			/**
			* Carrega todos os dados dos telefones contidos na associacao
			*/
    		foreach ($assoc_landlines as $k => $v) {
				$landline = $this->Entity->Landline->find('first', array(
					'conditions' => array('Landline.id' => $v['landline_id'])
					));
				$landline['Landline']['year'] = $v['year'];
				$map[$v['id']] = $landline;
    		}
    	}

    	return $map;
	}

	/**
	* Método address
	* Este método corresponde ao produto 'Endereços' e retorna todos os telefones fixos da entidade a partir dos id's contidos em $associations
	*
	* @return array
	*/
	public function address($associations){
		$map = array();
		$assoc_address = array();
    	if($associations){
			/**
			* Agrupa os enderecos por ANO e ID
			*/
			foreach ($associations as $k => $v) {
				if(!empty($v['Association']['address_id'])){
					$assoc_address["{$v['Association']['year']}{$v['Association']['address_id']}"] = $v['Association'];
				}
			}

			/**
			* Carrega todos os dados dos enderecos contidos na associacao
			*/
    		foreach ($assoc_address as $k => $v) {
				$address = $this->Entity->Address->find('first', array(
					'conditions' => array('Address.id' => $v['address_id'])
					));

				$address['Address']['year'] = $v['year'];
				$map[$v['id']] = $address;
    		}
    	}

    	return $map;
	}

	/**
	* Método family
	* Este método corresponde ao produto 'Familia' e retorna todos os possiveis familiares da entidade consultada
	*
	* @return array
	*/
	public function family($people, $address){
		$map = array('Family' => array('mother' => array(), 'children' => array(), 'spouse' => array(), 'brothers' => array(), 'members' => array()));
		$members_found = array();
		$brothers = array();

		/**
		* Verifica se a entidade pesquisada consiste como uma pessoa fisica
		*/
		if($people['Entity']['type'] != TP_CNPJ){
			/**
			* Busca pela mae da entidade
			*/
			if($people['Entity']['h_mother'] > 0){
				$map['Family']['mother'] = $this->Entity->find('first', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.id NOT' => $people['Entity']['id'],
						'Entity.type !=' => TP_CNPJ,
						'Entity.h_all' => $people['Entity']['h_mother'],
						),
					'limit' => 1
				));

				if(isset($map['Family']['mother']['Entity']['id'])){
					/**
					* Carrega os ids dos parentes encontrados
					*/
					$members_found[] = $map['Family']['mother']['Entity']['id'];
				}

				/**
				* Remove a mae da entidade encontrada cado a mae tenha idade registrada e essa idade seja menos do que a da entidade
				*/
				if(isset($map['Family']['mother']['Entity']['age']) && $map['Family']['mother']['Entity']['age'] < $people['Entity']['age']){
					$map['Family']['mother'] = array();
				}
			}

			/**
			* Busca pelos filhos da entidade, caso seja mulher
			*/
			if($people['Entity']['gender'] == FEMALE){
				$brothers = $this->Entity->find('all', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.id NOT' => $people['Entity']['id'],
						'Entity.type !=' => TP_CNPJ,
						'Entity.h_all NOT' => $people['Entity']['h_all'],
						'Entity.h_mother' => $people['Entity']['h_all'],
						),
					'limit' => LIMIT_FAMILY
					));

				foreach ($brothers as $k => $v) {
					$map['Family']['children'][] = $v;
					/**
					* Carrega os ids dos parentes encontrados
					*/
					$members_found[] = $v['Entity']['id'];
				}
 			}				

			/**
			* Busca os irmaos da entidade comparando outras entidades 
			* com o mesmo nome da mae
			*/
			if($people['Entity']['h_mother'] > 0){
				$limit_family = LIMIT_FAMILY - count($members_found);
				$brothers = $this->Entity->find('all', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.id NOT' => $people['Entity']['id'],
						'Entity.type !=' => TP_CNPJ,
						'Entity.h_all NOT' => $people['Entity']['h_all'],
						'Entity.h_mother' => $people['Entity']['h_mother'],
						),
					'limit' => $limit_family
					));

				foreach ($brothers as $k => $v) {
					$map['Family']['brothers'][] = $v;
					/**
					* Carrega os ids dos parentes encontrados
					*/
					$members_found[] = $v['Entity']['id'];
				}
 			}				

			/**
			* Caso nao encontre a quantidade suficiente de supostos irmaos, busca em entidades com o mesmo sobrenome 
			*/
			if(count($members_found) < LIMIT_FAMILY){
				$limit_family = LIMIT_FAMILY - count($members_found);

				$brothers = $this->Entity->find('all', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.h_all NOT' => $people['Entity']['h_all'],
						'Entity.id NOT' => $people['Entity']['id'],
						'Entity.id NOT' => $members_found,
						'Entity.type !=' => TP_CNPJ,
						'Entity.h2' => $people['Entity']['h2'],
						'Entity.h3' => $people['Entity']['h3'],
						'Entity.h4' => $people['Entity']['h4'],
						'Entity.h5' => $people['Entity']['h5'],
						),
					'limit' => $limit_family
					));

				foreach ($brothers as $k => $v) {
					if(!in_array($v['Entity']['id'], $members_found)){
						/**
						* Caso o nome da entidade encontrada seja igual ao nome da mae da entidade pesquisada, é um forte indicio de que seja a sua mae
						*/
						if(!count($map['Family']['mother']) && $v['Entity']['h_all'] == $people['Entity']['h_mother']){
							$map['Family']['mother'] = $v;

						/**
						* Caso o nome da mae da entidade encontrada seja igual ao nome da entidade pesquisada, é um forte indicio de que seja seu irmao
						*/
						}else if($v['Entity']['h_mother'] == $people['Entity']['h_all']){
							$map['Family']['children'][] = $v;

						/**
						* Em todo caso, se a entidade encontrada tenha os mesmos sobre nome da entidade pesquisada, é um forte indicio de que seja da familia
						*/
						}else{
							$map['Family']['members'][] = $v;
						}
					}

					/**
					* Carrega os ids dos parentes encontrados
					*/
					$members_found[] = $v['Entity']['id'];
				}
			}

			/**
			* Caso nao encontre a quantidade suficiente de supostos irmaos, termina a busca com entidades com o mesmo sobrenome
			*/
			if(count($members_found) < LIMIT_FAMILY){
				$limit_family = LIMIT_FAMILY - count($members_found);

				$brothers = $this->Entity->find('all', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.h_all NOT' => $people['Entity']['h_all'],
						'Entity.id NOT' => $people['Entity']['id'],
						'Entity.id NOT' => $members_found,
						'Entity.type !=' => TP_CNPJ,
						'Entity.h_last1_last2' => $people['Entity']['h_last1_last2'],
						),
					'limit' => $limit_family
					));

				foreach ($brothers as $k => $v) {
					/**
					* Caso o nome da entidade encontrada seja igual ao nome da mae da entidade pesquisada, é um forte indicio de que seja a sua mae
					*/
					if(!count($map['Family']['mother']) && $v['Entity']['h_all'] == $people['Entity']['h_mother'] && $map['Family']['mother']['Entity']['age'] < $people['Entity']['age']){
						$map['Family']['mother'] = $v;
						/**
						* Caso o nome da mae da entidade encontrada seja igual ao nome da entidade pesquisada, é um forte indicio de que seja seu irmao
						*/
					}else if($v['Entity']['h_mother'] == $people['Entity']['h_all']){
						$map['Family']['children'][] = $v;
						/**
						* Em todo caso, se a entidade encontrada tenha os mesmos sobre nome da entidade pesquisada, é um forte indicio de que seja da familia
						*/
					}else{
						$map['Family']['members'][] = $v;
					}
				}
			}

			/**
			* Percorre por todos os endereços encontrados da entidade buscando um possivel conjuge
			*/
			foreach ($address as $k => $v) {
				/**
				* Busca o conjuge da entidade comparando outras entidades 
				* com o endereço identico, sexo oposto e nome das maes diferente
				*/
				if(!empty($v['Address']['complement']) && !empty($v['Address']['zipcode_id']) && !empty($people['Entity']['gender']) && !empty($v['Address']['number'])){
					switch ($people['Entity']['gender']) {
						case FEMALE:
							$opposit_gender = MALE;
							break;
						case MALE:
							$opposit_gender = FEMALE;
							break;
					}

					$spouse = $this->Entity->find('first', array(
						'fields' => '*',
						'joins' => array(
							array('table' => 'associations',
						        'alias' => 'Association',
						        'type' => 'INNER',
						        'conditions' => array(
						            'Association.entity_id = Entity.id',
						        )
						    ),
							array('table' => 'addresses',
						        'alias' => 'Address',
						        'type' => 'INNER',
						        'conditions' => array(
						            'Address.id = Association.address_id',
						        )
						    ),
							),
						'conditions' => array(
							'Entity.type !=' => TP_CNPJ,
							'Entity.gender' => $opposit_gender,
							'Entity.h_mother >' => 0,
							'Entity.h_all !=' => $people['Entity']['h_mother'],
							'Entity.h_mother !=' => $people['Entity']['h_mother'],
							'Address.id' => $v['Address']['id'],
							),
						'limit' => 1
						));

					/**
					* Interrompe a verificacao caso encontre alguma entidade que atenda todos os criterios
					*/
					if(count($spouse)){
						$map['Family']['spouse'] = $spouse;
						break;
					}
				}
			}
		}

    	return $map;
	}

	/**
	* Método neighborhood
	* Este método corresponde ao produto 'Vizinhos' e retorna todos os vizinhos da entidade consultada
	*
	* @return array
	*/
	public function neighborhood($people, $address){
		$map = array('Neighborhood' => array());
		$neighbors_found = array('same_address' => array(), 'same_floor' => array(), 'same_street' => array());
		$limit_neighbors = LIMIT_NEIGHBORS;

		/**
		* Percorre por todos os enderecos da entidade encontrada
		*/
		foreach ($address as $k => $v) {
			if($limit_neighbors > 0){
				/**
				* Procura por entidades com o mesmo endereço
				*/
				$neighbor = $this->Entity->Association->find('all', array(
					'fields' => '*',
					'conditions' => array(
						'Association.address_id' => $v['Address']['id'],
						'Association.entity_id !=' => $people['Entity']['id'],
						),
					'group' => 'Association.entity_id',
					'limit' => LIMIT_NEIGHBORS
					));
				foreach ($neighbor as $v2) {
					$neighbors_found['same_address'][$v2['Association']['entity_id']] = $v2['Association']['entity_id'];
					$limit_neighbors--;
				}

				/**
				* Procura por vizinhos que estejam no mesmo andar do endereço encontrado da entidade
				*/
				$regexp = $this->regexpApto($v);
				if($regexp && $limit_neighbors > 0){
					$neighbor = $this->Entity->Address->find('all', array(
						'fields' => '*',
                        'joins' => array(
                            array(
                                'table' => 'associations',
		                         'alias' => 'Association',
		                         'type' => 'INNER',
		                         'conditions' => array(
		                         'Association.address_id = Address.id',
		                         )
                            )
                        ),
						'conditions' => array(
							'Association.entity_id !=' => $people['Entity']['id'],
							'Address.id !=' => $v['Address']['id'],
							'Address.id NOT' => $neighbors_found['same_address'],
							'Address.zipcode_id' => $v['Address']['zipcode_id'],
							'Address.number' => $v['Address']['number'],
							"Address.complement REGEXP '{$regexp}'",
							),
						'group' => 'Association.entity_id',
						'limit' => $limit_neighbors
						));
					foreach ($neighbor as $v2) {
						$neighbors_found['same_floor'][$v2['Association']['entity_id']] = $v2['Association']['entity_id'];
						$limit_neighbors--;
					}
				}

				/**
				* Procura por vizinhos da mesma rua do endereço encontrado da entidade
				*/
				if($limit_neighbors > 0){
					$cond = array(
							'Address.id !=' => $v['Address']['id'],
							'Address.id NOT' => $neighbors_found['same_address'],
							'Address.id NOT' => $neighbors_found['same_floor'],
							'Address.zipcode_id' => $v['Address']['zipcode_id'],
							);

					if(!empty($v['Address']['number']) && is_numeric($v['Address']['number'])){
						$number_ini = $v['Address']['number'] - (LIMIT_NEIGHBORS*2);
						$number_ini = ($number_ini < 0)?1:$number_ini;
						$number_end = $v['Address']['number'] + (LIMIT_NEIGHBORS*2);
						$cond['Address.number BETWEEN ? AND ?'] = array($number_ini, $number_end);
					}
					
					$neighbor = $this->Entity->Address->find('all', array(
						'fields' => '*',
                        'joins' => array(
							array(
								'table' => 'associations',
								'alias' => 'Association',
								'type' => 'INNER',
								'conditions' => array(
								'Association.address_id = Address.id',
								)
							)
                        ),
						'conditions' => $cond,
						'group' => 'Association.entity_id',
						'limit' => $limit_neighbors
						));

					foreach ($neighbor as $v2) {
						$neighbors_found['same_street'][$v2['Association']['entity_id']] = $v2['Association']['entity_id'];
					}
				}
			}
		}

		/**
		* Carrega os dados a partir dos IDs de todas entidades vizinhas
		*/
		foreach ($neighbors_found as $k => $v) {
			foreach ($v as $k2 => $v2) {
				$map['Neighborhood'][$k][] = $this->Entity->findById($v2);
			}
		}

		return $map;
	}

	/**
	* Método loadAssociations
	* Este método carrega todas as chaves associativas da entidade, telefones fixos e seus respectivos endereços a partir do id da entidade
	*
	* @return array
	*/
	private function loadAssociations($entity){
		$map = array();
		if(isset($entity['Entity']['id']) && !empty($entity['Entity']['id'])){
			$map = $this->Entity->Association->find('all', array(
				'conditions' => array('Association.entity_id' => $entity['Entity']['id']),
				'order' => array('Association.year' => 'desc')
				)
			);
		}   

		/**
		* Retorna FALSE caso nao encontre nenhum registro na base de dados
		*/
		$map = count($map)?$map:false;

		return $map;
	}

	/**
	* Método regexpApto
	* Este metodo monta um aexpressao regular para os endereços de apartamento, com ele, 
	* consigo encontrar vizinhos do mesmo apartamento da entidade pesquisada
	*
	* @return string
	*/
	private function regexpApto($address){
		$regexp = false;

		if(!empty($address['Address']['complement'])){
			preg_match('/(apartamento|apto|apt|ap|sl|lj).?([0-9]{1,})*/si', $address['Address']['complement'], $vet);
			if(isset($vet[2])){
				$regexp = '(apartamento|apto|apt|ap|sl|lj).?' . substr($vet[2], 0, -1) . '[0-9]';
			}
		}

		return $regexp;
	}

	/**
	 * Se o campo "q" for igual a 1, simula o envio do form por get
	 * redirecionando para http://domain/controller/action/seach:value1/namedN:valueN
	 *
	 * @override Metodo AppController.__post2get
	 * [Esta funcao foi sobrescrita pois no caso de entidade, nao é necessario o reenvio do PASS Ex.: entities/people/100]
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
	        	if(!preg_match('/(page|search|doc|name|ddd|landline|zipcode|number_ini|number_end|street|state_id|city_id)/si', $k)){
	            	$redirect[$k] = $v;
	        	}
	        }

			$this->redirect($redirect);    		
    	}		
	}		
	
}
