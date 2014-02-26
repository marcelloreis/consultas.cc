<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Campaigns/
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Controller
 */

App::uses('AppBillingsController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Static content controller
 *
 * Este controlador contem regras de negócio aplicadas ao model Country
 *
 * @package       app.Controller
 * @link http://.framework.nasza.com.br/2.0/controller/Campaigns.html
 */
class CampaignsController extends AppBillingsController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'Campaigns';

	/**
	* Atributos da classe
	*/
	public $campaign_layout;


	/**
	* Controller models
	*
	* @note O MODEL SmsTemplate NAO FOI LIGADO COM belongsTo
	* 		PARA DAR LIBERDADE AO USUARIO DE SELECIONAR O Template
	*		E ADITA-LOS APOS A SELECAO, ENTAO, OS MODELS CITADOS SÓ SERVEM DE REFERENCIA
	*		E NAO EXATAMENTE COMO UMA CHAVE EXTRANGEIRA
	* @var array
	*/
	public $uses = array('Campaign', 'SmsTemplate','Entity');

	/**
	* Carrega os componentes que poderao ser usados em quaisquer controller desta framework
	*/
	public $components = array('Main.AppSms', 'AppImport');

	/**
	* Método beforeFilter
	* Esta função é executada antes de todas ações do controlador. 
	* E no caso da framework, esta sendo usado para checar uma sessão ativa e inspecionar permissões.
	*
	* @override Metodo AppController.beforeFilter
	* @return void
	*/
	public function beforeFilter() {
		/**
		* Aplica o filtro herdado de acordo com a action
		*/
		switch ($this->action) {
			case 'cron':
			case 'download':
				AppController::beforeFilter();
				break;
			
			default:
				parent::beforeFilter();
				break;
		}

		/**
		* Carrega os campos disponiveis para o mailing
		*/
		$this->campaign_layout = array(
			'entity_doc' => 'CPF/CNPJ',
			'entity_name' => 'Nome',
			'entity_mother' => 'Nome da Mãe',
			'entity_type_str' => 'Fisica/Juridica',
			'entity_gender_str' => 'Sexo',
			'entity_birthday_str' => 'Aniversário',
			'entity_age' => 'Idade',

			'landline_ddd' => '(fixo) DDD',
			'landline_tel' => '(fixo) Telefone',
			'landline_tel_full' => '(fixo) dddtelefone',
			
			'mobile_ddd' => '(móvel) DDD',
			'mobile_tel' => '(móvel) Telefone',
			'mobile_tel_full' => '(móvel) dddtelefone',
			
			'address_state' => 'Estado',
			'address_city' => 'Cidade',
			'address_zipcode' => 'CEP',
			'address_type_address' => 'Tipo do Logradouro',
			'address_street' => 'Logradouro',
			'address_number' => 'Número',
			'address_neighborhood' => 'Bairro',
			'address_complement' => 'Complemento',

			'association_year' => 'Ano de Atualização',
			);		
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
    	AppController::beforeRender();

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
		$state_id = !empty($this->data['Campaign']['state_id'])?$this->data['Campaign']['state_id']:null;
		$cities = $this->Campaign->City->loadByState($state_id);

    	/**
    	* Carrega as variaveis de ambiente
    	*/
    	$this->set(compact('states', 'uf', 'cities'));
	}

	/**
	* Método download
	* Este método gerencia os downloads das campanhas
	*
	* @return void
	*/
	public function download($token) {
		$this->Campaign->recursive = -1;

		/**
		* Carrega a campanha passada pelo parametro
		*/
		$campaign = $this->Campaign->findByToken($token);

		/**
		* Desencriptografa o nome do arquivo
		*/
		$name = "us{$campaign['Campaign']['user_id']}cl{$campaign['Campaign']['client_id']}ca{$campaign['Campaign']['id']}.zip";

		/**
		* Monta o diretorio a partir do nome do arquivo informado
		*/
		$path = ROOT . "/app/webroot/files/campaign/return/{$campaign['Campaign']['client_id']}/{$campaign['Campaign']['id']}/{$name}";

		/**
		* Verifica se o arquivo solicitado existe
		*/
		if(!is_file($path)){
			$this->Session->setFlash("Desculpe, este link esta incorreto ou não existe mais.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
			$this->Campaign->saveField('process_state', CAMPAIGN_DOWNLOADED_LINK_BROKEN);
			throw new NotFoundException();
		}

		/**
		* Verifica se o link esta na validade
		*/
		if(!empty($campaign['Campaign']['elapsed']) && $campaign['Campaign']['elapsed'] > CAMPAIGN_VALIDITY){
			$this->Session->setFlash("Desculpe, o prazo para baixar os arquivos expirou.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
			$this->Campaign->saveField('process_state', CAMPAIGN_DOWNLOADED_EXPIRED);
			throw new NotFoundException();
		}

		/**
		* Calcula quantas vezes o download foi efetuado
		*/
		$download_qt = empty($campaign['Campaign']['download_qt'])?1:$campaign['Campaign']['download_qt']+1;

		/**
		* Carrega os valores q serao atualizados
		*/
		$values = array(
			'download_date' => 'NOW()',
			'download_qt' => $download_qt,
			'process_state' => CAMPAIGN_DOWNLOADED
			);
		
		/**
		* Carrega as condicoes da atualozacao
		*/
		$condition = array('Campaign.id' => $campaign['Campaign']['id']);
		
		/**
		* Atualiza os dados da campanha
		*/
		$this->Campaign->updateAll($values, $condition);
		
		/**
		* DIspara o download
		*/
	    $this->response->file($path, array(
	        'download' => true,
	        'name' => $name,
	    ));

	    return $this->response;
	}

	/**
	* Método reload
	* Este método recarrega a campanha, colocando-a na fila de processamento novamente
	*
	* @return void
	*/
	public function reload($id, $redirect=true){
		$this->Campaign->recursive = -1;

		/**
		* Carrega a campanha
		*/
		$campaign = $this->Campaign->findById($id);

		/**
		* Remove o cache da campanha caso ela seja atualizada
		*/
		if(!empty($campaign['Campaign']['user_id']) && !empty($campaign['Campaign']['client_id']) && !empty($campaign['Campaign']['id'])){
			Cache::delete("us{$campaign['Campaign']['user_id']}cl{$campaign['Campaign']['client_id']}ca{$campaign['Campaign']['id']}", 'campaigns');
		}

		/**
		* Recarrega a campanha
		*/
		$values = array(
			'process_state' => CAMPAIGN_NOT_PROCESSED,
			'process_date' => null,
			'download_link' => null,
			'download_qt' => null,
			);
		$this->Campaign->updateAll($values, array('Campaign.id' => $id));
		/**
		* Volta para a pagina de onde veio
		*/
		if($redirect){
			$this->redirect($this->referer());
		}
	}

	/**
	* Método cron
	* Este método busca todos as campanhas q ainda nao foram processadas e as executa
	*
	* @return void
	*/
	public function cron(){
		/**
		* Desabilita a renderizacao do cake
		*/
		$this->autoRender = false;

		/**
		* Carrega o model de bilhetagem
		*/
		$this->loadModel('Billing');

		/**
		* Verifica se existe algum processo em execucao, caso exista, aborta o processo
		*/
		$isBusy = $this->Campaign->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				'Campaign.process_state' => CAMPAIGN_RUN_PROCESSED
				)
			));	
		if(!$isBusy){
			/**
			* Carrega todas as campanhas q ainda nao foram processadas
			*/
			$campaign = $this->Campaign->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'Campaign.process_state' => CAMPAIGN_NOT_PROCESSED
					),
				'order' => array('Campaign.modified'),
				));
	
			if(!empty($campaign['Campaign']['id'])){
				/**
				* Executa a campanha
				*/
				$this->build_campaign($campaign);
			}
		}
	}

	/**
	* Método loadEntities
	* Este método retorna as entidades encontradas a partir dos dados da campanha
	*
	* @param string $data (Dados da campanha)
	* @return void
	*/
	private function loadEntities($data){
		/**
		* Carrega os dados da entidade a partir do cache caso ja exista
		*/
		$this->cache_id = "us{$data['Campaign']['user_id']}cl{$data['Campaign']['client_id']}ca{$data['Campaign']['id']}";;		
		$this->entity = Cache::read($this->cache_id, 'campaigns');

		/**
		* Salva os dados encontrados da entidade em cache
		*/
		if(!$this->entity){
			/**
			* Contabiliza todos os registros de acordo com o filtro da campanha
			*/
			$fields = array('*');
			$limit = null;
			$cond = array();
			$joins = array();
			$order = array('Association.year' => 'DESC');
			$counter = array(
				'people' => 0, 
				'female' => 0, 
				'male' => 0, 
				'individual' => 0, 
				'corporation' => 0
				);

			/**
			* Monta o join com a tabela de telefones fixos e moveis
			*/
			switch ($data['Campaign']['tel_type']) {
				/**
				* Somente Fixos
				*/
				case TP_TEL_LANDLINE:
					$joins[] = array(
						'table' => 'assoc_landline_max',
						'alias' => 'Association',
						'type' => 'INNER',
						'conditions' => array(
							'Association.entity_id = Entity.id',
						)
					);

	        		$joins[] = array(
						'table' => 'landlines',
				        'alias' => 'Landline',
				        'type' => 'INNER',
				        'conditions' => array(
				            'Landline.id = Association.landline_id',
				        )
			        );
					/**
					* Traz somentes os registros com telefone movel
					*/
			        $cond['Association.landline_id NOT'] = null;
					break;
				
				/**
				* Somente Moveis
				*/
				case TP_TEL_MOBILE:
					$joins[] = array(
						'table' => 'assoc_mobile_max',
						'alias' => 'Association',
						'type' => 'INNER',
						'conditions' => array(
							'Association.entity_id = Entity.id',
						)
					);

					$joins[] = array(
						'table' => 'mobiles',
				        'alias' => 'Mobile',
				        'type' => 'INNER',
				        'conditions' => array(
				            'Mobile.id = Association.mobile_id',
				        )
					);
					/**
					* Traz somentes os registros com telefone movel
					*/
			        $cond['Association.mobile_id NOT'] = null;
					break;
			
				/**
				* Padrao
				*/
				default:
					$joins[] = array(
						'table' => 'associations',
						'alias' => 'Association',
						'type' => 'INNER',
						'conditions' => array(
							'Association.entity_id = Entity.id',
						)
					);
					break;
			}

	        /**
	        * Monta o join de endereco
	        */
			$joins[] = array(
				'table' => 'addresses',
		        'alias' => 'Address',
		        'type' => 'INNER',
		        'conditions' => array(
		            'Address.id = Association.address_id',
		        )
	        );        

			/**
			* Monta o join de CEPs
			*/
			$joins[] = array(
				'table' => 'zipcodes',
		        'alias' => 'Zipcode',
		        'type' => 'INNER',
		        'conditions' => array(
		            'Zipcode.id = Address.zipcode_id',
		        )
	        );			

	        /**
	        * Verifica se foi informado algum limite para a busca
	        */
			if(!empty($data['Campaign']['limit'])){
				$limit = $data['Campaign']['limit'];
			}

	        /**
	        * Monta a consulta com CEPs informadas
	        */
	        if(!empty($data['Campaign']['zipcodes'])){
				$zipcodes = preg_split('/\n/si', $data['Campaign']['zipcodes']);
				foreach ($zipcodes as $k => $v) {
					if(!empty($v)){
						$cond['Zipcode.code'][] = trim(preg_replace('/[^0-9]/si', '', $v));
					}
				}
	        }

	        /**
	        * Monta a consulta com as areas (Estado, Cidade e Bairros)
	        */
	        if(!empty($data['Campaign']['state_id'])){
				$cond['Address.state_id'] = $data['Campaign']['state_id'];
	        
	        	if(!empty($data['Campaign']['city_id'])){
			        	$city = $this->Campaign->City->findById($data['Campaign']['city_id']);
						$cond['Address.city like'] = $city['City']['name'];

			        if(!empty($data['Campaign']['neighbors'])){
						$neighbors = preg_split('/\n/si', $data['Campaign']['neighbors']);
						foreach ($neighbors as $k => $v) {
							if(!empty($v)){
								$cond_or[] = array('Address.neighborhood LIKE' => "%" . trim($v) . "%");
							}
						}
						$cond['AND'][]['OR'] = $cond_or;
			        }
		        }
	        }

	        /**
	        * Monta a consulta com DDDs informadas
	        */
	        if(!empty($data['Campaign']['ddd'])){
				$ddd = preg_split('/\n/si', $data['Campaign']['ddd']);
				foreach ($ddd as $k => $v) {
					if(!empty($v)){
						/**
						* Monta as condicoes de busca de telefones fixos e moveis
						*/
						switch ($data['Campaign']['tel_type']) {
							/**
							* Somente Fixos
							*/
							case TP_TEL_LANDLINE:
								break;
								$cond['Landline.ddd'][] = trim(preg_replace('/[^0-9]/si', '', $v));
							
							/**
							* Somente Moveis
							*/
							case TP_TEL_MOBILE:
								$cond['Mobile.ddd'][] = trim(preg_replace('/[^0-9]/si', '', $v));
								break;
						}
					}
				}
	        }

	        /**
	        * Verifica se foi informado algum sexo específico
	        */
			if(!empty($data['Campaign']['gender']) && $data['Campaign']['gender'] > 0){
				$cond['Entity.gender'] = $data['Campaign']['gender'];
			}			

	        /**
	        * Verifica se foi informado algum tipo de pessoa
	        */
			if(!empty($data['Campaign']['type']) && $data['Campaign']['type'] > 0){
				$cond['Entity.type'] = $data['Campaign']['type'];
			}			

	        /**
	        * Verifica se foi informado alguma faixa de idade
	        */
			if(!empty($data['Campaign']['age_ini']) && $data['Campaign']['age_ini'] > 0){
				$data['Campaign']['age_end'] = (!empty($data['Campaign']['age_end']) && $data['Campaign']['age_end'] > 0)?$data['Campaign']['age_end']:200;

				if(!empty($data['Campaign']['ignore_age_null']) && $data['Campaign']['ignore_age_null']){
					$cond['OR'] = array(
						array('Entity.birthday' => null),
						array("DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Entity.birthday)), '%Y')+0 BETWEEN ? AND ?" => array($data['Campaign']['age_ini'], $data['Campaign']['age_end']))
						);
				}else{
					$cond["DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Entity.birthday)), '%Y')+0 BETWEEN ? AND ?"] = array($data['Campaign']['age_ini'], $data['Campaign']['age_end']);
				}
			}			
			
			/**
			* Carrega o layout montado na campanha
			*/
			if(!empty($data['Campaign']['layout']) && count($data['Campaign']['layout'])){
				/**
				* Inicializa a variavel $fields com os campos padroes
				*/
				$fields = array(
					'Association.id',
					'Entity.gender',
					'Entity.type',
					);

				$layout = explode(';', $data['Campaign']['layout']);
				foreach ($layout as $k => $v) {
					$field = ucfirst(preg_replace('/^([a-z].*?)_/si', '$1.', $v));
					if($data['Campaign']['tel_type'] == TP_TEL_LANDLINE && strstr($field, 'Mobile')){
						unset($field);
					}
					if($data['Campaign']['tel_type'] == TP_TEL_MOBILE && strstr($field, 'Landline')){
						unset($field);
					}
					if(!empty($field)){
						$fields[] = $field;
					}
				}
			}	

			/**
			* Verifica se existe algum arquivo anexado a campanha
			*/	
			$hasAttachment = ($data['Campaign']['product_id'] == PRODUCT_CHECKLIST && !empty($data['Campaign']['source']) && !empty($data['Campaign']['source_dir']) && is_file(ROOT . "/app/webroot/files/campaign/source/{$data['Campaign']['source_dir']}/{$data['Campaign']['source']}"));

			/**
			* Concatena os documentos contidos no arquivo anexado a campanha
			*/
			if($hasAttachment){
				$source = file_get_contents(ROOT . "/app/webroot/files/campaign/source/{$data['Campaign']['source_dir']}/{$data['Campaign']['source']}");
				$map_source = explode("\n", $source);
				$map_docs = array();
				foreach ($map_source as $k => $v) {
					if(!empty($v) && $v > 0){
						$map_docs[] = (int)preg_replace('/[^0-9]/si', '', $v);
					}
				}
				if(count($map_docs)){
					$cond['Entity.doc'] = $map_docs;
				}

				/**
				* Desabilita o limite no ato da consulta, pois o limit sera aplicado manualmente depois da ordenacao das entidades encontradas
				*/
				$limit = null;
			}		

			/**
			* Carrega as entidades encontradas a partir do filtro da campanha
			*/
			$this->entity['Entity'] = $this->Entity->find('all', array(
				'recursive' => -1,
				'fields' => $fields,
				'conditions' => $cond,
				'joins' => $joins,
				'order' => $order,
				'limit' => $limit
				)
			);

			/**
			* Organiza as entidades encontradas de acordo com o arquivo anexo a campanha
			*/
			if($hasAttachment){
				$map_entities = array();
				$map_null = array();
				$hasEntity = false;

				foreach ($this->entity['Entity'][0] as $k => $v) {
					foreach ($v as $k1 => $v1) {
							$map_null[$k][$k1] = null;
					}
				}

				foreach ($map_source as $k => $v) {
					$doc = preg_replace('/[^0-9]/si', '', trim($v));
					$hasEntity = false;

					foreach ($this->entity['Entity'] as $k2 => $v2) {
						if($doc == $v2['Entity']['doc']){
							$map_entities[] = $v2;
							$hasEntity = true;
							break;
						}
					}

					if(!$hasEntity){
						$map_null['Entity']['doc'] = $v;
						$map_entities[] = $map_null;
					}
				}
				$this->entity['Entity'] = $map_entities;
			}

			/**
			* Percorre por todas as entidades encontradas a partir do filtro montado na campanha
			* contabilizando as entidades
			*/
			foreach ($this->entity['Entity'] as $k => $v) {
				/**
				* Contabiliza quantos registros foram encontrados
				*/
				$counter['people']++;

				/**
				* Contabiliza quantas mulheres/homens foram encontradas
				*/
				switch ($v['Entity']['gender']) {
					case FEMALE:
						$counter['female']++;
						break;
					case MALE:
						$counter['male']++;
						break;
				}

				/**
				* Contabiliza quantas pessoas fisicas/juridicas foram encontradas
				*/
				switch ($v['Entity']['type']) {
					case TP_CPF:
						$counter['individual']++;
						break;
					case TP_CNPJ:
						$counter['corporation']++;
						break;
				}
			}		

			/**
			* Salva os numeros contabilizados da campanha
			*/
			$this->Campaign->updateAll($counter, array('Campaign.id' => $data['Campaign']['id']));

			/**
			* Salva os dados encontrados da entidade em cache
			*/
			Cache::write($this->cache_id, $this->entity, 'campaigns');
		}
	}

	/**
	* Método loadTable
	* Este método carrega os dados das entidades em formato de Tabelas (HTML)
	*
	* @return void
	*/
	private function loadTable(){
		if(is_array($this->entity['Entity']) && count($this->entity['Entity'])){
			/**
			* Tags da tabela
			*/
			$thead_open = '<thead>';
			$thead_close = '</thead>';
			$tbody_open = '<tbody>';
			$tbody_close = '</tbody>';
			$table_open = '<table>';
			$table_close = '</table>';
			$tr_open = '<tr>';
			$tr_close = '</tr>';
			$td_open = '<td>';
			$td_close = '</td>';
			$th_open = '<th>';
			$th_close = '</th>';

			$table = '';

			/**
			* Carrega o cabecalho da tabela
			*/
			$header = array_slice($this->entity['Entity'], 0, 1);
			$lines = '';
			foreach ($header as $k => $v) {
				$lines .= "{$tr_open}";
				foreach ($v as $k2 => $v2) {
					foreach ($v2 as $k3 => $v3) {
						/**
						* Remove os campos q nao estiverem no layout da campanha
						*/	
						if(!empty($this->campaign_layout[strtolower($k2) . '_' . $k3])){
							$lines .= "{$th_open}" . $this->campaign_layout[strtolower($k2) . '_' . $k3] . "{$th_close}";
						}
					}
				}
				$lines .= "{$tr_close}";
			}
			$table = "{$thead_open}{$lines}{$thead_close}";

			/**
			* Carrega o body da tabela
			*/
			$lines = '';
			foreach ($this->entity['Entity'] as $k => $v) {
				$lines .= $tr_open;
				foreach ($v as $k2 => $v2) {		
					/**
					* Remove os campos q nao estiverem no layout da campanha
					*/	
					foreach ($v2 as $k3 => $v3) {
						if(empty($this->campaign_layout[strtolower($k2) . '_' . $k3])){
							unset($v2[$k3]);
						}
					}
					$lines .= $td_open . implode("{$td_close}{$td_open}", $v2) . $td_close;
				}
				$lines .= $tr_close;
			}
			$table .= "{$tbody_open}{$lines}{$tbody_close}";

			/**
			* Finaliza a montagem da tabela
			*/
			$table =  "{$table_open}{$table}{$table_close}";

			return $table;			
		}
	}

	/**
	* Método loadSemicolon
	* Este método carrega os dados das entidades em formato de textp (Separado por ;)
	*
	* @return void
	*/
	private function loadSemicolon(){
		if(is_array($this->entity['Entity']) && count($this->entity['Entity'])){
			$content = '';

			/**
			* Carrega o cabecalho da tabela
			*/
			$header = array_slice($this->entity['Entity'], 0, 1);
			$lines = '';
			foreach ($header as $k => $v) {
				foreach ($v as $k2 => $v2) {
					foreach ($v2 as $k3 => $v3) {
						if(!empty($this->campaign_layout[strtolower($k2) . '_' . $k3])){
							$lines .= ';' . $this->campaign_layout[strtolower($k2) . '_' . $k3];
						}
					}
				}
			}
			$content = substr($lines, 1) . "\n";

			/**
			* Carrega o body da tabela
			*/
			$lines = '';
			foreach ($this->entity['Entity'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					$lines .= implode(';', $v2);
				}
				$lines .=  "\n";
			}
			$content .= $lines;

			return $content;			
		}
	}

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppBillingsController.index
	* @param string $params
	* @return void
	*/
	public function index($params=array()){
		/**
		* Carrega as campanhas apenas do usuario logado
		*/
		$params['conditions']['Campaign.user_id'] = $this->Session->read('Auth.User.id');

		//@override
		parent::index($params);
	}	

	/**
	* Método edit
	* Este método contem regras de negocios para adicionar e editar registros na base de dados
	*
	* @override Metodo AppBillingsController.edit
	* @param string $id
	* @return void
	*/
	public function edit($id=null){
		/**
		 * Verifica se o formulário foi submetido por post
		 */
		if ($this->request->is('post') || $this->request->is('put')) {
			/**
			* Insere o valor padrao do estado do processo
			*/
			$this->request->data['Campaign']['process_state'] = CAMPAIGN_NOT_PROCESSED;

			/**
			* Carrega o layout montado na campanha
			*/
			if(!empty($this->request->data['Campaign']['layout']) && count($this->request->data['Campaign']['layout'])){
				$this->request->data['Campaign']['layout'] = implode(';', array_keys($this->request->data['Campaign']['layout']));
			}		

			/**
			* Gera o token da campanha
			*/
			if(empty($this->request->data['Campaign']['id'])){
				$token = md5(uniqid());
				$this->request->data['Campaign']['token'] = $token;
			}

			/**
			* Recarrega a campanha
			*/
			if(!empty($this->request->data['Campaign']['id'])){
				$this->reload($this->request->data['Campaign']['id'], false);
			}
		}		

		//@override
		parent::edit($id);

		/**
		* Carrega os templates que pertencem somente ao usuario logado
		*/
		$sms_templates = $this->SmsTemplate->find('list', array(
			'fields' => array(
				'SmsTemplate.template',
				'SmsTemplate.title',
				),
			'conditions' => array(
				'SmsTemplate.user_id' => $this->Session->read('Auth.User.id')
				)
			));

		/**
		* Carrega os valores do ID tel_type
		*/
		$tel_type = array(
			TP_TEL_LANDLINE => 'Somente Fixos',
			TP_TEL_MOBILE => 'Somente Móveis',
			);

		/**
		* Carrega os campos disponiveis para o mailing
		*/
		$layout = $this->campaign_layout;

		/**
		* Carrega o layout selecionado da campanha
		*/
		$layout_checked = array();
		if(!empty($this->data['Campaign']['layout']) && count($this->data['Campaign']['layout'])){
			$layout_checked = explode(';', $this->data['Campaign']['layout']);
		}	

		/**
		* Carrega as variaveis de ambiente
		*/		
		$this->set(compact('sms_templates', 'tel_type', 'layout', 'layout_checked'));
	}

	/**
	* Método index_mailing
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo CampaignsController.index
	* @param string $params
	* @return void
	*/
	public function index_mailing($params=array()){
		/**
		* Carrega as campanhas de mailing
		*/
		$params['conditions']['Campaign.product_id'] = PRODUCT_MAILING;

		$this->index($params);

		$this->view = 'index_mailing';
	}

	/**
	* Método index_sms
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo CampaignsController.index
	* @param string $params
	* @return void
	*/
	public function index_sms(){
		/**
		* Carrega as campanhas de sms
		*/
		$params['conditions']['Campaign.product_id'] = PRODUCT_SMS;

		$this->index($params);

		$this->view = 'index_sms';
	}

	/**
	* Método index_checklist
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo CampaignsController.index
	* @param string $params
	* @return void
	*/
	public function index_checklist($params=array()){
		/**
		* Carrega as campanhas de checklist
		*/
		$params['conditions']['Campaign.product_id'] = PRODUCT_CHECKLIST;

		$this->index($params);

		$this->view = 'index_checklist';
	}

	/**
	* Método edit_mailing
	* Este método contem regras de negocios para adicionar e editar registros na base de dados
	*
	* @override Metodo CampaignsController.edit
	* @param string $id
	* @return void
	*/
	public function edit_mailing($id=null){
		$this->redirect_edit = $this->action;

		/**
		* Valida o limite informado no formualrio
		*/
		if(!empty($this->request->data['Campaign']['limit']) && $this->request->data['Campaign']['limit'] > $this->balance && !$this->limit_exceeded){
			$this->request->data['Campaign']['limit'] = $this->balance;
		}

		if(empty($this->request->data['Campaign']['empty']) && !$this->limit_exceeded){
			$this->request->data['Campaign']['limit'] = $this->balance;
		}

		/**
		 * Verifica se o formulário foi submetido por post
		 */
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Campaign']['tp_search'] = TP_SEARCH_MAILING;
			$this->request->data['Campaign']['product_id'] = PRODUCT_MAILING;
			$this->request->data['Campaign']['user_id'] = $this->userLogged['id'];
			$this->request->data['Campaign']['client_id'] = $this->userLogged['client_id'];
		}		

		//override
		$this->edit($id);

		$this->view = $this->action;
	}

	/**
	* Método edit_sms
	* Este método contem regras de negocios para adicionar e editar registros na base de dados
	*
	* @override Metodo CampaignsController.edit
	* @param string $id
	* @return void
	*/
	public function edit_sms($id=null){
		$this->edit($id);

		$this->view = $this->action;
	}

	/**
	* Método edit_checklist
	* Este método contem regras de negocios para adicionar e editar registros na base de dados
	*
	* @override Metodo CampaignsController.edit
	* @param string $id
	* @return void
	*/
	public function edit_checklist($id=null){
		$this->redirect_edit = $this->action;

		/**
		* Valida o limite informado no formualrio
		*/
		if(!empty($this->request->data['Campaign']['limit']) && !empty($this->request->data['Campaign']['limit_max']) && $this->request->data['Campaign']['limit'] > $this->request->data['Campaign']['limit_max']){
			$this->request->data['Campaign']['limit'] = $this->request->data['Campaign']['limit_max'];
		}

		/**
		 * Verifica se o formulário foi submetido por post
		 */
		if ($this->request->is('post') || $this->request->is('put')) {

			$this->request->data['Campaign']['tp_search'] = TP_SEARCH_CHECKLIST;
			$this->request->data['Campaign']['product_id'] = PRODUCT_CHECKLIST;
			$this->request->data['Campaign']['user_id'] = $this->userLogged['id'];
			$this->request->data['Campaign']['client_id'] = $this->userLogged['client_id'];
		}		

		$this->edit($id);

		$this->view = $this->action;
	}

	/**
	* Método build_campaign
	* Este método é responsavel pela producao dos arquivos de retorno a partir da campanha de Mailing
	*
	* @param string $campaign
	* @return void
	*/
	private function build_campaign($campaign){
		/**
		* Variaveis q contera os retornos do cron
		*/
		$files = array(
			'txt' => '',
			'xls' => '',
			'info' => '',
			);

		/**
		* Altera o status da campanha para PROCESSO EM ANDAMENTO
		*/
		$this->Campaign->id = $campaign['Campaign']['id'];
		$this->Campaign->saveField('process_state', CAMPAIGN_RUN_PROCESSED);

		/**
		* Carrega os dados do usuario que criou a campanha
		*/
		$this->Campaign->User->recursive = -1;
		$user = $this->Campaign->User->findById($campaign['Campaign']['user_id']);

		/**
		* Carrega todos os precos dos produtos de acordo o pacote do cliente
		*/
		$obj_users = new UsersController();
		$client = $obj_users->loadClient($campaign['Campaign']['client_id']);
		$prices = $obj_users->loadPrices();	

		/**
		* Carrega os IDs necessarios para cobranca
		*/
		$this->tp_search = $campaign['Campaign']['tp_search'];
		$this->product_id = $campaign['Campaign']['product_id'];
		$this->user_name = $user['User']['given_name'];
		$this->user_id = $campaign['Campaign']['user_id'];
		$this->package_id = $client['Client']['package_id'];
		$this->billing_id = $client['Client']['billing_id'];
		$this->price_id = $prices['prices_id'][$this->package_id][$this->product_id];
		$this->price = $prices['prices_val'][$this->package_id][$this->product_id];
		$this->limit_exceeded = $client['Client']['limit_exceeded'];
		
		$hasSignature = $this->Campaign->Client->Invoice->find('count', array(
		                'recursive' => -1,
		                'conditions' => array(
		                    'Invoice.client_id' => $client['Client']['id'],
		                    'Invoice.is_signature' => true,
		                    'Invoice.is_paid' => true,
		                    )
		                ));
		$this->hasSignature = $hasSignature;

		$map = $this->Campaign->Client->Billing->findById($this->billing_id);
		$this->balance = ($map['Billing']['franchise'] - $map['Billing']['qt_queries']);
		$this->balance = ($this->balance < 0)?0:$this->balance;

		/**
		* Verifica se o cliente tem permissao/credito para continuar o processo
		*/
		if($this->security()){
			$files['info'] = $this->Session->read('Message.session_form.message');
		}else{
			/**
			* Carrega as entidades no atributo $this->entity a partir dos dados da campanha
			*/
			$this->loadEntities($campaign);
			$files['xls'] = $this->loadTable();
			$files['txt'] = $this->loadSemicolon();

			/**
			* Percorre por todos as entidades encontradas efetuando a cobrança
			*/
			if(!empty($this->entity['Entity'])){
				foreach ($this->entity['Entity'] as $k => $v) {
					if(!empty($v['Association']['id'])){
			    		/**
			    		* Recarrega o cache de paginas cobradas
			    		*/
						$this->query = "/campaigns/mailing/campaign:{$campaign['Campaign']['id']}/association_id:{$v['Association']['id']}";

						/**
						* Verifica se o usuario tem saldo/permissao
						*/
						if($this->security()){
							$files['info'] = $this->Session->read('Message.session_form.message');
							break;
						}else{
							/**
							* Efetua a cobrança do envio
							*/
							$this->charge();
						}
					}
				}
			}
		}

		/**
		* Cria a pasta onde sera guardado os arquivos
		*/
		$dir = ROOT . "/app/webroot/files/campaign/return/{$client['Client']['id']}/{$campaign['Campaign']['id']}";
		if(!is_dir($dir)){
			mkdir($dir, 0777, true);
		}

		/**
		* Salva os arquivos gerados
		*/
		$file_name = "us{$campaign['Campaign']['user_id']}cl{$campaign['Campaign']['client_id']}ca{$campaign['Campaign']['id']}";

		/**
		* Guarda o caminho e os nomes dos arquivos q serao gerados
		*/
		$file_path = array(
			"{$dir}/RELATORIO-{$file_name}.txt",
			"{$dir}/TEXTO-{$file_name}.txt",
			"{$dir}/EXCEL-{$file_name}.xls",
			);

		/**
		* Salva os arquivos gerados nos diretorios padrao
		*/
		file_put_contents("{$dir}/RELATORIO-{$file_name}.txt", $files['info']);			
		file_put_contents("{$dir}/TEXTO-{$file_name}.txt", $files['txt']);			
		file_put_contents("{$dir}/EXCEL-{$file_name}.xls", $files['xls']);

		/**
		* Compacta os arquivos gerados
		*/
		$this->AppUtils->zip($file_path, "{$dir}/{$file_name}.zip", true, true);

		/**
		* Remove os arquivos compactados
		*/
		foreach ($file_path as $k => $v) {
			unlink($v);
		}

		/**
		* Gera o link da campanha
		*/
		$download_link = PROJECT_LINK . "campaigns/download/{$campaign['Campaign']['token']}";

		/**
		* Altera o process_state da campanha para PROCESSADO
		*/
		$this->Campaign->saveField('process_state', CAMPAIGN_PROCESSED);
		$this->Campaign->saveField('process_date', date('Y-m-d H:i:s'));
		$this->Campaign->saveField('download_link', $download_link);

		/**
		* Dispara um email para o usuario que criou a campanha, avisando que os arquivos ja estao disponiveis
		*/
		$email = new CakeEmail('apps');
		$email->template('mailing');
		$email->emailFormat('html');
		$email->viewVars(array('user' => $user, 'campaign' => $campaign, 'download_link' => $download_link));

		$email->sender(array(EMAIL_NO_REPLAY => TITLE_APP));
		$email->from(array(EMAIL_NO_REPLAY => TITLE_APP));
		// $email->to($user['User']['given_name']);
		$email->to('marcello@marcelloreis.com');
		$email->subject("Campanha disponível para download.");
		$email->send();		
	}

	/**
	* Método send_sms
	* Este método é responsavel pelo envio de SMSs para os contatos vinculados a campanha passada por parametro
	*
	* @param string $id
	* @return void
	*/
	public function send_sms($id){
		/**
		* Carrega as entidades a partir dos dados da campanha no atributo $this->entity
		*/
		$data = $this->Campaign->findById($id);
		$this->loadEntities($data);

		/**
		* Carrega todos os precos dos produtos de acordo com os seus pacotes
		*/
		$users = new UsersController();
		$client = $users->loadClient($data['Campaign']['client_id']);
		$prices = $users->loadPrices();

		/**
		* Carrega os IDs de envio de SMS
		*/
		$this->tp_search = TP_SEARCH_SMS;
		$this->product_id = PRODUCT_SMS;
		$this->user_id = $data['Campaign']['user_id'];
		$this->package_id = $client['Client']['package_id'];
		$this->billing_id = $client['Client']['billing_id'];
		$this->price_id = $prices['prices_id'][$this->package_id][$this->product_id];
		$this->price = $prices['prices_val'][$this->package_id][$this->product_id];

		/**
		* Carrega o assunto do SMS com o titulo da campanha
		*/
		$params['subject'] = $data['Campaign']['title'];

		/**
		* Percorre por todos as entidades encontradas enviando o SMS para cada uma
		*/
		foreach ($this->entity['Entity'] as $k => $v) {
			/**
			* Prepara e carrega a mensagem que sera enviada por SMS
			*/
			$birthday = $v['Entity']['birthday']?$this->AppUtils->dt2br($v['Entity']['birthday']):null;
			$vars = array(
				'%nome%' => $v['Entity']['first_name'],
				'%nome_com%' => $v['Entity']['name'],
				'%sexo%' => $v['Entity']['gender_str'],
				'%idade%' => $v['Entity']['age'],
				'%aniversario%' => $birthday,
				);
			$params['msg'] = str_replace(array_keys($vars), $vars, $data['Campaign']['template']);

			/**
			* Carrega o numero que sera enviado o SMS
			*/
			$params['numbers'] = 27998890888;
			// $params['number'] = $v['Mobile']['tel_full'];

    		/**
    		* Recarrega o cache de paginas cobradas
    		*/
			$this->query = "/campaigns/send/3/{$v['Mobile']['tel_full']}";

			/**
			* Verifica se o usuario tem saldo/permissao para enviar SMSs
			*/
			if($this->security()){
				$this->AppSms->log = $this->Session->read('Message.session_form.message');
				$this->AppSms->status = false;
			}else{
				/**
				* Envia o SMS para o destinatario
				*/
				// $this->AppSms->send($params);

				/**
				* Efetua a cobrança do envio
				*/
				$this->charge();
			}

			/**
			* Salva as informacoes do log de envio
			*/
			$data_sms_sent = array(
				'SmsSent' => array(
					'campaign_id' => $id,
					'entity_id' => $v['Entity']['id'],
					'mobile_id' => $v['Mobile']['id'],
					'name' => $v['Entity']['name'],
					'number' => $v['Mobile']['tel_full'],
					'msg' => $params['msg'],
					'cost' => $this->AppUtils->num2db($this->price),
					'log' => $this->AppSms->log,
					'status' => $this->AppSms->status,
					)
				);
			$this->Campaign->SmsSent->create();
			$this->Campaign->SmsSent->save($data_sms_sent);
		}


		die('aqui');
	}

}