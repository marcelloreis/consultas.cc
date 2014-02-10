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
	* Controller models
	*
	* @note OS MODELS SmsTemplate E CampaignList NAO FORAM LIGADOS COM belongsTo
	* 		PARA DAR LIBERDADE AO USUARIO DE SELECIONAR O Template OU O Grupo
	*		E ADITA-LOS APOS A SELECAO, ENTAO, OS MODELS CITADOS SÓ SERVEM DE REFERENCIA
	*		E NAO EXATAMENTE COMO UMA CHAVE EXTRANGEIRA
	* @var array
	*/
	public $uses = array('Campaign', 'SmsTemplate', 'CampaignList', 'Entity');

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
		* Inicializa a busca de entidades somente se for passado alguma area como parametro
		*/
		if(!empty($data['Campaign']['areas'])){
			/**
			* Contabiliza todos os registros de acordo com o filtro da campanha
			*/
			$limit = null;
			$cond = array();
			$joins = array();

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
			* Monta o join com a tabela de telefones moveis
			*/
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

	        /**
	        * Verifica se foi informado algum limite para a busca
	        */
			if(!empty($data['Campaign']['limit'])){
				$limit = $data['Campaign']['limit'];
			}

	        /**
	        * Monta a consulta com as areas(CEPs) informadas
	        */
			$zipcodes = preg_split('/\n/si', $data['Campaign']['areas']);
			foreach ($zipcodes as $k => $v) {
				if(!empty($v)){
					$cond['Zipcode.code'][] = trim(preg_replace('/[^0-9]/si', '', $v));
				}
			}
			$joins[] = array(
				'table' => 'addresses',
		        'alias' => 'Address',
		        'type' => 'INNER',
		        'conditions' => array(
		            'Address.id = Association.address_id',
		        )
	        );
			$joins[] = array(
				'table' => 'zipcodes',
		        'alias' => 'Zipcode',
		        'type' => 'INNER',
		        'conditions' => array(
		            'Zipcode.id = Address.zipcode_id',
		        )
	        );

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

			$this->entity['Entity'] = $this->Entity->find('all', array(
				'recursive' => -1,
				'fields' => array(
					'Entity.id',
					'Entity.name',
					'Entity.first_name',
					'Entity.type',
					'Entity.age',
					'Entity.birthday',
					'Entity.gender',
					'Entity.gender_str',
					'Mobile.id',
					'Mobile.tel_full',
					),
				'conditions' => $cond,
				'joins' => $joins,
				'order' => array('Association.year' => 'DESC'),
				'limit' => $limit
				)
			);
		}

		/**
		* Carrega os contatos contidos no campo contact_list no atributo $this->entity
		*/
		$this->loadContactList($data);
	}

	/**
	* Método loadContactList
	* Este método é responsavel pelo envio de SMSs para os contatos vinculados a campanha passada por parametro
	*
	* @param string $id
	* @return void
	*/
	private function loadContactList($data){	
		/**
		* Concatena os contatos informados manualmente da campanha
		*/
		if(!empty($data['Campaign']['contact_list'])){
			$contact_list = preg_split('/\n/si', $data['Campaign']['contact_list']);

			foreach ($contact_list as $k => $v) {
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
			* Carrega as entidades econtradas a partir dos dados da campanha no atributo $this->entity
			*/
			$this->loadEntities($this->request->data);

			foreach ($this->entity['Entity'] as $k => $v) {
				/**
				* Contabiliza quantos registros foram encontrados
				*/
				$this->request->data['Campaign']['people']++;

				/**
				* Contabiliza quantas mulheres/homens foram encontradas
				*/
				switch ($v['Entity']['gender']) {
					case FEMALE:
						$this->request->data['Campaign']['female']++;
						break;
					case MALE:
						$this->request->data['Campaign']['male']++;
						break;
				}

				/**
				* Contabiliza quantas pessoas fisicas/juridicas foram encontradas
				*/
				switch ($v['Entity']['type']) {
					case TP_CPF:
						$this->request->data['Campaign']['individual']++;
						break;
					case TP_CNPJ:
						$this->request->data['Campaign']['corporation']++;
						break;
				}
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
		$campaign_list = $this->CampaignList->find('list', array(
			'fields' => array(
				'CampaignList.list',
				'CampaignList.title',
				),
			'conditions' => array(
				'CampaignList.user_id' => $this->Session->read('Auth.User.id')
				)
			));

		$this->set(compact('sms_templates', 'campaign_list'));
	}

	/**
	* Método deactivate
	* Este método desativa a campanha para o envio de SMSs
	*
	* @param string $id
	* @return void
	*/
	public function deactivate($id){
		/**
		* Carrega o id informado para o objeto
		*/
		$this->Campaign->id = $id;

		/**
		* Verifica se existe a campanha solicitada
		*/
		if (!$this->Campaign->exists()) {
			$this->Session->setFlash('Não foi possível encontrar a campanha informada.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
		}else{
			/**
			* Ativa a campanha
			*/
			$this->Campaign->saveField('status', false);
			$this->Session->setFlash('A campanha foi desativada com sucesso.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}

		/**
		* Retorna para a pagina de onde veio a requisicao
		*/
		$this->redirect(array('action' => 'edit', $id));
	}

	/**
	* Método active
	* Este método ativa a campanha para o envio de SMSs
	*
	* @param string $id
	* @return void
	*/
	public function activate($id){
		/**
		* Carrega o id informado para o objeto
		*/
		$this->Campaign->id = $id;

		/**
		* Verifica se existe a campanha solicitada
		*/
		if (!$this->Campaign->exists()) {
			$this->Session->setFlash('Não foi possível encontrar a campanha informada.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
		}else{
			/**
			* Ativa a campanha
			*/
			$this->Campaign->saveField('status', true);
			$this->Session->setFlash('A campanha foi ativada com sucesso.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		}

		/**
		* Retorna para a pagina de onde veio a requisicao
		*/
		$this->redirect(array('action' => 'edit', $id));
	}

	/**
	* Método send
	* Este método é responsavel pelo envio de SMSs para os contatos vinculados a campanha passada por parametro
	*
	* @param string $id
	* @return void
	*/
	public function send($id){
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
