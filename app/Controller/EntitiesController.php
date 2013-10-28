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
	public $components = array('Import');

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($params=array()){
		$this->Entity->recursive = 1;

		//@override
		parent::index($params);
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
		* Carrega todos os estados cadastrados
		*/
		$states = $this->Entity->Address->State->find('list');

		/**
		* Carrega todos as cidades cadastrados
		*/
		$cities = array();

    	/**
		 * Se o campo "q" for igual a 1, simula o envio do form por get
		 * redirecionando para http://[domain]/[controller]/[action]/seach:value1/namedN:valueN
    	 */
    	$this->__post2get();
    	/**
    	* Carrega os parametros named na variavel $params
    	*/
    	$params = $this->params['named'];

    	/**
    	* Inicializa a variavel $people com false
    	*/
    	$people = false;
    	/**
    	* Carrega os dados da entidade a partir do id informado
    	*/
    	if($id){
	    	$people = $this->Entity->findById($id);
	    	
    	/**
    	* Carrega os dados da entidade a partir dos parametros passados
    	*/
    	}else if(!$id){
    		if(isset($params['doc'])){
    			$people = $this->Entity->findByDoc($params['doc']);
    		}else if(isset($params['name'])){
				/**
				* Gera o hash do nome da entidade e
				* procura por outras entidades com o nome identico ao passado por parametro
				*/
				$hash = $this->Import->getHash($this->Import->clearName($params['name']));
				$people_found = $this->Entity->findAllByHAll($hash['h_all']);
				if(count($people_found) == 1){
					$people = $people_found[0];
				}else if(count($people_found) > 1){
					$params = array(
						'conditions' => array(
							'Entity.h_all' => $hash['h_all'],
							),
						'order' => array('state_id')
						);					
					$this->index($params);
				}else{
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
						'order' => array('state_id')
						);					
					$this->index($params);
				}
    		}else if(isset($params['landline'])){
  			
				/**
				* Uni o ddd ao telefone pesquisado
				*/
    			if(isset($params['ddd']) && !empty($params['ddd']) && isset($params['landline']) && !empty($params['landline'])){
    				$tel = "{$params['ddd']}{$params['landline']}";
    			}else if(isset($params['landline']) && !empty($params['landline'])){
    				$tel = $params['landline'];
    			}

    			$people_found = $this->Entity->find('list', array(
    				'fields' => array('Entity.id', 'Entity.id'),
    				'joins' => array(
						array('table' => 'associations',
					        'alias' => 'Association',
					        'type' => 'INNER',
					        'conditions' => array(
					            'Association.entity_id = Entity.id',
					        )
					    ),
						array('table' => 'landlines',
					        'alias' => 'Landline',
					        'type' => 'INNER',
					        'conditions' => array(
					            'Landline.id = Association.landline_id',
					        )
					    ),
						),
    				'conditions' => array(
    					'OR' => array(
    						'Landline.tel' => $tel,
    						'Landline.tel_full' => $tel,
    						)
    					),
    				'order' => array('Association.year' => 'desc', 'Entity.id')
    				));

				if(count($people_found) == 1){
					$people = $this->Entity->findById($people_found[key($people_found)]);
				}else if(count($people_found) > 1){
					$params = array(
						'conditions' => array(
							'Entity.id' => $people_found,
							),
						);						
					$this->index($params);
				}    			
    		}else if(isset($params['zipcode']) || isset($params['street'])){
    			/**
    			* Inicializa a variavel que guardara as condicoes de busca por endereco
    			*/
    			$cond = array();
    			/**
    			* Verifica se a numeracao do endereco foi preenchida
    			*/
    			if(isset($params['number_ini']) && !empty($params['number_ini'])){
    				$cond['Address.number >'] = $params['number_ini'];
    				if(isset($params['number_end']) && !empty($params['number_end'])){
    					unset($cond['Address.number >']);
    					$cond['Address.number BETWEEN ? AND ?'] = array($params['number_ini'], $params['number_end']);
    				}
    			}
    			
    			/**
    			* Verifica se o cep do endereço foi prennchido
    			*/
    			if(isset($params['zipcode']) && !empty($params['zipcode'])){
    				$zipcode = $this->Entity->Address->Zipcode->find('first', array('conditions' => array('Zipcode.code' => $params['zipcode'])));
    				if(count($zipcode)){
    					$cond['Address.zipcode_id'] = $zipcode['Zipcode']['id'];
    				}
    			}else{
    				/**
    				* Verifica se o nome da rua do endereço foi prenchido
    				*/
    				if(isset($params['street']) && !empty($params['street'])){
						/**
						* Gera o hash do nome da rua
						*/
						$hash = $this->Import->getHash($params['street']);
						$hasAddress = $this->Entity->Address->find('count', array('conditions' => array('h_all' => $hash['h_all'])));
						if($hasAddress){
							$cond['Address.h_all'] = $hash['h_all'];
						}else{
							$cond['OR']['h_first_last'] = $hash['h_first_last'];
							$cond['OR']['h_last'] = $hash['h_last'];
							$cond['OR']['h_first1_first2'] = $hash['h_first1_first2'];
							$cond['OR']['h_last1_last2'] = $hash['h_last1_last2'];
						}
    				}

    				/**
    				* Verifica se o estado foi setado
    				*/
    				if(isset($params['state_id']) && !empty($params['state_id'])){
    					$cond['Address.state_id'] = $params['state_id'];
    				}

    				/**
    				* Verifica se a cidade foi setado
    				*/
    				if(isset($params['city_id']) && !empty($params['city_id'])){
    					$cond['Address.city_id'] = $params['city_id'];
    				}
    			}

    			$people_found = $this->Entity->find('list', array(
    				'fields' => array('Entity.id', 'Entity.id'),
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
    				'conditions' => $cond,
    				'order' => array('Association.year' => 'desc', 'Entity.id')
    				));

				if(count($people_found) == 1){
					$people = $this->Entity->findById($people_found[key($people_found)]);
				}else if(count($people_found) > 1){
					$params = array(
						'conditions' => array(
							'Entity.id' => $people_found,
							),
						);						
					$this->index($params);
				}    			
    		}
    	}

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
    	}

    	/**
    	* Carrega as variaveis de ambiente
    	*/
    	$this->set(compact('states', 'cities'));
	}	

	/**
	* Método redirectOnFound
	* Este método redireciona para a funcao 'people' caso a entidade passada por parametro contenha somente 1 registro
	* ou redireciona para a index caso contenha mais de uma entidade no parametro
	*
	* @return void
	*/
	private function redirectOnFound($entity, $hash, $type_hash){
		/**
		* Redireciona para o action INDEX passando o hash como parametro caso exista mais de uma entidade com o mesmo nome pesquisado
		*/
		if(count($entity) > 1){
			$this->redirect(array('action' => 'index', 't_hash' => $type_hash, 'hash' => $hash[$type_hash]));
		}		

		/**
		* Carrega a entidade econtrada caso exista somente uma com o mesmo nome pesquisado
		*/
		if(count($entity) == 1){
			$this->redirect(array('action' => 'people', $entity[key($entity)]['Entity']['id']));
		}
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
				if(isset($map['Family']['mother']['Entity']['age']) && $map['Family']['mother']['Entity']['age'] < $people['Entity']['age']){
					$map['Family']['mother'] = array();
				}
			}

			/**
			* Busca os irmaos da entidade comparando outras entidades 
			* com o mesmo nome da mae
			*/
			if($people['Entity']['h_mother'] > 0){
				$brothers = $this->Entity->find('all', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.id NOT' => $people['Entity']['id'],
						'Entity.type !=' => TP_CNPJ,
						'Entity.h_all NOT' => $people['Entity']['h_all'],
						'Entity.h_mother' => $people['Entity']['h_mother'],
						),
					'limit' => LIMIT_FAMILY
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
			* Percorre por todos os endereços encontrados da entidade buscando um possivel conjuje
			*/
			foreach ($address as $k => $v) {
				/**
				* Busca o conjuje da entidade comparando outras entidades 
				* com o endereço identico, sexo oposto e nome das maes diferente
				*/
				if(!empty($v['Address']['complement']) && !empty($v['Address']['zipcode_id']) && !empty($people['Entity']['gender']) && $people['Entity']['h_mother'] > 0 && !empty($v['Address']['number'])){
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
							'Entity.h_mother !=' => $people['Entity']['h_mother'],
							'Address.zipcode_id' => $v['Address']['zipcode_id'],
							'Address.h_complement' => $v['Address']['h_complement'],
							'Address.number' => $v['Address']['number'],
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
		$neighbors_found = array('same_house' => array(), 'same_floor' => array(), 'same_street' => array());
		$limit_neighbors = 0;
		/**
		* Percorre por todos os enderecos da entidade encontrada
		*/
		foreach ($address as $k => $v) {
			if((count($neighbors_found['same_house']) + count($neighbors_found['same_floor'])) < LIMIT_NEIGHBORS){
				/**
				* Procura por entidades que morem na mesma residencia
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
					$neighbors_found['same_house'][$v2['Association']['entity_id']] = $v2['Association']['entity_id'];
				}
				$limit_neighbors = LIMIT_NEIGHBORS - count($neighbors_found['same_house']);

				/**
				* Procura por vizinhos que estejam no mesmo andar do endereço encontrado da entidade
				*/
				$apto = $this->sameApto($v);
				if($apto && count($neighbors_found['same_house']) < LIMIT_NEIGHBORS){
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
							'Address.id !=' => $v['Address']['id'],
							'Address.id NOT' => $neighbors_found['same_house'],
							'Address.zipcode_id' => $v['Address']['zipcode_id'],
							'Address.number' => $v['Address']['number'],
							"Address.complement REGEXP '{$apto}[1-9]'",
							),
						'group' => 'Association.entity_id',
						'limit' => LIMIT_NEIGHBORS
						));
					foreach ($neighbor as $v2) {
						$neighbors_found['same_floor'][$v2['Association']['entity_id']] = $v2['Association']['entity_id'];
					}
				}
				$limit_neighbors = LIMIT_NEIGHBORS - (count($neighbors_found['same_house']) + count($neighbors_found['same_floor']));

				/**
				* Procura por vizinhos da mesma rua do endereço encontrado da entidade
				*/
				if((count($neighbors_found['same_house']) + count($neighbors_found['same_floor'])) < LIMIT_NEIGHBORS){
					$cond = array(
							'Address.id !=' => $v['Address']['id'],
							'Address.id NOT' => $neighbors_found['same_house'],
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
	* Método sameApto
	* Este metodo informa se a entidade esta localizado em apartamento, sala ou loja a partir do complemento
	* do endereço passado por parametro
	*
	* Caso encontre, retorna a numeraçao do ap/sala/loja sem a ultima casa numerica para ser usado como busca de vizinhos
	*
	* @return string
	*/
	private function sameApto($address){
		$lives = false;

		if(!empty($address['Address']['complement'])){
			preg_match('/(apartamento|apto|ap|sl|lj).?[0-9]*/si', $address['Address']['complement'], $vet);
			$lives = isset($vet[0])?substr(trim($vet[0]), 0, -1):false;
		}

		return $lives;
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
