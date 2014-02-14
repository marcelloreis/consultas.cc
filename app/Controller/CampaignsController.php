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
	* Método beforeFilter
	* Esta função é executada antes de todas ações do controlador. 
	* E no caso da framework, esta sendo usado para checar uma sessão ativa e inspecionar permissões.
	*
	* @override Metodo AppController.beforeFilter
	* @return void
	*/
	public function beforeFilter() {
		AppController::beforeFilter();

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
		$cities = array();
		if(!empty($this->data['Campaign']['state_id'])){
			$cities = $this->Campaign->City->find('list', array(
				'recursive' => -1,
				'fields' => array('City.id', 'City.name'),
				'conditions' => array(
					'City.state_id' => $this->data['Campaign']['state_id']
					)
				));
		}		

    	/**
    	* Carrega as variaveis de ambiente
    	*/
    	$this->set(compact('states', 'uf', 'cities'));
	}

	/**
	* Controller models
	*
	* @note OS MODELS SmsTemplate E Contact NAO FORAM LIGADOS COM belongsTo
	* 		PARA DAR LIBERDADE AO USUARIO DE SELECIONAR O Template OU O Grupo
	*		E ADITA-LOS APOS A SELECAO, ENTAO, OS MODELS CITADOS SÓ SERVEM DE REFERENCIA
	*		E NAO EXATAMENTE COMO UMA CHAVE EXTRANGEIRA
	* @var array
	*/
	public $uses = array('Campaign', 'SmsTemplate', 'Contact', 'Entity');

	/**
	* Carrega os componentes que poderao ser usados em quaisquer controller desta framework
	*/
	public $components = array('Main.AppSms', 'AppImport');

	/**
	* Método loadEntities
	* Este método retorna as entidades encontradas a partir dos dados da campanha
	*
	* @param string $data (Dados da campanha)
	* @return void
	*/
	private function loadEntities($data){
		/**
		* Contabiliza todos os registros de acordo com o filtro da campanha
		*/
		$fields = array('*');
		$limit = null;
		$cond = array();
		$joins = array();
		$counter = array(
			'people' => 0, 
			'female' => 0, 
			'male' => 0, 
			'individual' => 0, 
			'corporation' => 0
			);

		/**
		* Monta o join com a tabela de associacoes
		*/
		$joins[] = array(
			'table' => 'associations',
	        'alias' => 'Association',
	        'type' => 'INNER',
	        'conditions' => array(
	            'Association.entity_id = Entity.id',
	        )
        );

        /**
        * Monta o join de endereco
        */
        if(!empty($data['Campaign']['zipcodes']) || !empty($data['Campaign']['state_id'])){
			$joins[] = array(
				'table' => 'addresses',
		        'alias' => 'Address',
		        'type' => 'INNER',
		        'conditions' => array(
		            'Address.id = Association.address_id',
		        )
	        );        
        }

		/**
		* Monta o join com a tabela de telefones fixos e moveis
		*/
		switch ($data['Campaign']['tel_type']) {
			/**
			* Somente Fixos
			*/
			case TP_TEL_LANDLINE:
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
		}

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
			$joins[] = array(
				'table' => 'zipcodes',
		        'alias' => 'Zipcode',
		        'type' => 'INNER',
		        'conditions' => array(
		            'Zipcode.id = Address.zipcode_id',
		        )
	        );
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
		if(!empty($data['Campaign']['gender_str']) && $data['Campaign']['gender_str'] > 0){
			$cond['Entity.gender'] = $data['Campaign']['gender_str'];
		}			

        /**
        * Verifica se foi informado algum tipo de pessoa
        */
		if(!empty($data['Campaign']['type_str']) && $data['Campaign']['type_str'] > 0){
			$cond['Entity.type'] = $data['Campaign']['type_str'];
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
				'Association.id'
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
		* Carrega as entidades encontradas a partir do filtro da campanha
		*/
		$this->entity['Entity'] = $this->Entity->find('all', array(
			'recursive' => -1,
			'fields' => $fields,
			'conditions' => $cond,
			'joins' => $joins,
			'order' => array('Association.year' => 'DESC'),
			'limit' => $limit
			)
		);

		/**
		* Carrega os contatos contidos no campo contacts no atributo $this->entity
		*/
		$this->loadContact($data);

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
			switch ($v['Entity']['gender_str']) {
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
			switch ($v['Entity']['type_str']) {
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
	}

	/**
	* Método loadContact
	* Este método é responsavel pelo envio de SMSs para os contatos vinculados a campanha passada por parametro
	*
	* @param string $id
	* @return void
	*/
	private function loadContact($data){
		/**
		* Concatena os contatos informados manualmente da campanha
		*/
		if(!empty($data['Campaign']['contacts'])){
			$contacts = preg_split('/\n/si', $data['Campaign']['contacts']);

			foreach ($contacts as $k => $v) {
				if(!empty($v)){
					$i = count($this->entity['Entity']);
					$name = substr(trim($v), 0, strpos(trim($v), ','));
					$first_name =  substr(trim($v), 0, strpos($name, ' '));
					$tel_full = preg_replace('/[^0-9]/si', '', substr(trim($v), strpos(trim($v), ',')));
					$gender = $this->AppImport->getGender(false, TP_CPF, $name);
					$gender_str = null;
					if($gender){
						$gender_str = $gender == FEMALE?'Feminino':'Masculino';
					}

					$this->entity['Entity'][$i]['Entity']['id'] = null;
					$this->entity['Entity'][$i]['Entity']['name'] = empty($name)?null:$name;
					$this->entity['Entity'][$i]['Entity']['type'] = TP_CPF;
					$this->entity['Entity'][$i]['Entity']['birthday'] = null;
					$this->entity['Entity'][$i]['Entity']['gender'] = $gender;
					$this->entity['Entity'][$i]['Entity']['age'] = null;
					$this->entity['Entity'][$i]['Entity']['gender_str'] = $gender_str;
					$this->entity['Entity'][$i]['Entity']['first_name'] = empty($first_name)?null:$first_name;
					$this->entity['Entity'][$i]['Mobile']['id'] = null;
					$this->entity['Entity'][$i]['Mobile']['tel_full'] = empty($tel_full)?null:$tel_full;

					/**
					* Filtra os contatos da lista de acordo com o filtro da camapanha
					*/
					if($data['Campaign']['gender'] == FEMALE && $gender == MALE){
						unset($this->entity['Entity'][$i]);
					}			
					if($data['Campaign']['gender'] == MALE && $gender == FEMALE){
						unset($this->entity['Entity'][$i]);
					}			
				}
			}			
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
			* Carrega o layout montado na campanha
			*/
			if(!empty($this->request->data['Campaign']['layout']) && count($this->request->data['Campaign']['layout'])){
				$this->request->data['Campaign']['layout'] = implode(';', array_keys($this->request->data['Campaign']['layout']));
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
		* Carrega os grupo que pertencem somente ao usuario logado
		*/
		$contacts = $this->Contact->find('list', array(
			'fields' => array(
				'Contact.list',
				'Contact.title',
				),
			'conditions' => array(
				'Contact.user_id' => $this->Session->read('Auth.User.id')
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
		$this->set(compact('sms_templates', 'contacts', 'tel_type', 'layout', 'layout_checked'));
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
		$params['conditions']['Campaign.product'] = 'mailing';

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
		$params['conditions']['Campaign.product'] = 'sms';

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
		$params['conditions']['Campaign.product'] = 'checklist';

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
		$this->redirect_edit = 'edit_mailing';

		$this->edit($id);

		$this->view = 'edit_mailing';
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

		$this->view = 'edit_sms';
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
		$this->edit($id);

		$this->view = 'edit_checklist';
	}

	/**
	* Método cron_mailing
	* Este método é responsavel pela producao dos arquivos de retorno a partir da campanha de Mailing
	*
	* @param string $id
	* @return void
	*/
	public function cron_mailing($id){
		/**
		* Desabilita a renderizacao do cake
		*/
		$this->autoRender = false;

		/**
		* Variaveis q contera os retornos do cron
		*/
		$files = array(
			'txt' => '',
			'xls' => '',
			'info' => '',
			);

		/**
		* Carrega os dados da campanha
		*/
		$this->Campaign->recursive = -1;
		$data = $this->Campaign->findById($id);

		/**
		* Altera o status da campanha para PROCESSO EM ANDAMENTO
		*/
		$this->Campaign->id = $data['Campaign']['id'];
		$this->Campaign->saveField('status', CAMPAIGN_RUN_PROCESSED);

		/**
		* Carrega os dados do usuario que criou a campanha
		*/
		$this->Campaign->User->recursive = -1;
		$user = $this->Campaign->User->findById($data['Campaign']['user_id']);

		/**
		* Carrega todos os precos dos produtos de acordo o pacote do cliente
		*/
		$obj_users = new UsersController();
		$this->loadModel('Billing');
		$client = $obj_users->loadClient($data['Campaign']['client_id']);
		$prices = $obj_users->loadPrices();	

		/**
		* Carrega os IDs necessarios para cobranca
		*/
		$this->tp_search = TP_SEARCH_MAILING;
		$this->product_id = PRODUCT_MAILING;
		$this->user_name = $user['User']['given_name'];
		$this->user_id = $data['Campaign']['user_id'];
		$this->package_id = $client['Client']['package_id'];
		$this->contract_id = $client['Client']['contract_id'];
		$this->billing_id = $client['Client']['billing_id'];
		$this->price_id = $prices['prices_id'][$this->package_id][$this->product_id];
		$this->price = $prices['prices_val'][$this->package_id][$this->product_id];
		$this->balance = $this->Billing->balance($client['Client']['id']);
		$this->validity_orig = $client['Client']['validity_orig'];

		/**
		* Verifica se o cliente tem permissao/credito para continuar o processo
		*/
		if($this->security()){
			$files['info'] = $this->Session->read('Message.session_form.message');
		}else{
			/**
			* Carrega as entidades no atributo $this->entity a partir dos dados da campanha
			*/
			$this->loadEntities($data);
			$files['xls'] = $this->loadTable();
			$files['txt'] = $this->loadSemicolon();

			/**
			* Percorre por todos as entidades encontradas efetuando a cobrança
			*/
			foreach ($this->entity['Entity'] as $k => $v) {
	    		/**
	    		* Recarrega o cache de paginas cobradas
	    		*/
				$this->query = "/campaigns/mailing/campaign:{$id}/association_id:{$v['Association']['id']}";

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

		/**
		* Cria a pasta onde sera guardado os arquivos
		*/
		$dir = ROOT . "/app/webroot/files/campaign/mailing/{$client['Client']['id']}/{$id}/";
		if(!is_dir($dir)){
			mkdir($dir, 0777, true);
		}

		/**
		* Salva os arquivos gerados
		*/
		$file_name = Inflector::slug(strtolower($data['Campaign']['title']), '-') . '-' . date('YmdHi');
		file_put_contents("{$dir}/RELATORIO-{$file_name}.txt", $files['info']);			
		file_put_contents("{$dir}/{$file_name}.txt", $files['txt']);			
		file_put_contents("{$dir}/{$file_name}.xls", $files['xls']);

		/**
		* Dispara um email para o usuario que criou a campanha, avisando que os arquivos ja estao disponiveis
		*/
		$email = new CakeEmail('apps');
		$email->template($this->action);
		$email->emailFormat('html');
		$email->viewVars(array('user' => $hasEmail));

		$email->sender(array(EMAIL_NO_REPLAY => TITLE_APP));
		$email->from(array(EMAIL_NO_REPLAY => TITLE_APP));
		$email->to($hasEmail['User']['email']);
		$email->subject("Lembrete da senha nova de {$hasEmail['User']['given_name']}");
		$email->send();		

		/**
		* Altera o status da campanha para PROCESSADO
		*/
		$this->Campaign->saveField('status', CAMPAIGN_PROCESSED);
		$this->Campaign->saveField('date_process', date('Y-m-d H:i:s'));

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
		$this->validity_orig = $client['Client']['validity_orig'];

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
