<?php
App::uses('AppBillingsController', 'Controller');
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
class EntitiesController extends AppBillingsController {
	/**
	* Método beforeFilter
	* Esta função é executada antes de todas ações do controlador. 
	* E no caso da framework, esta sendo usado para checar uma sessão ativa e inspecionar permissões.
	*
	* @override Metodo AppBillingsController.beforeFilter
	* @return void
	*/
	public function beforeFilter() {
    	/**
    	* Carrega a informacao do produto que esta sendo consumido
    	*/
    	$this->product_id = PRODUCT_PESSOAS;

		//@override
		parent::beforeFilter();

    	/**
    	* Renderiza qualquer action na view index por padrao
    	*/
    	$this->view = 'index';
	}	

    /**
	* Método beforeRender
    * Chamado depois controlador com as regras de negócio, mas antes da visão ser renderizada.
	*
	* @override Metodo AppBillingsController.beforeRender
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
		* Carrega as informacoes da entidade
		*/
		$entity = $this->entity;

    	/**
    	* Carrega as variaveis de ambiente
    	*/
    	$this->set(compact('states', 'uf', 'cities', 'entity'));
	}

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($id=false){
		if($id){
			/**
			* Carrega a entidade a partir do ID passado pelo parametro
			*/
			$this->Entity->recursive = 1;
			$this->entity = $this->Entity->findById($id);
		}
	}	

	/**
	* Método doc
	* Este método contem as regras de negocio responsaveis para encontrar a entidade a partir do documento
	*
	* @param array $doc (parametros de busca)
	*/
	public function doc($doc=false){
		if(!$doc && !empty($this->request->data['Entity']['doc'])){
			$this->redirect(array($this->request->data['Entity']['doc'], '#' => 'entity-main'));
		}
		
		if($doc){
			/**
			* Carrega a entidade a partir do documento passado pelo parametro
			*/
			$this->Entity->recursive = 1;
			$this->entity = $this->Entity->findByDoc($doc);
		}
	}

	/**
	* Método name
	* Este método as regras de negocio responsaveis por encontrar a entidade a partir do nome
	*
	* @param array $name (parametros de busca)
	*/
	public function name($name=false){
		if(!$name && !empty($this->request->data['Entity']['name'])){
			$this->redirect(array($this->request->data['Entity']['name'], '#' => 'entity-main'));
		}

		if($name){
			/**
			* Gera o hash do nome da entidade e
			*/
			$hash = $this->AppImport->getHash($this->AppImport->clearName($name));

			/**
			* Procura por outras entidades com o nome identico ao passado por parametro
			*/
			$this->entity = $this->Entity->_findName($hash);
			if(count($this->entity) > 1){
				$this->set('map_found', $this->map_found());
				$this->view = 'map';
			}else{
				$this->entity = $this->entity[0];
			}
		}
	}	

	/**
	* Método landline
	* Este método as regras de negocio responsaveis por encontrar a entidade a partir do telefone fixo
	*
	* @param array $query (parametros de busca)
	* @return array $people (dados da entidade encontrada)
	*/
	public function landline($ddd=false, $tel=false){
		if($ddd && !$tel){
			$tel = $ddd;
			$ddd = false;
		}

		if(!$tel && !empty($this->request->data['Entity']['tel'])){
			$this->request->data['Entity']['ddd'] = !empty($this->request->data['Entity']['ddd'])?$this->request->data['Entity']['ddd']:0;
			$this->redirect(array($this->request->data['Entity']['ddd'], $this->request->data['Entity']['tel'], '#' => 'entity-main'));
		}

		if($tel){
			/**
			* Uni o ddd ao telefone pesquisado
			*/
			if($ddd && $tel){
				$tel = preg_replace('/[^0-9]/', '', $ddd) . preg_replace('/[^0-9]/', '', $tel);
			}else if($tel){
				$tel = preg_replace('/[^0-9]/', '', $tel);
			}

			$this->phone = $this->Entity->Landline->_findLandline($ddd, $tel);
			$this->phone2entity();
			$this->set('map_found', $this->map_found());
			$this->view = 'map';
		}
	}

	/**
	* Método mobile
	* Este método as regras de negocio responsaveis por encontrar a entidade a partir do telefone movel
	*
	* @param array $query (parametros de busca)
	* @return array $people (dados da entidade encontrada)
	*/
	public function mobile($ddd=false, $tel=false){
		if($ddd && !$tel){
			$tel = $ddd;
			$ddd = false;
		}

		if(!$tel && !empty($this->request->data['Entity']['tel'])){
			$this->request->data['Entity']['ddd'] = !empty($this->request->data['Entity']['ddd'])?$this->request->data['Entity']['ddd']:0;
			$this->redirect(array($this->request->data['Entity']['ddd'], $this->request->data['Entity']['tel'], '#' => 'entity-main'));
		}

		if($tel){
			/**
			* Uni o ddd ao telefone pesquisado
			*/
			if($ddd && $tel){
				$tel = preg_replace('/[^0-9]/', '', $ddd) . preg_replace('/[^0-9]/', '', $tel);
			}else if($tel){
				$tel = preg_replace('/[^0-9]/', '', $tel);
			}

			$this->phone = $this->Entity->Mobile->_findMobile($ddd, $tel);
			$this->phone2entity();
			$this->set('map_found', $this->map_found());
			$this->view = 'map';
		}
	}

	/**
	* Método address
	* Este método as regras de negocio responsaveis por encontrar a entidade a partir do endereço
	*
	* @param array $query (parametros de busca)
	* @return array $people (dados da entidade encontrada)
	*/
	public function address(){
    	/**
		 * Se o campo "q" for igual a 1, simula o envio do form por get
		 * redirecionando para http://[domain]/[controller]/[action]/seach:value1/namedN:valueN
    	 */
    	$this->__post2get();

    	/**
    	* Carrega os parametros de busca
    	*/
    	$params = $this->params['named'];

    	/**
    	* Cancela a consulta caso o CEP seja generico
    	*/
    	if(preg_match('/[0-9]{5}000/si', preg_replace('/[^0-9]/si', '', $params['zipcode']))){
    		$this->Session->setFlash("{$this->userLogged['given_name']}, " . "o CEP [{$params['zipcode']}] é genérico, portanto não é possível concluir a busca.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
    		$this->redirect($this->referer());
    	}

    	$this->address = $this->Entity->Address->_findAddress($params);
		$this->address2entity();
		$this->set('map_found', $this->map_found());
		$this->view = 'map';
	}


	/**
	* Método mobile
	* Este método busca os telefones moveis a partir das chaves associativas passadas pelo request
	*/
	public function extra_mobile(){
		if(!empty($this->params->query['data']['Assoc']['id'])){
			$this->Entity->Mobile->recursive = 1;
			$map = $this->Entity->Mobile->findAllByid($this->params->query['data']['Assoc']['id']);
			/**
			* Ordena os resultados por data de atualizacao
			*/
			foreach ($map as $k => $v) {
				$mobiles["{$v['Association'][0]['year']}-{$v['Mobile']['tel_full']}"] = $v;
			}
			krsort($mobiles, SORT_NUMERIC);

			$this->set(compact('mobiles'));	
			$this->render($this->action, 'ajax');
		}
	}

	/**
	* Método landline
	* Este método busca os telefones moveis a partir das chaves associativas passadas pelo request
	*/
	public function extra_landline(){
		if(!empty($this->params->query['data']['Assoc']['id'])){
			$this->Entity->Landline->recursive = 1;
			$map = $this->Entity->Landline->findAllByid($this->params->query['data']['Assoc']['id']);
			/**
			* Ordena os resultados por data de atualizacao
			*/
			foreach ($map as $k => $v) {
				$landlines["{$v['Association'][0]['year']}-{$v['Landline']['tel_full']}"] = $v;
			}
			krsort($landlines, SORT_NUMERIC);

			$this->set(compact('landlines'));	
			$this->render($this->action, 'ajax');
		}
	}

	/**
	* Método locator
	* Este método busca os telefones moveis a partir das chaves associativas passadas pelo request
	*/
	public function extra_locator(){
		if(!empty($this->params->query['data']['Assoc']['id'])){
			$this->Entity->Address->recursive = 1;
			$locator = $this->Entity->Address->findAllByid($this->params->query['data']['Assoc']['id']);

			$this->set(compact('locator'));	
			$this->render($this->action, 'ajax');
		}
	}

	/**
	* Método family
	* Este método corresponde ao produto 'Familia' e retorna todos os possiveis familiares da entidade consultada
	*
	* @return array
	*/
	public function extra_family($id){
		$entity = $this->Entity->findById($id);

		$family = array('Family' => array('mother' => array(), 'children' => array(), 'spouse' => array(), 'brothers' => array(), 'members' => array()));
		$members_found = array();
		$brothers = array();

		/**
		* Verifica se a entidade pesquisada consiste como uma pessoa fisica
		*/
		if($entity['Entity']['type'] != TP_CNPJ){
			/**
			* Busca pela mae da entidade
			*/
			if($entity['Entity']['h_mother'] > 0){
				$family['Family']['mother'] = $this->Entity->find('first', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.id NOT' => $entity['Entity']['id'],
						'Entity.type !=' => TP_CNPJ,
						'Entity.h_all' => $entity['Entity']['h_mother'],
						),
					'limit' => 1
				));

				if(isset($family['Family']['mother']['Entity']['id'])){
					/**
					* Carrega os ids dos parentes encontrados
					*/
					$members_found[] = $family['Family']['mother']['Entity']['id'];
				}

				/**
				* Remove a mae da entidade encontrada cado a mae tenha idade registrada e essa idade seja menos do que a da entidade
				*/
				if(isset($family['Family']['mother']['Entity']['age']) && $family['Family']['mother']['Entity']['age'] < $entity['Entity']['age']){
					$family['Family']['mother'] = array();
				}
			}

			/**
			* Busca pelos filhos da entidade, caso seja mulher
			*/
			if($entity['Entity']['gender'] == FEMALE){
				$brothers = $this->Entity->find('all', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.id NOT' => $entity['Entity']['id'],
						'Entity.type !=' => TP_CNPJ,
						'Entity.h_all NOT' => $entity['Entity']['h_all'],
						'Entity.h_mother' => $entity['Entity']['h_all'],
						),
					'limit' => LIMIT_FAMILY
					));

				foreach ($brothers as $k => $v) {
					$family['Family']['children'][] = $v;
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
			if($entity['Entity']['h_mother'] > 0){
				$limit_family = LIMIT_FAMILY - count($members_found);
				$brothers = $this->Entity->find('all', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.id NOT' => $entity['Entity']['id'],
						'Entity.type !=' => TP_CNPJ,
						'Entity.h_all NOT' => $entity['Entity']['h_all'],
						'Entity.h_mother' => $entity['Entity']['h_mother'],
						),
					'limit' => $limit_family
					));

				foreach ($brothers as $k => $v) {
					$family['Family']['brothers'][] = $v;
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
						'Entity.h_all NOT' => $entity['Entity']['h_all'],
						'Entity.id NOT' => $entity['Entity']['id'],
						'Entity.id NOT' => $members_found,
						'Entity.type !=' => TP_CNPJ,
						'Entity.h2' => $entity['Entity']['h2'],
						'Entity.h3' => $entity['Entity']['h3'],
						'Entity.h4' => $entity['Entity']['h4'],
						'Entity.h5' => $entity['Entity']['h5'],
						),
					'limit' => $limit_family
					));

				foreach ($brothers as $k => $v) {
					if(!in_array($v['Entity']['id'], $members_found)){
						/**
						* Caso o nome da entidade encontrada seja igual ao nome da mae da entidade pesquisada, é um forte indicio de que seja a sua mae
						*/
						if(!count($family['Family']['mother']) && $v['Entity']['h_all'] == $entity['Entity']['h_mother']){
							$family['Family']['mother'] = $v;

						/**
						* Caso o nome da mae da entidade encontrada seja igual ao nome da entidade pesquisada, é um forte indicio de que seja seu irmao
						*/
						}else if($v['Entity']['h_mother'] == $entity['Entity']['h_all']){
							$family['Family']['children'][] = $v;

						/**
						* Em todo caso, se a entidade encontrada tenha os mesmos sobre nome da entidade pesquisada, é um forte indicio de que seja da familia
						*/
						}else{
							$family['Family']['members'][] = $v;
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
						'Entity.h_all NOT' => $entity['Entity']['h_all'],
						'Entity.id NOT' => $entity['Entity']['id'],
						'Entity.id NOT' => $members_found,
						'Entity.type !=' => TP_CNPJ,
						'Entity.h_last1_last2' => $entity['Entity']['h_last1_last2'],
						),
					'limit' => $limit_family
					));

				foreach ($brothers as $k => $v) {
					/**
					* Caso o nome da entidade encontrada seja igual ao nome da mae da entidade pesquisada, é um forte indicio de que seja a sua mae
					*/
					if(!count($family['Family']['mother']) && $v['Entity']['h_all'] == $entity['Entity']['h_mother'] && $family['Family']['mother']['Entity']['age'] < $entity['Entity']['age']){
						$family['Family']['mother'] = $v;
						/**
						* Caso o nome da mae da entidade encontrada seja igual ao nome da entidade pesquisada, é um forte indicio de que seja seu irmao
						*/
					}else if($v['Entity']['h_mother'] == $entity['Entity']['h_all']){
						$family['Family']['children'][] = $v;
						/**
						* Em todo caso, se a entidade encontrada tenha os mesmos sobre nome da entidade pesquisada, é um forte indicio de que seja da familia
						*/
					}else{
						$family['Family']['members'][] = $v;
					}
				}
			}
		}

		$this->set(compact('family'));	
		$this->render($this->action, 'ajax');
	}

	/**
	* Método neighborhood
	* Este método corresponde ao produto 'Vizinhos' e retorna todos os vizinhos da entidade consultada
	*
	* @return array
	*/
	public function extra_neighbors($id){
		$entity_id = $id;
		$address = $this->Entity->Association->find('all', array(
			'recursive' => 0,
			'fields' => 'Address.*',
			'conditions' => array(
				'Association.entity_id' => $entity_id
				)
			));


		$neighbors = array('Neighbors' => array());
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
						'Association.entity_id !=' => $entity_id,
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
							'Association.entity_id !=' => $entity_id,
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
					/**
					* Verifica se o CEP nao é generico
					*/
					if(preg_match('/[0-9]{5}000/si', preg_replace('/[^0-9]/si', '', $v['Address']['zipcode']))){
						$cond = array(
								'Address.id !=' => $v['Address']['id'],
								'Association.entity_id !=' => $entity_id,
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
							$limit_neighbors--;
						}
					}
				}

			}
		}

		/**
		* Carrega os dados a partir dos IDs de todas entidades vizinhas
		*/
		foreach ($neighbors_found as $k => $v) {
			foreach ($v as $k2 => $v2) {
				$neighbors['Neighbors'][$k][] = $this->Entity->findById($v2);
			}
		}

		$this->set(compact('neighbors'));	
		$this->render($this->action, 'ajax');
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
	* Método map_found
	* Este método retorna um array com a url montada de cada entidade encontrada a partir da pesquisa feita
	*
	* @return array
	*/
	private function map_found(){
		$url = $this->params['named'];
		unset($url['page']);
		$map = array();

		if(is_array($this->entity) && count($this->entity)){
			foreach ($this->entity as $k => $v) {
				$address = $this->Entity->Address->findById($v['Association'][key($v['Association'])]['address_id']);
				if(!isset($map[$address['Address']['state_id']]['qt'])){
					$map[$address['Address']['state_id']]['qt'] = 1;
				}else{
					$map[$address['Address']['state_id']]['qt']++;
				}
			}	
		}
		return $map;
	}

	/**
	* Método phone2entity
	* Este converte o array retornado das consultas de Landline e Mobile para a estrutura Entity
	*
	* @return array
	*/
	private function phone2entity(){
		$modelName = ucfirst($this->action);
		if(is_array($this->phone) && count($this->phone)){
			foreach ($this->phone as $k => $v) {
				foreach ($v['Entity'] as $k2 => $v2) {
					$this->entity[$v2['doc']]['Association'] = $v['Association'];
					unset($v2['Association']);
					$this->entity[$v2['doc']]['Entity'] = $v2;
					$this->entity[$v2['doc']][$modelName][$v[$modelName]['tel_full']] = $v[$modelName];
					$this->entity[$v2['doc']]['Address'] = $v['Address'];
				}
			}
		}
	}

	/**
	* Método address2entity
	* Este converte o array retornado das consultas de Landline e Mobile para a estrutura Entity
	*
	* @return array
	*/
	private function address2entity(){
		$modelName = ucfirst($this->action);
		foreach ($this->address as $k => $v) {
			foreach ($v['Entity'] as $k2 => $v2) {
				$this->entity[$v2['doc']]['Association'] = $v['Association'];
				unset($v2['Association']);
				$this->entity[$v2['doc']]['Entity'] = $v2;
				$this->entity[$v2['doc']][$modelName][$v[$modelName]['id']] = $v[$modelName];
				$this->entity[$v2['doc']]['Address'] = $v['Address'];
			}
		}
	}
}
