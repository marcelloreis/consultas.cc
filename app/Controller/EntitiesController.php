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
    	* Carrega as informacoes do produto que esta sendo consumido
    	*/
		/**
		* Carrega o tipo da consulta
		*/
		switch ($this->action) {
			case 'index':
				$this->tp_search = TP_SEARCH_ID;
				$this->product_id = PRODUCT_PESSOAS;
				break;
			case 'doc':
				$this->tp_search = TP_SEARCH_DOC;
				$this->product_id = PRODUCT_PESSOAS;
				break;
			case 'name':
				$this->tp_search = TP_SEARCH_NAME;
				$this->product_id = PRODUCT_PESSOAS;
				break;
			case 'landline':
				$this->tp_search = TP_SEARCH_PHONE;
				$this->product_id = PRODUCT_PESSOAS;
				break;
			case 'mobile':
				$this->tp_search = TP_SEARCH_MOBILE;
				$this->product_id = PRODUCT_PESSOAS;
				break;
			case 'address':
				$this->tp_search = TP_SEARCH_ADDRESS;
				$this->product_id = PRODUCT_PESSOAS;
				break;
			case 'extra_mobile':
				$this->tp_search = TP_SEARCH_EXTRA_MOBILE;
				$this->product_id = PRODUCT_EXTRA_TELEFONE_MOVEL;
				break;
			case 'extra_landline':
				$this->tp_search = TP_SEARCH_EXTRA_LANDLINE;
				$this->product_id = PRODUCT_EXTRA_TELEFONE_FIXO;
				break;
			case 'extra_locator':
				$this->tp_search = TP_SEARCH_EXTRA_LOCATOR;
				$this->product_id = PRODUCT_LOCALIZADOR;
				break;
			case 'extra_family':
				$this->tp_search = TP_SEARCH_EXTRA_FAMILY;
				$this->product_id = PRODUCT_POSSIVEIS_PARENTES;
				break;
			case 'extra_neighbors':
				$this->tp_search = TP_SEARCH_EXTRA_NEIGHBORS;
				$this->product_id = PRODUCT_VIZINHOS;
				break;
		}    	
    	
		//@override
		parent::beforeFilter();

		/**
		* Carrega os dados da entidade a partir do cache caso ja exista
		*/
		$this->cache_id = Inflector::slug(substr(urldecode($_SERVER['REQUEST_URI']), 1), '_');		
		$this->entity = Cache::read($this->cache_id, 'campagins');

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
		if(!Cache::read('states', 'components')){
			Cache::write('states', $this->Entity->Address->State->find('list'), 'components');
		}
		$states = Cache::read('states', 'components');

		/**
		* Carrega todos os estados cadastrados
		*/
		if(!Cache::read('uf', 'components')){
			Cache::write('uf', $this->Entity->Address->State->find('list', array('fields' => array('State.id', 'State.uf'))), 'components');
		}
		$uf = Cache::read('uf', 'components');

		/**
		* Carrega todos as cidades cadastrados
		*/
		// $cities = array();

		/**
		* Carrega as informacoes da entidade
		*/
		$entity = $this->entity;

		/**
		* Salva os dados encontrados da entidade em cache
		*/
		if($this->entity){
			Cache::write($this->cache_id, $this->entity, 'entities');
		}

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
		if($id && !$this->entity){
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
		
		if($doc && !$this->entity){
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

		if($name && !$this->entity){
			/**
			* Gera o hash do nome da entidade e
			*/
			$hash = $this->AppImport->getHash($this->AppImport->clearName($name));

			/**
			* Procura por outras entidades com o nome identico ao passado por parametro
			*/
			$this->entity = $this->Entity->_findName($hash);

			if(count($this->entity) > 1){
				$this->loadMap();
			}else{
				$this->entity = $this->entity[0];
			}
		}

		if(isset($this->entity['map'])){
			$this->view = 'map';
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

		if($tel && !$this->entity){
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
			$this->loadMap();
		}

		$this->view = 'map';
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

		if($tel && !$this->entity){
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
			$this->loadMap();
		}

		$this->view = 'map';
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

    	if(!$this->entity){
	    	$this->address = $this->Entity->Address->_findAddress($params);
			$this->address2entity();
			$this->loadMap();
    	}
  	
		$this->view = 'map';
	}

	/**
	* Método extra_mobile
	* Este método busca os telefones moveis a partir das chaves associativas passadas pelo request
	*/
	public function extra_mobile(){
		if(!$this->entity && !empty($this->params->query['data']['Assoc']['id'])){
			$this->Entity->Mobile->recursive = 1;
			$map = $this->Entity->Mobile->findAllByid($this->params->query['data']['Assoc']['id']);

			/**
			* Ordena os resultados por data de atualizacao
			*/
			foreach ($map as $k => $v) {
				$this->entity["{$v['Association'][0]['year']}-{$v['Mobile']['tel_full']}"] = $v;
			}
			krsort($this->entity, SORT_NUMERIC);
		}

		$this->render($this->action, 'ajax');
	}

	/**
	* Método extra_landline
	* Este método busca os telefones moveis a partir das chaves associativas passadas pelo request
	*/
	public function extra_landline(){
		if(!$this->entity && !empty($this->params->query['data']['Assoc']['id'])){
			$this->Entity->Landline->recursive = 1;
			$map = $this->Entity->Landline->findAllById($this->params->query['data']['Assoc']['id']);

			/**
			* Ordena os resultados por data de atualizacao
			*/
			foreach ($map as $k => $v) {
				$this->entity["{$v['Association'][0]['year']}-{$v['Landline']['tel_full']}"] = $v;
			}
			krsort($this->entity, SORT_NUMERIC);
		}

		$this->render($this->action, 'ajax');
	}

	/**
	* Método extra_locator
	* Este método busca os telefones moveis a partir das chaves associativas passadas pelo request
	*/
	public function extra_locator(){
		if(!$this->entity && !empty($this->params->query['data']['Assoc']['id'])){
			$this->Entity->Address->recursive = 1;
			$map = $this->Entity->Address->findAllById($this->params->query['data']['Assoc']['id']);

			/**
			* Agrupa e ordena os resultados por data de atualizacao
			*/
			foreach ($map as $k => $v) {
				$this->entity["{$v['Association'][0]['year']}-{$v['Address']['id']}"] = $v;
			}
			if(is_array($this->entity)){
				krsort($this->entity, SORT_NUMERIC);
			}
		}

		$this->render($this->action, 'ajax');
	}

	/**
	* Método extra_family
	* Este método corresponde ao produto 'Familia' e retorna todos os possiveis familiares da entidade consultada
	*
	* @return array
	*/
	public function extra_family($id){
		if(!$this->entity){
			$this->Entity->recursive = 1;

			/**
			* Carrega os dados da entidade encontrada
			*/
			$entity = $this->Entity->findById($id);

			$this->entity = array('Family' => array('mother' => array(), 'children' => array(), 'spouse' => array(), 'brothers' => array(), 'members' => array()));
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
					$mothers = $this->Entity->find('all', array(
						'fields' => '*',
						'conditions' => array(
							'Entity.id NOT' => $entity['Entity']['id'],
							'Entity.type !=' => TP_CNPJ,
							'Entity.h_all' => $entity['Entity']['h_mother'],
							)
					));


					if(count($mothers)){
						/**
						* Remove todas as maes encontradas que tenham menos de 15 de diferença da entidade encontrada
						*/
						foreach ($mothers as $k => $v) {
							if(isset($v['Entity']['age']) && $v['Entity']['age'] <= ($entity['Entity']['age'] + 15)){
								unset($mothers[$k]);
							}
						}

						/**
						* Da preferencia para as maes que residem no mesmo estado da entidade encontrada
						*/
						foreach ($mothers as $k => $v) {
							//Verifica se a entidade econtrada e a mae da entidade tem endereços
							if(!empty($v['Address'][0]['state_id']) && !empty($entity['Address'][0]['state_id'])){
								//Caso ambos tenham endereços, verifica se sao iguais
								if($v['Address'][0]['state_id'] == $entity['Address'][0]['state_id']){
									/**
									* Carrega os ids dos parentes encontrados
									*/
									$this->entity['Family']['mother'][] = $v;
									$members_found[] = $v['Entity']['id'];
								}
							}
						}

						/**
						* Se com o filtro por estado nao sejam encontrados nenhuma mae, 
						* retorna todas as maes encontradas independetes do seus respectivos estados
						*/
						if(!count($this->entity['Family']['mother'])){
							foreach ($mothers as $k => $v) {
								/**
								* Carrega os ids dos parentes encontrados
								*/
								$this->entity['Family']['mother'][] = $v;
								$members_found[] = $v['Entity']['id'];
							}
						}
					}

					/**
					* Remove a mae da entidade encontrada cado a mae tenha idade registrada e essa idade seja menos do que a da entidade
					*/
					if(isset($this->entity['Family']['mother']['Entity']['age']) && $this->entity['Family']['mother']['Entity']['age'] < $entity['Entity']['age']){
						$this->entity['Family']['mother'] = array();
					}
				}

				/**
				* Busca pelos filhos da entidade, caso seja mulher
				*/
				if($entity['Entity']['gender'] == FEMALE){
					$children = $this->Entity->find('all', array(
						'fields' => '*',
						'conditions' => array(
							'Entity.id NOT' => $entity['Entity']['id'],
							'Entity.type !=' => TP_CNPJ,
							'Entity.h_all NOT' => $entity['Entity']['h_all'],
							'Entity.h_mother' => $entity['Entity']['h_all'],
							),
						'limit' => LIMIT_FAMILY
						));

					/**
					* Remove todos os filhos encontradas que tenham mais de 15 de diferença da entidade encontrada
					*/
					foreach ($children as $k => $v) {
						if(isset($v['Entity']['age']) && $v['Entity']['age'] >= ($entity['Entity']['age'] + 15)){
							unset($mothers[$k]);
						}
					}
					

					foreach ($children as $k => $v) {
						$this->entity['Family']['children'][] = $v;
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
						$this->entity['Family']['brothers'][] = $v;
						/**
						* Carrega os ids dos parentes encontrados
						*/
						$members_found[] = $v['Entity']['id'];
					}
	 			}				

				/**
				* Caso nao encontre a quantidade suficiente de supostos irmaos, busca entidades com os 2 ultimos sobre nomes iguais
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
						if(!in_array($v['Entity']['id'], $members_found)){
							/**
							* Caso o nome da entidade encontrada seja igual ao nome da mae da entidade pesquisada, é um forte indicio de que seja a sua mae
							*/
							if(!count($this->entity['Family']['mother']) && $v['Entity']['h_all'] == $entity['Entity']['h_mother']){
								$this->entity['Family']['mother'] = $v;

							/**
							* Caso o nome da mae da entidade encontrada seja igual ao nome da entidade pesquisada, é um forte indicio de que seja seu irmao
							*/
							}else if($v['Entity']['h_mother'] == $entity['Entity']['h_all']){
								$this->entity['Family']['children'][] = $v;

							/**
							* Em todo caso, se a entidade encontrada tenha os mesmos sobre nome da entidade pesquisada, é um forte indicio de que seja da familia
							*/
							}else{
								$this->entity['Family']['members'][] = $v;
							}
						}

						/**
						* Carrega os ids dos parentes encontrados
						*/
						$members_found[] = $v['Entity']['id'];
					}
				}

				/**
				* Caso nao encontre a quantidade suficiente de supostos irmaos, busca entidades com todos os sobrenome iguais
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
							if(!count($this->entity['Family']['mother']) && $v['Entity']['h_all'] == $entity['Entity']['h_mother']){
								$this->entity['Family']['mother'] = $v;

							/**
							* Caso o nome da mae da entidade encontrada seja igual ao nome da entidade pesquisada, é um forte indicio de que seja seu irmao
							*/
							}else if($v['Entity']['h_mother'] == $entity['Entity']['h_all']){
								$this->entity['Family']['children'][] = $v;

							/**
							* Em todo caso, se a entidade encontrada tenha os mesmos sobre nome da entidade pesquisada, é um forte indicio de que seja da familia
							*/
							}else{
								$this->entity['Family']['members'][] = $v;
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
						if(!count($this->entity['Family']['mother']) && $v['Entity']['h_all'] == $entity['Entity']['h_mother'] && $this->entity['Family']['mother']['Entity']['age'] < $entity['Entity']['age']){
							$this->entity['Family']['mother'] = $v;
							/**
							* Caso o nome da mae da entidade encontrada seja igual ao nome da entidade pesquisada, é um forte indicio de que seja seu irmao
							*/
						}else if($v['Entity']['h_mother'] == $entity['Entity']['h_all']){
							$this->entity['Family']['children'][] = $v;
							/**
							* Em todo caso, se a entidade encontrada tenha os mesmos sobre nome da entidade pesquisada, é um forte indicio de que seja da familia
							*/
						}else{
							$this->entity['Family']['members'][] = $v;
						}
					}
				}
			}
		}

		$this->render($this->action, 'ajax');
	}

	/**
	* Método extra_neighbors
	* Este método corresponde ao produto 'Vizinhos' e retorna todos os vizinhos da entidade consultada
	*
	* @return array
	*/
	public function extra_neighbors($id){
		if(!$this->entity){
			$entity_id = $id;
			$address = $this->Entity->Association->find('all', array(
				'recursive' => 0,
				'conditions' => array(
					'Association.entity_id' => $entity_id
					),
				'order' => array('Association.year' => 'desc'),
				));

			$this->entity = array('Neighbors' => array());
			$neighbors_found = array('mesmo_endereco' => array(), 'mesmo_andar' => array(), 'mesma_rua' => array());
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
						$neighbors_found['mesmo_endereco'][$v2['Association']['entity_id']] = $v2['Association']['entity_id'];
						$limit_neighbors--;
					}

					/**
					* Procura por vizinhos que estejam no mesmo andar do endereço encontrado da entidade
					*/
					if($v['Address']['zipcode_id']){
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
									'Address.id NOT' => $neighbors_found['mesmo_endereco'],
									'Address.zipcode_id' => $v['Address']['zipcode_id'],
									'Address.number' => $v['Address']['number'],
									"Address.complement REGEXP '{$regexp}'",
									),
								'group' => 'Association.entity_id',
								'limit' => $limit_neighbors
								));
							foreach ($neighbor as $v2) {
								$neighbors_found['mesmo_andar'][$v2['Association']['entity_id']] = $v2['Association']['entity_id'];
								$limit_neighbors--;
							}
						}
					}

					/**
					* Procura por vizinhos de parede da entidade encontrada
					*/
					if($limit_neighbors > 0){
						/**
						* Verifica se o CEP nao é generico
						*/
						if($v['Address']['zipcode_id'] && !preg_match('/[0-9]{5}000/si', preg_replace('/[^0-9]/si', '', $v['Address']['zipcode']))){
							$cond = array(
									'Address.id !=' => $v['Address']['id'],
									'Association.entity_id !=' => $entity_id,
									'Address.id NOT' => $neighbors_found['mesmo_endereco'],
									'Address.id NOT' => $neighbors_found['mesmo_andar'],
									'Address.zipcode_id' => $v['Address']['zipcode_id'],
									);

							if(!empty($v['Address']['number']) && is_numeric($v['Address']['number'])){
								$number_ini = $v['Address']['number'] - 3;
								$number_ini = ($number_ini < 0)?1:$number_ini;
								$number_end = $v['Address']['number'] + 3;
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
								$neighbors_found['mesma_rua'][$v2['Association']['entity_id']] = $v2['Association']['entity_id'];
								$limit_neighbors--;
							}
						}
					}

					/**
					* Procura por vizinhos da mesma rua do endereço encontrado da entidade
					*/
					if($limit_neighbors > 0){
						/**
						* Verifica se o CEP nao é generico
						*/
						if($v['Address']['zipcode_id'] && !preg_match('/[0-9]{5}000/si', preg_replace('/[^0-9]/si', '', $v['Address']['zipcode']))){
							$cond = array(
									'Address.id !=' => $v['Address']['id'],
									'Association.entity_id !=' => $entity_id,
									'Address.id NOT' => $neighbors_found['mesmo_endereco'],
									'Address.id NOT' => $neighbors_found['mesmo_andar'],
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
								$neighbors_found['mesma_rua'][$v2['Association']['entity_id']] = $v2['Association']['entity_id'];
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
					$this->entity['Neighbors'][$k][] = $this->Entity->findById($v2);
				}
			}
		}

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
			preg_match('/(apartamento|apto|apt|ap|sl|lj) ?([0-9]{1,})*/si', $address['Address']['complement'], $vet);

			if(isset($vet[2])){
				$regexp = '(apartamento|apto|apt|ap|sl|lj) ?' . substr($vet[2], 0, -1) . '[0-9]';
			}
		}

		return $regexp;
	}

	/**
	* Método loadMap
	* Este método retorna um array com a url montada de cada entidade encontrada a partir da pesquisa feita
	*
	* @return array
	*/
	private function loadMap(){
		$url = $this->params['named'];
		unset($url['page']);
		$this->entity['map'] = array();

		if(is_array($this->entity) && count($this->entity)){
			foreach ($this->entity as $k => $v) {
				$address = (isset($v['Association']) && !empty($v['Association'][key($v['Association'])]['address_id']))?$this->Entity->Address->findById($v['Association'][key($v['Association'])]['address_id']):null;
				if(!isset($this->entity['map'][$address['Address']['state_id']]['qt'])){
					$this->entity['map'][$address['Address']['state_id']]['qt'] = 1;
				}else{
					$this->entity['map'][$address['Address']['state_id']]['qt']++;
				}
			}	
		}
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
