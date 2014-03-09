<?php
App::uses('AppController', 'Controller');
/**
 * Application level Controller
 *
 * Area destinada a funcoes especificas do projeto, estas funcoes nao pertencem a framework
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppImportsController extends AppController {
	public $uses = array(
		"Import", 
		"Ientity",
		"Izipcode",
		"Iaddress",
		"Iassociation",
		"Settings",
		"Counter",
		// "NattMovelTelefone", 
		"NattFixoTelefone", 
		"NattFixoPessoa", 
		"NattFixoEndereco",
		"Ilandline",
		"NattMobile", 
		"Imobile",
		);

	public $components = array('AppImport');

	/**
	* Atributos da classe
	*/
	protected $db;
	protected $uf;
	protected $qt_reg = 0;
	protected $qt_imported = 0;	

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

		//Verifica se a acao foi chamada apartir da linha de comando
		if (!defined('CRON_DISPATCHER')) { 
			// $this->Session->setFlash("{$user['given_name']}, " . __('This page can not be executed on browser'), FLASH_TEMPLETE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
			// $this->redirect($this->Auth->loginRedirect); 
			// exit(); 
		} 

	    $this->layout = null;
	}

	/**
	* Método importEntity
	* Este método importa os dados da entidade
	*
	* @return void
	*/
	protected function importEntity($entity){
		/**
		* Variavel que guarda o status da importacao da entidade
		*/
		$hasCreated = false;

		/**
		* Verifica se o nome da entidade é valido
		*/
		if(empty($entity['Ientity']['name']) || empty($entity['Ientity']['h1'])){
			$this->AppImport->fail('entities');
		}else{
			/**
			* Inicializa o ID da entidade como null
			*/
			$this->Ientity->id = null;

			/**
			* Verifica se a entidade que sera importada já existe na base de dados
			*/
			$hasEntity = array();
			if(!empty($entity['Ientity']['doc'])){
				$hasEntity = $this->Ientity->find('first', array(
					'conditions' => array(
						'doc' => $entity['Ientity']['doc'],
						)
					));				
			}

			if(count($hasEntity)){
				$this->Ientity->id = $hasEntity['Ientity']['id'];
				$this->AppImport->success('entities');
				$hasCreated = true;
			}else{
				$this->Ientity->create();
				if($this->Ientity->save($entity)){
					$this->AppImport->success('entities');
					$hasCreated = true;
				}else{
					$this->AppImport->fail('entities');
					$this->AppImport->__log("Falha ao importar a entidade", IMPORT_ENTITY_FAIL, $this->uf, false, $this->Ientity->useTable, null, $entity['Ientity']['doc'], $this->db['entity']->error);
				}
			}	
		}


		return $hasCreated;
	}

	/**
	* Método importLandline
	* Este método importa os telefones relacionados a entidade
	*
	* @return void
	*/
	protected function importLandline($landline){
		/**
		* Inicializa o ID do telefone como null
		*/
		$this->Ilandline->id = null;

		/**
		* Aborta a insercao caso o telefone seja null (inconsistente)
		*/		
		if(!$landline['Ilandline']['tel']){
			$this->AppImport->fail('landlines');
			$this->AppImport->__log("Telefone inconsistente", IMPORT_LANDLINE_INCONSISTENT, $this->uf, false, $this->Ilandline->useTable, null, $landline['Ilandline']['tel_original']);
		}else{
			/**
			* Verifica se o telefone que sera importado já existe na base de dados
			*/
			$hasLandline = $this->Ilandline->find('first', array(
				'conditions' => array(
					'tel_full' => $landline['Ilandline']['tel_full'],
					)
				));		

			if(count($hasLandline)){				
				$this->Ilandline->id = $hasLandline['Ilandline']['id'];
				$this->AppImport->success('landlines');
			}else{
				$this->Ilandline->create();
				if($this->Ilandline->save($landline)){
					$this->AppImport->success('landlines');
				}else{					
					$this->AppImport->fail('landlines');
					$this->AppImport->__log("Falha ao importar o telefone.", IMPORT_LANDLINE_FAIL, $this->uf, false, $this->Ilandline->useTable, null, $landline['Ilandline']['tel_full'], $this->db['Ilandline']->error);
				}
			}	
		}
	}

	/**
	* Método importMobile
	* Este método importa os telefones relacionados a entidade
	*
	* @return void
	*/
	protected function importMobile($mobile){
		/**
		* Inicializa o ID do telefone como null
		*/
		$this->Imobile->id = null;

		/**
		* Aborta a insercao caso o telefone seja null (inconsistente)
		*/		
		if(!$mobile['Imobile']['tel']){
			$this->AppImport->fail('mobiles');
			$this->AppImport->__log("Celular inconsistente", IMPORT_LANDLINE_INCONSISTENT, $this->uf, false, $this->Imobile->useTable, null, $mobile['Imobile']['tel_original']);
		}else{
			/**
			* Verifica se o telefone que sera importado já existe na base de dados
			*/
			$hasMobile = $this->Imobile->find('first', array(
				'conditions' => array(
					'tel_full' => $mobile['Imobile']['tel_full'],
					)
				));		

			if(count($hasMobile)){
				$this->Imobile->id = $hasMobile['Imobile']['id'];
				$this->AppImport->success('mobiles');
			}else{
				$this->Imobile->create();
				if($this->Imobile->save($mobile)){
					$this->AppImport->success('mobiles');
				}else{
					$this->AppImport->fail('mobiles');
					$this->AppImport->__log("Falha ao importar o telefone.", IMPORT_LANDLINE_FAIL, $this->uf, false, $this->Imobile->useTable, null, $mobile['Imobile']['tel_full'], $this->db['Imobile']->error);
				}
			}	
		}
	}

	/**
	* Método importZipcode
	* Este método importa CEP relacionados ao telefone
	*
	* @return void
	*/
	protected function importZipcode($zipcode){
		/**
		* Inicializa o ID do CEP como null
		*/
		$this->Izipcode->id = null;

		/**
		* Aborta a insercao caso o CEP seja null (inconsistente)
		*/		
		if(!$zipcode['Izipcode']['code']){
			$this->AppImport->fail('zipcodes');
			$this->AppImport->__log("CEP inconsistente ou null", IMPORT_ZIPCODE_INCONSISTENT, $this->uf, false, $this->Izipcode->useTable, null, $zipcode['Izipcode']['code_original']);
		}else{
			/**
			* Verifica se o telefone que sera importado já existe na base de dados
			*/
			$hasZipcode = $this->Izipcode->find('first', array(
				'conditions' => array(
					'code' => $zipcode['Izipcode']['code'],
					)
				));		

			if(count($hasZipcode)){
				$this->Izipcode->id = $hasZipcode['Izipcode']['id'];
				$this->AppImport->success('zipcodes');
			}else{
				$this->Izipcode->create();
				if($this->Izipcode->save($zipcode)){
					$this->AppImport->success('zipcodes');
				}else{
					$this->AppImport->fail('zipcodes');
					$this->AppImport->__log("Falha ao importar o CEP.", IMPORT_ZIPCODE_FAIL, $this->uf, false, $this->Izipcode->useTable, null, $zipcode['Izipcode']['code_original'], $this->db['Izipcode']->error);
				}
			}	
		}
	}

	/**
	* Método importAddress
	* Este método importa Endereço relacionados ao telefone
	*
	* @return void
	*/
	protected function importAddress($address){
		/**
		* Inicializa o ID do endereco como null
		*/
		$this->Iaddress->id = null;

		/**
		* Verifica se o telefone que sera importado já existe na base de dados
		*/
		$hasAddress = $this->Iaddress->find('first', array(
			'conditions' => array(
				'zipcode_id' => $address['Iaddress']['zipcode_id'],
				'number' => $address['Iaddress']['number'],
				'h_complement' => $address['Iaddress']['h_complement'],
				)
			));		

		if(count($hasAddress)){
			$this->Iaddress->id = $hasAddress['Iaddress']['id'];
			$this->AppImport->success('addresses');
		}else{
			$this->Iaddress->create();
			if($this->Iaddress->save($address)){
				$this->AppImport->success('addresses');
			}else{
				$this->AppImport->fail('addresses');
				$this->AppImport->__log("Falha ao importar o endereço.", IMPORT_ADDRESS_FAIL, $this->uf, false, $this->Iaddress->useTable, null, $address['Iaddress']['state_id'], $this->db['Iaddress']->error);
			}
		}	
	}

	/**
	* Método importAssociation
	* Amarra os registros Entidade, Telefone, CEP e Endereço na tabela associations
	*
	* @return bool $hasCreated
	*/
	protected function importAssociation($association){
		/**
		* Inicializa o ID das juncoes como null
		*/
		$this->Iassociation->id = null;

		/**
		* Inicializa a variavel $asCreated com false
		*/
		$hasCreated = false;

		if(
			(!empty($association['Iassociation']['entity_id']) && $association['Iassociation']['entity_id'] != '0')
			&& 
			(
				(!empty($association['Iassociation']['landline_id']) && $association['Iassociation']['landline_id'] != '0') 
				|| 
				(!empty($association['Iassociation']['address_id']) && $association['Iassociation']['address_id'] != '0'))
			){

			/**
			* Verifica se a junção já existe
			*/
			$hasAssociation = $this->Iassociation->find('first', array(
				'conditions' => array(
					'entity_id' => $association['Iassociation']['entity_id'],
					'landline_id' => $association['Iassociation']['landline_id'],
					'mobile_id' => $association['Iassociation']['mobile_id'],
					'address_id' => $association['Iassociation']['address_id'],
					'year' => $association['Iassociation']['year'],
					)
				));	

		}


		if(isset($hasAssociation) && count($hasAssociation)){
			$this->Iassociation->id = $hasAssociation['Iassociation']['id'];
			$this->AppImport->success('associations');
		}else{
			$this->Iassociation->create();
			$hasCreated = $this->Iassociation->save($association); 
			if($hasCreated){
				$this->AppImport->success('associations');
			}else{
				$this->AppImport->fail('associations');
				$this->AppImport->__log("Falha ao importar os dados da tabela associations", IMPORT_ASSOCIATION_FAIL, $this->uf, false, $this->Iassociation->useTable, $this->Ientity->id);
			}
		}	

		return $hasCreated;
	}

}