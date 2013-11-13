<?php
/**
 * Import content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/LandlinesImport/
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Controller
 */

App::uses('AppController', 'Controller');

/**
 * Import content controller
 *
 * Este controlador contem regras de negócio aplicadas ao model State
 *
 * @package       app.Controller
 * @link http://.framework.nasza.com.br/2.0/controller/LandlinesImport.html
 */
class LandlinesImportController extends AppController {
	public $uses = array(
		"Import", 
		"NattFixoTelefone", 
		"NattFixoPessoa", 
		"NattFixoEndereco",
		"Ilandline",
		"Ientity",
		"Izipcode",
		"Iaddress",
		"Iassociation",
		"Settings",
		"Counter"
		);

	public $components = array('AppImport');

	/**
	* Atributos da classe
	*/
	private $db;
	private $uf;
	private $telefones_uf;
	private $pessoa_uf;
	private $endereco_uf;
	private $qt_reg = 0;
	private $qt_imported = 0;

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

	    $this->layout=null;
	}	

	/**
	* Método run
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function build_source($uf=null){
		/**
		* Verifica se foi passado algum estado por parametro
		*/
		if($uf){
			$this->uf = strtoupper($uf);
			
			/**
			* Carrega as tabelas do estado que sera importado
			*/
			$this->telefones_uf = "TELEFONES_{$this->uf}";
			$this->pessoa_uf = "PESSOA_{$this->uf}";
			$this->endereco_uf = "ENDERECO_{$this->uf}";

			/**
			* Carrega os models com o nome das tabelas
			*/
			$this->NattFixoTelefone->useTable = $this->telefones_uf;
			$this->NattFixoPessoa->useTable = $this->pessoa_uf;
			$this->NattFixoEndereco->useTable = $this->endereco_uf;

			
			$this->qt_reg = $this->NattFixoPessoa->find('count', array('conditions' => array('CPF_CNPJ !=' => '00000000000000000000')));

			$this->NattFixoPessoa->buildSource($this->uf, $this->qt_reg);
		}
	}

	/**
	* Método run
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function run($uf=null){
		/**
		* Verifica se foi passado algum estado por parametro
		*/
		if($uf){
			$this->uf = strtoupper($uf);
			
			/**
			* Carrega as tabelas do estado que sera importado
			*/
			$this->telefones_uf = "TELEFONES_{$this->uf}";
			$this->pessoa_uf = "PESSOA_{$this->uf}";
			$this->endereco_uf = "ENDERECO_{$this->uf}";

			/**
			* Carrega os models com o nome das tabelas
			*/
			$this->NattFixoTelefone->useTable = $this->telefones_uf;
			$this->NattFixoPessoa->useTable = $this->pessoa_uf;
			$this->NattFixoEndereco->useTable = $this->endereco_uf;

			/**
			* Calcula o total de registros que sera importado de cada tabela
			*/
			$start_time = time();
			$this->qt_reg = $this->NattFixoPessoa->find('count', array('conditions' => array('CPF_CNPJ !=' => '00000000000000000000')));
			$this->Counter->updateAll(array('Counter.extracted' => $this->qt_reg, 'Counter.start_time' => $start_time), array('table' => 'entities', 'active' => '1'));

			/**
			* Inicia o processo de importacao
			*/
			$this->AppImport->__log("Importacao Iniciada.", IMPORT_BEGIN, $this->uf);

			/**
			* Inicializa a transacao das tabelas
			*/
			$this->db['entity'] = $this->Ientity->getDataSource();
			$this->db['landline'] = $this->Ilandline->getDataSource();
			$this->db['address'] = $this->Ilandline->getDataSource();
			$this->db['zipcode'] = $this->Ilandline->getDataSource();
			$this->db['association'] = $this->Iassociation->getDataSource();

			for ($i=0; $i < $this->qt_reg; $i++) { 
				/**
				* Verifica se a chave do modulo de importacao esta ativa
				*/
				$this->AppImport->timing_ini(TUNING_ON_OF);
				if(!$this->Settings->active($this->name)){
					$this->AppImport->__log("Importacao Pausada.", IMPORT_PAUSED, $this->uf);
					die;
				}
				$this->AppImport->timing_end();

				/**
				* Carrega o proximo registro das tabelas de pessoa, telefone e endereco q ainda nao foram importado
				*/
				$this->AppImport->timing_ini(TUNING_LOAD_NEXT_REGISTER);
				$entity = $this->NattFixoPessoa->next();
				$this->AppImport->timing_end();

				if(isset($entity['pessoa'])){
					/**
					* Inicialiaza a transacao
					*/
					$this->db['entity']->begin();

					/**
					* Gera o hash do nome da entidade
					*/
					$hash = $this->AppImport->getHash($this->AppImport->clearName($entity['pessoa']['NOME_RAZAO']));

					/**
					* Trata os dados da entidade para a importacao
					*/
					//Carrega o tipo de documento
					$doc_type = $this->AppImport->getTypeDoc($entity['pessoa']['CPF_CNPJ'], $this->AppImport->clearName($entity['pessoa']['NOME_RAZAO']), $this->AppImport->clearName($entity['pessoa']['MAE']), $this->AppImport->getBirthday($entity['pessoa']['DT_NASCIMENTO']));
					$this->AppImport->timing_ini(TUNING_ENTITY_LOAD);
					$data = array(
						'Ientity' => array(
							'doc' => $entity['pessoa']['CPF_CNPJ'],
							'name' => $this->AppImport->clearName($entity['pessoa']['NOME_RAZAO']),
							'mother' => $this->AppImport->clearName($entity['pessoa']['MAE']),
							'type' => $doc_type,
							'gender' => $this->AppImport->getGender($entity['pessoa']['SEXO'], $doc_type, $entity['pessoa']['NOME_RAZAO']),
							'birthday' => $this->AppImport->getBirthday($entity['pessoa']['DT_NASCIMENTO']),
							'h1' => $hash['h1'],
							'h2' => $hash['h2'],
							'h3' => $hash['h3'],
							'h4' => $hash['h4'],
							'h5' => $hash['h5'],
							'h_all' => $hash['h_all'],
							'h_first_last' => $hash['h_first_last'],
							'h_last' => $hash['h_last'],
							'h_first1_first2' => $hash['h_first1_first2'],
							'h_last1_last2' => $hash['h_last1_last2'],
							'h_mother' => $this->AppImport->getHash($entity['pessoa']['MAE'], 'h_all'),
							)
						);
					$this->AppImport->timing_end();

					/**
					* Executa a importacao da tabela Entity
					* e carrega o id da entidade importada
					*/
					$this->AppImport->timing_ini(TUNING_ENTITY_IMPORT);
					$this->importEntity($data);
					$this->AppImport->timing_end();


					/**
					* Exibe o status da importacao no console 
					*/
					$this->qt_imported++;
					// $this->AppImport->progressBar($this->qt_imported, $this->qt_reg, $this->uf);

					/**
					* Inicializa a importacao dos telefones da entidade encontrada
					*/
					if(isset($entity['telefone'])){
						foreach ($entity['telefone'] as $k => $v) {
							/**
							* Inicializa a transacao
							*/
							$this->db['landline']->begin();
							$this->db['address']->begin();
							$this->db['zipcode']->begin();
							$this->db['association']->begin();

							/**
							* Desmembra o DDD do Telefone
							*/
							$this->AppImport->timing_ini(TUNING_LANDLINE_LOAD);
							$ddd_telefone = $v['TELEFONE'];
							$ddd = $this->AppImport->getDDD($v['TELEFONE']);
							$telefone = $this->AppImport->getTelefone($v['TELEFONE']);
						
							/**
							* Extrai o ano de atualizacao do telefone
							*/
							$year = $this->AppImport->getUpdated($v['DATA_ATUALIZACAO']);

							/**
							* Trata os dados o telefone para a importacao
							*/
							$data = array(
								'Ilandline' => array(
									'year' => $year,
									'ddd' => $ddd,
									'tel' => $telefone,
									'tel_full' => "{$ddd}{$telefone}",
									'tel_original' => $v['TELEFONE'],
									)
								);
							$this->AppImport->timing_end();
							
							/**
							* Executa a importacao do telefone
							* e carrega o id do telefone importado
							*/
							$this->AppImport->timing_ini(TUNING_LANDLINE_IMPORT);
							$this->importLandline($data, $v['TELEFONE']);
							$this->AppImport->timing_end();


							/**
							* Inicializa a importacao dos telefones da entidade encontrada
							*/
							if(isset($v['endereco'])){
								/**
								* Inicializa a importacao do CEP do telefone encontrado
								* Trata os dados do CEP para a importacao
								*/				
								$this->AppImport->timing_ini(TUNING_ZIPCODE_LOAD);		
								$data = array(
									'Izipcode' => array(
										'code' => $this->AppImport->getZipcode($v['endereco']['CEP']),
										'code_original' => $v['endereco']['CEP']
										)
									);
								$this->AppImport->timing_end();

								/**
								* Executa a importacao do CEP
								* e carrega o id do CEP importado
								*/
								$this->AppImport->timing_ini(TUNING_ZIPCODE_IMPORT);
								$this->importZipcode($data);
								$this->AppImport->timing_end();

								/**
								* Inicializa a importacao do endereco do telefone encontrado
								* Trata os dados do endereço para a importacao
								*/	
								$this->AppImport->timing_ini(TUNING_ADDRESS_LOAD);
								
								$state_id = $this->AppImport->getState($v['endereco']['UF'], $this->uf);
								$city_id = $this->AppImport->getCityId($v['endereco']['CIDADE'], $state_id, $this->Izipcode->id);
								$city = $this->AppImport->getCity($v['endereco']['CIDADE']);
								$zipcode = $this->AppImport->getZipcode($v['endereco']['CEP']);
								$number = $this->AppImport->getStreetNumber($v['NUMERO'], $v['endereco']['NOME_RUA']);

								/**
								* Trata o nome da rua
								*/
								$street = $this->AppImport->getStreet($v['endereco']['NOME_RUA']);

								/**
								* Gera o hash do nome da rua
								*/
								$hash = $this->AppImport->getHash($street);

								/**
								* Gera o hash do complemento da rua
								*/
								$hash_complement = $this->AppImport->getHash($this->AppImport->getComplement($v['COMPLEMENTO']), null, false);

								/**
								* Carrega um array com todos os estados
								*/
								$map_states = $this->AppImport->loadStates(true);

								$data = array(
									'Iaddress' => array(
										'state_id' => $state_id,
										'zipcode_id' => $this->Izipcode->id,
										'city_id' => $city_id,
										'state' => $map_states[$state_id],
										'zipcode' => $zipcode,
										'city' => $city,
										'type_address' => $this->AppImport->getTypeAddress($v['endereco']['RUA'], $v['endereco']['NOME_RUA']),
										'street' => $street,
										'number' => $number,
										'neighborhood' => $this->AppImport->getNeighborhood($v['endereco']['BAIRRO']),
										'complement' => $this->AppImport->getComplement($v['COMPLEMENTO']),
										'h1' => $hash['h1'],
										'h2' => $hash['h2'],
										'h3' => $hash['h3'],
										'h4' => $hash['h4'],
										'h5' => $hash['h5'],
										'h_all' => $hash['h_all'],
										'h_first_last' => $hash['h_first_last'],
										'h_last' => $hash['h_last'],
										'h_first1_first2' => $hash['h_first1_first2'],
										'h_last1_last2' => $hash['h_last1_last2'],
										'h_complement' => $hash_complement['h_all'],
										)
									);
								$this->AppImport->timing_end();

								/**
								* Executa a importacao do Endereço
								* e carrega o id do Endereço importado
								*/
								$this->AppImport->timing_ini(TUNING_ADDRESS_IMPORT);
								$this->importAddress($data);
								$this->AppImport->timing_end();
								
							}

							/**
							* Amarra os registros Entidade, Telefone, CEP e Endereço na tabela associations
							*/

							/**
							* Carrega todos os id coletados ate o momento
							*/
							$this->AppImport->timing_ini(TUNING_LOAD_ALL_DATA);
							$data = array(
								'Iassociation' => array(
									'entity_id' => $this->Ientity->id,
									'landline_id' => $this->Ilandline->id,
									'address_id' => $this->Iaddress->id,
									'year' => $year,
									)
								);
							$this->AppImport->timing_end();
							
							$this->AppImport->timing_ini(TUNING_IMPORT_ALL_DATA);
							if($this->importAssociation($data)){
								/**
								* Registra todas as transacoes
								*/
								$this->db['landline']->commit();
								$this->db['address']->commit();
								$this->db['zipcode']->commit();
								$this->db['association']->commit();
							}else{
								/**
								* Aborta todas as transacoes relacionadas a entidade
								*/
								$this->db['landline']->rollback();
								$this->db['address']->rollback();
								$this->db['zipcode']->rollback();
								$this->db['association']->rollback();							
							}
							$this->AppImport->timing_end();

							/**
							* Salva as contabilizacoes na base de dados
							*/					
							$this->AppImport->__counter('entities');
							$this->AppImport->__counter('landlines');
							$this->AppImport->__counter('addresses');
							$this->AppImport->__counter('zipcodes');
							$this->AppImport->__counter('associations');	
						}
					}

					/**
					* Salva as contabilizacoes na base de dados
					*/					
					$this->AppImport->__counter('entities');

					/**
					* Finaliza todas as transacoes
					*/
					$this->db['entity']->commit();					
				}else{
					$this->AppImport->fail('entities');
				}
			}

			/**
			* Finaliza o processo de importacao
			*/
			exit();
		}
	}

	/**
	* Método run
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function run_block($uf=null){
		/**
		* Verifica se foi passado algum estado por parametro
		*/
		if($uf){
			$this->uf = strtoupper($uf);
			
			/**
			* Carrega as tabelas do estado que sera importado
			*/
			$this->telefones_uf = "TELEFONES_{$this->uf}";
			$this->pessoa_uf = "PESSOA_{$this->uf}";
			$this->endereco_uf = "ENDERECO_{$this->uf}";

			/**
			* Carrega os models com o nome das tabelas
			*/
			$this->NattFixoTelefone->useTable = $this->telefones_uf;
			$this->NattFixoPessoa->useTable = $this->pessoa_uf;
			$this->NattFixoEndereco->useTable = $this->endereco_uf;

			/**
			* Calcula o total de registros que sera importado de cada tabela
			*/
			$start_time = time();
			$this->qt_reg = $this->NattFixoPessoa->find('count', array('conditions' => array('CPF_CNPJ !=' => '00000000000000000000')));
			$this->Counter->updateAll(array('Counter.extracted' => $this->qt_reg, 'Counter.start_time' => $start_time), array('table' => 'entities', 'active' => '1'));

			/**
			* Inicia o processo de importacao
			*/
			$this->AppImport->__log("Importacao Iniciada.", IMPORT_BEGIN, $this->uf);

			/**
			* Inicializa a transacao das tabelas
			*/
			$this->db['entity'] = $this->Ientity->getDataSource();
			$this->db['landline'] = $this->Ilandline->getDataSource();
			$this->db['address'] = $this->Ilandline->getDataSource();
			$this->db['zipcode'] = $this->Ilandline->getDataSource();
			$this->db['association'] = $this->Iassociation->getDataSource();

			for ($i=0; $i < $this->qt_reg; $i+=LIMIT_BUILD_SOURCE) { 
				/**
				* Carrega o proximo registro das tabelas de pessoa, telefone e endereco q ainda nao foram importado
				*/
				$this->AppImport->timing_ini(TUNING_LOAD_NEXT_REGISTER);
				$entity_block = $this->NattFixoPessoa->next_block($i, LIMIT_BUILD_SOURCE);
				$this->AppImport->timing_end();

				foreach ($entity_block as $k => $v) {	
					/**
					* Verifica se a chave do modulo de importacao esta ativa
					*/
					$this->AppImport->timing_ini(TUNING_ON_OF);
					if(!$this->Settings->active($this->name)){
						$this->AppImport->__log("Importacao Pausada.", IMPORT_PAUSED, $this->uf);
						die;
					}
					$this->AppImport->timing_end();

					if(isset($v['pessoa'])){
						/**
						* Gera o hash do nome da entidade
						*/
						$hash = $this->AppImport->getHash($this->AppImport->clearName($v['pessoa']['NOME_RAZAO']));

						/**
						* Trata os dados da entidade para a importacao
						*/
						//Carrega o tipo de documento
						$doc_type = $this->AppImport->getTypeDoc($v['pessoa']['CPF_CNPJ'], $this->AppImport->clearName($v['pessoa']['NOME_RAZAO']), $this->AppImport->clearName($v['pessoa']['MAE']), $this->AppImport->getBirthday($v['pessoa']['DT_NASCIMENTO']));
						$this->AppImport->timing_ini(TUNING_ENTITY_LOAD);
						$data = array(
							'Ientity' => array(
								'doc' => $v['pessoa']['CPF_CNPJ'],
								'name' => $this->AppImport->clearName($v['pessoa']['NOME_RAZAO']),
								'mother' => $this->AppImport->clearName($v['pessoa']['MAE']),
								'type' => $doc_type,
								'gender' => $this->AppImport->getGender($v['pessoa']['SEXO'], $doc_type, $v['pessoa']['NOME_RAZAO']),
								'birthday' => $this->AppImport->getBirthday($v['pessoa']['DT_NASCIMENTO']),
								'h1' => $hash['h1'],
								'h2' => $hash['h2'],
								'h3' => $hash['h3'],
								'h4' => $hash['h4'],
								'h5' => $hash['h5'],
								'h_all' => $hash['h_all'],
								'h_first_last' => $hash['h_first_last'],
								'h_last' => $hash['h_last'],
								'h_first1_first2' => $hash['h_first1_first2'],
								'h_last1_last2' => $hash['h_last1_last2'],
								'h_mother' => $this->AppImport->getHash($v['pessoa']['MAE'], 'h_all'),
								)
							);
						$this->AppImport->timing_end();

						/**
						* Executa a importacao da tabela Entity
						* e carrega o id da entidade importada
						*/
						$this->AppImport->timing_ini(TUNING_ENTITY_IMPORT);
						$this->importEntity($data);
						$this->AppImport->timing_end();


						/**
						* Exibe o status da importacao no console 
						*/
						// $this->qt_imported++;
						// $this->AppImport->progressBar($this->qt_imported, $this->qt_reg, $this->uf);

						/**
						* Inicializa a importacao dos telefones da entidade encontrada
						*/
						if(isset($v['telefone'])){
							foreach ($v['telefone'] as $v2) {
								/**
								* Desmembra o DDD do Telefone
								*/
								$this->AppImport->timing_ini(TUNING_LANDLINE_LOAD);
								$ddd_telefone = $v2['TELEFONE'];
								$ddd = $this->AppImport->getDDD($v2['TELEFONE']);
								$telefone = $this->AppImport->getTelefone($v2['TELEFONE']);
							
								/**
								* Extrai o ano de atualizacao do telefone
								*/
								$year = $this->AppImport->getUpdated($v2['DATA_ATUALIZACAO']);

								/**
								* Trata os dados o telefone para a importacao
								*/
								$data = array(
									'Ilandline' => array(
										'year' => $year,
										'ddd' => $ddd,
										'tel' => $telefone,
										'tel_full' => "{$ddd}{$telefone}",
										'tel_original' => $v2['TELEFONE'],
										)
									);
								$this->AppImport->timing_end();
								
								/**
								* Executa a importacao do telefone
								* e carrega o id do telefone importado
								*/
								$this->AppImport->timing_ini(TUNING_LANDLINE_IMPORT);
								$this->importLandline($data, $v2['TELEFONE']);
								$this->AppImport->timing_end();


								/**
								* Inicializa a importacao dos telefones da entidade encontrada
								*/
								if(isset($v2['endereco'])){
									/**
									* Inicializa a importacao do CEP do telefone encontrado
									* Trata os dados do CEP para a importacao
									*/				
									$this->AppImport->timing_ini(TUNING_ZIPCODE_LOAD);		
									$data = array(
										'Izipcode' => array(
											'code' => $this->AppImport->getZipcode($v2['endereco']['CEP']),
											'code_original' => $v2['endereco']['CEP']
											)
										);
									$this->AppImport->timing_end();

									/**
									* Executa a importacao do CEP
									* e carrega o id do CEP importado
									*/
									$this->AppImport->timing_ini(TUNING_ZIPCODE_IMPORT);
									$this->importZipcode($data);
									$this->AppImport->timing_end();

									/**
									* Inicializa a importacao do endereco do telefone encontrado
									* Trata os dados do endereço para a importacao
									*/	
									$this->AppImport->timing_ini(TUNING_ADDRESS_LOAD);
									
									$state_id = $this->AppImport->getState($v2['endereco']['UF'], $this->uf);
									$city_id = $this->AppImport->getCityId($v2['endereco']['CIDADE'], $state_id, $this->Izipcode->id);
									$city = $this->AppImport->getCity($v2['endereco']['CIDADE']);
									$zipcode = $this->AppImport->getZipcode($v2['endereco']['CEP']);
									$number = $this->AppImport->getStreetNumber($v2['NUMERO'], $v2['endereco']['NOME_RUA']);

									/**
									* Trata o nome da rua
									*/
									$street = $this->AppImport->getStreet($v2['endereco']['NOME_RUA']);

									/**
									* Gera o hash do nome da rua
									*/
									$hash = $this->AppImport->getHash($street);

									/**
									* Gera o hash do complemento da rua
									*/
									$hash_complement = $this->AppImport->getHash($this->AppImport->getComplement($v2['COMPLEMENTO']), null, false);

									/**
									* Carrega um array com todos os estados
									*/
									$map_states = $this->AppImport->loadStates(true);

									$data = array(
										'Iaddress' => array(
											'state_id' => $state_id,
											'zipcode_id' => $this->Izipcode->id,
											'city_id' => $city_id,
											'state' => $map_states[$state_id],
											'zipcode' => $zipcode,
											'city' => $city,
											'type_address' => $this->AppImport->getTypeAddress($v2['endereco']['RUA'], $v2['endereco']['NOME_RUA']),
											'street' => $street,
											'number' => $number,
											'neighborhood' => $this->AppImport->getNeighborhood($v2['endereco']['BAIRRO']),
											'complement' => $this->AppImport->getComplement($v2['COMPLEMENTO']),
											'h1' => $hash['h1'],
											'h2' => $hash['h2'],
											'h3' => $hash['h3'],
											'h4' => $hash['h4'],
											'h5' => $hash['h5'],
											'h_all' => $hash['h_all'],
											'h_first_last' => $hash['h_first_last'],
											'h_last' => $hash['h_last'],
											'h_first1_first2' => $hash['h_first1_first2'],
											'h_last1_last2' => $hash['h_last1_last2'],
											'h_complement' => $hash_complement['h_all'],
											)
										);
									$this->AppImport->timing_end();

									/**
									* Executa a importacao do Endereço
									* e carrega o id do Endereço importado
									*/
									$this->AppImport->timing_ini(TUNING_ADDRESS_IMPORT);
									$this->importAddress($data);
									$this->AppImport->timing_end();
									
								}

								/**
								* Amarra os registros Entidade, Telefone, CEP e Endereço na tabela associations
								*/

								/**
								* Carrega todos os id coletados ate o momento
								*/
								$this->AppImport->timing_ini(TUNING_LOAD_ALL_DATA);
								$data = array(
									'Iassociation' => array(
										'entity_id' => $this->Ientity->id,
										'landline_id' => $this->Ilandline->id,
										'address_id' => $this->Iaddress->id,
										'year' => $year,
										)
									);
								$this->AppImport->timing_end();
								
								$this->AppImport->timing_ini(TUNING_IMPORT_ALL_DATA);
								$this->importAssociation($data);
								$this->AppImport->timing_end();

								/**
								* Salva as contabilizacoes na base de dados
								*/					
								$this->AppImport->__counter('entities');
								$this->AppImport->__counter('landlines');
								$this->AppImport->__counter('addresses');
								$this->AppImport->__counter('zipcodes');
								$this->AppImport->__counter('associations');	
							}
						}

						/**
						* Salva as contabilizacoes na base de dados
						*/					
						$this->AppImport->__counter('entities');
					}else{
						$this->AppImport->fail('entities');
					}
				}
			}

			/**
			* Finaliza o processo de importacao
			*/
			exit();
		}
	}

	/**
	* Método run_json
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function run_json($uf=null){
		/**
		* Verifica se foi passado algum estado por parametro
		*/
		if($uf){
			$this->uf = strtoupper($uf);
			
			/**
			* Carrega as tabelas do estado que sera importado
			*/
			$this->telefones_uf = "TELEFONES_{$this->uf}";
			$this->pessoa_uf = "_source_" . strtolower($this->uf);
			$this->endereco_uf = "ENDERECO_{$this->uf}";

			/**
			* Carrega os models com o nome das tabelas
			*/
			$this->NattFixoTelefone->useTable = $this->telefones_uf;
			$this->NattFixoPessoa->useTable = $this->pessoa_uf;
			$this->NattFixoEndereco->useTable = $this->endereco_uf;

			/**
			* Calcula o total de registros que sera importado de cada tabela
			*/
			$start_time = time();
			$this->qt_reg = $this->NattFixoPessoa->find('count');
			$this->Counter->updateAll(array('Counter.extracted' => $this->qt_reg, 'Counter.start_time' => $start_time), array('table' => 'entities', 'active' => '1'));

			/**
			* Inicia o processo de importacao
			*/
			$this->AppImport->__log("Importacao Iniciada.", IMPORT_BEGIN, $this->uf);

			/**
			* Inicializa a transacao das tabelas
			*/
			$this->db['entity'] = $this->Ientity->getDataSource();
			$this->db['landline'] = $this->Ilandline->getDataSource();
			$this->db['address'] = $this->Ilandline->getDataSource();
			$this->db['zipcode'] = $this->Ilandline->getDataSource();
			$this->db['association'] = $this->Iassociation->getDataSource();

			for ($i=0; $i < $this->qt_reg; $i++) { 
				/**
				* Verifica se a chave do modulo de importacao esta ativa
				*/
				$this->AppImport->timing_ini(TUNING_ON_OF);
				if(!$this->Settings->active($this->name)){
					$this->AppImport->__log("Importacao Pausada.", IMPORT_PAUSED, $this->uf);
					die;
				}
				$this->AppImport->timing_end();

				/**
				* Carrega o proximo registro das tabelas de pessoa, telefone e endereco q ainda nao foram importado
				*/
				$this->AppImport->timing_ini(TUNING_LOAD_NEXT_REGISTER);
				$entity = $this->NattFixoPessoa->next_json();
				$this->AppImport->timing_end();

				if(isset($entity->pessoa)){
					/**
					* Inicialiaza a transacao
					*/
					$this->db['entity']->begin();

					/**
					* Gera o hash do nome da entidade
					*/
					$hash = $this->AppImport->getHash($this->AppImport->clearName($entity->pessoa->NOME_RAZAO));

					/**
					* Trata os dados da entidade para a importacao
					*/
					//Carrega o tipo de documento
					$doc_type = $this->AppImport->getTypeDoc($entity->pessoa->CPF_CNPJ, $this->AppImport->clearName($entity->pessoa->NOME_RAZAO), $this->AppImport->clearName($entity->pessoa->MAE), $this->AppImport->getBirthday($entity->pessoa->DT_NASCIMENTO));
					$this->AppImport->timing_ini(TUNING_ENTITY_LOAD);
					$data = array(
						'Ientity' => array(
							'doc' => $entity->pessoa->CPF_CNPJ,
							'name' => $this->AppImport->clearName($entity->pessoa->NOME_RAZAO),
							'mother' => $this->AppImport->clearName($entity->pessoa->MAE),
							'type' => $doc_type,
							'gender' => $this->AppImport->getGender($entity->pessoa->SEXO, $doc_type, $entity->pessoa->NOME_RAZAO),
							'birthday' => $this->AppImport->getBirthday($entity->pessoa->DT_NASCIMENTO),
							'h1' => $hash['h1'],
							'h2' => $hash['h2'],
							'h3' => $hash['h3'],
							'h4' => $hash['h4'],
							'h5' => $hash['h5'],
							'h_all' => $hash['h_all'],
							'h_first_last' => $hash['h_first_last'],
							'h_last' => $hash['h_last'],
							'h_first1_first2' => $hash['h_first1_first2'],
							'h_last1_last2' => $hash['h_last1_last2'],
							'h_mother' => $this->AppImport->getHash($entity->pessoa->MAE, 'h_all'),
							)
						);
					$this->AppImport->timing_end();

					/**
					* Executa a importacao da tabela Entity
					* e carrega o id da entidade importada
					*/
					$this->AppImport->timing_ini(TUNING_ENTITY_IMPORT);
					$this->importEntity($data);
					$this->AppImport->timing_end();


					/**
					* Exibe o status da importacao no console 
					*/
					$this->qt_imported++;
					// $this->AppImport->progressBar($this->qt_imported, $this->qt_reg, $this->uf);

					/**
					* Inicializa a importacao dos telefones da entidade encontrada
					*/
					if(isset($entity->telefone)){
						foreach ($entity->telefone as $v) {
							/**
							* Inicializa a transacao
							*/
							$this->db['landline']->begin();
							$this->db['address']->begin();
							$this->db['zipcode']->begin();
							$this->db['association']->begin();

							/**
							* Desmembra o DDD do Telefone
							*/
							$this->AppImport->timing_ini(TUNING_LANDLINE_LOAD);
							$ddd_telefone = $v->TELEFONE;
							$ddd = $this->AppImport->getDDD($v->TELEFONE);
							$telefone = $this->AppImport->getTelefone($v->TELEFONE);
						
							/**
							* Extrai o ano de atualizacao do telefone
							*/
							$year = $this->AppImport->getUpdated($v->DATA_ATUALIZACAO);

							/**
							* Trata os dados o telefone para a importacao
							*/
							$data = array(
								'Ilandline' => array(
									'year' => $year,
									'ddd' => $ddd,
									'tel' => $telefone,
									'tel_full' => "{$ddd}{$telefone}",
									'tel_original' => $v->TELEFONE,
									)
								);
							$this->AppImport->timing_end();
							
							/**
							* Executa a importacao do telefone
							* e carrega o id do telefone importado
							*/
							$this->AppImport->timing_ini(TUNING_LANDLINE_IMPORT);
							$this->importLandline($data, $v->TELEFONE);
							$this->AppImport->timing_end();


							/**
							* Inicializa a importacao dos telefones da entidade encontrada
							*/
							if(isset($v->endereco)){
								/**
								* Inicializa a importacao do CEP do telefone encontrado
								* Trata os dados do CEP para a importacao
								*/				
								$this->AppImport->timing_ini(TUNING_ZIPCODE_LOAD);		
								$data = array(
									'Izipcode' => array(
										'code' => $this->AppImport->getZipcode($v->endereco->CEP),
										'code_original' => $v->endereco->CEP
										)
									);
								$this->AppImport->timing_end();

								/**
								* Executa a importacao do CEP
								* e carrega o id do CEP importado
								*/
								$this->AppImport->timing_ini(TUNING_ZIPCODE_IMPORT);
								$this->importZipcode($data);
								$this->AppImport->timing_end();

								/**
								* Inicializa a importacao do endereco do telefone encontrado
								* Trata os dados do endereço para a importacao
								*/	
								$this->AppImport->timing_ini(TUNING_ADDRESS_LOAD);
								
								$state_id = $this->AppImport->getState($v->endereco->UF, $this->uf);
								$city_id = $this->AppImport->getCityId($v->endereco->CIDADE, $state_id, $this->Izipcode->id);
								$city = $this->AppImport->getCity($v->endereco->CIDADE);
								$zipcode = $this->AppImport->getZipcode($v->endereco->CEP);
								$number = $this->AppImport->getStreetNumber($v->NUMERO, $v->endereco->NOME_RUA);

								/**
								* Trata o nome da rua
								*/
								$street = $this->AppImport->getStreet($v->endereco->NOME_RUA);

								/**
								* Gera o hash do nome da rua
								*/
								$hash = $this->AppImport->getHash($street);

								/**
								* Gera o hash do complemento da rua
								*/
								$hash_complement = $this->AppImport->getHash($this->AppImport->getComplement($v->COMPLEMENTO), null, false);

								/**
								* Carrega um array com todos os estados
								*/
								$map_states = $this->AppImport->loadStates(true);

								$data = array(
									'Iaddress' => array(
										'state_id' => $state_id,
										'zipcode_id' => $this->Izipcode->id,
										'city_id' => $city_id,
										'state' => $map_states[$state_id],
										'zipcode' => $zipcode,
										'city' => $city,
										'type_address' => $this->AppImport->getTypeAddress($v->endereco->RUA, $v->endereco->NOME_RUA),
										'street' => $street,
										'number' => $number,
										'neighborhood' => $this->AppImport->getNeighborhood($v->endereco->BAIRRO),
										'complement' => $this->AppImport->getComplement($v->COMPLEMENTO),
										'h1' => $hash['h1'],
										'h2' => $hash['h2'],
										'h3' => $hash['h3'],
										'h4' => $hash['h4'],
										'h5' => $hash['h5'],
										'h_all' => $hash['h_all'],
										'h_first_last' => $hash['h_first_last'],
										'h_last' => $hash['h_last'],
										'h_first1_first2' => $hash['h_first1_first2'],
										'h_last1_last2' => $hash['h_last1_last2'],
										'h_complement' => $hash_complement['h_all'],
										)
									);
								$this->AppImport->timing_end();

								/**
								* Executa a importacao do Endereço
								* e carrega o id do Endereço importado
								*/
								$this->AppImport->timing_ini(TUNING_ADDRESS_IMPORT);
								$this->importAddress($data);
								$this->AppImport->timing_end();
								
							}

							/**
							* Amarra os registros Entidade, Telefone, CEP e Endereço na tabela associations
							*/

							/**
							* Carrega todos os id coletados ate o momento
							*/
							$this->AppImport->timing_ini(TUNING_LOAD_ALL_DATA);
							$data = array(
								'Iassociation' => array(
									'entity_id' => $this->Ientity->id,
									'landline_id' => $this->Ilandline->id,
									'address_id' => $this->Iaddress->id,
									'year' => $year,
									)
								);
							$this->AppImport->timing_end();
							
							$this->AppImport->timing_ini(TUNING_IMPORT_ALL_DATA);
							if($this->importAssociation($data)){
								/**
								* Registra todas as transacoes
								*/
								$this->db['landline']->commit();
								$this->db['address']->commit();
								$this->db['zipcode']->commit();
								$this->db['association']->commit();
							}else{
								/**
								* Aborta todas as transacoes relacionadas a entidade
								*/
								$this->db['landline']->rollback();
								$this->db['address']->rollback();
								$this->db['zipcode']->rollback();
								$this->db['association']->rollback();							
							}
							$this->AppImport->timing_end();

							/**
							* Salva as contabilizacoes na base de dados
							*/					
							$this->AppImport->__counter('entities');
							$this->AppImport->__counter('landlines');
							$this->AppImport->__counter('addresses');
							$this->AppImport->__counter('zipcodes');
							$this->AppImport->__counter('associations');	
						}
					}

					/**
					* Salva as contabilizacoes na base de dados
					*/					
					$this->AppImport->__counter('entities');

					/**
					* Finaliza todas as transacoes
					*/
					$this->db['entity']->commit();					
				}else{
					$this->AppImport->fail('entities');
				}
			}

			/**
			* Finaliza o processo de importacao
			*/
			exit();
		}
	}	


	/**
	* Método importEntity
	* Este método importa os dados da entidade
	*
	* @return void
	*/
	private function importEntity($entity){
		/**
		* Inicializa o ID da entidade como null
		*/
		$this->Ientity->id = null;

		/**
		* Verifica se a entidade que sera importada já existe na base de dados
		*/
		$hasEntity = $this->Ientity->findImport('first', array(
			'conditions' => array(
				'doc' => $entity['Ientity']['doc'],
				'type' => $entity['Ientity']['type'],
				)
			));				

		if(count($hasEntity)){
			$this->Ientity->id = $hasEntity['Ientity']['id'];
		}else{
			$this->Ientity->create($entity);
			if($this->Ientity->save()){
				$this->AppImport->success('entities');
			}else{
				$this->AppImport->fail('entities');
				$this->AppImport->__log("Falha ao importar a entidade", IMPORT_ENTITY_FAIL, $this->uf, false, $this->Ientity->useTable, null, $entity['Ientity']['doc'], $this->db['entity']->error);
			}
		}	
	}

	/**
	* Método importLandline
	* Este método importa os telefones relacionados a entidade
	*
	* @return void
	*/
	private function importLandline($landline){
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
			$hasLandline = $this->Ilandline->findImport('first', array(
				'conditions' => array(
					'tel_full' => $landline['Ilandline']['tel_full'],
					)
				));		

			if(count($hasLandline)){
				$this->Ilandline->id = $hasLandline['Ilandline']['id'];
			}else{
				$this->Ilandline->create($landline);
				if($this->Ilandline->save()){
					$this->AppImport->success('landlines');
				}else{
					$this->AppImport->fail('landlines');
					$this->AppImport->__log("Falha ao importar o telefone.", IMPORT_LANDLINE_FAIL, $this->uf, false, $this->Ilandline->useTable, null, $landline['Ilandline']['tel_full'], $this->db['Ilandline']->error);
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
	private function importZipcode($zipcode){
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
			$hasZipcode = $this->Izipcode->findImport('first', array(
				'conditions' => array(
					'code' => $zipcode['Izipcode']['code'],
					)
				));		

			if(count($hasZipcode)){
				$this->Izipcode->id = $hasZipcode['Izipcode']['id'];
			}else{
				$this->Izipcode->create($zipcode);
				if($this->Izipcode->save()){
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
	private function importAddress($address){
		/**
		* Inicializa o ID do endereco como null
		*/
		$this->Iaddress->id = null;

		/**
		* Verifica se o telefone que sera importado já existe na base de dados
		*/
		$hasAddress = $this->Iaddress->findImport('first', array(
			'conditions' => array(
				'zipcode_id' => $address['Iaddress']['zipcode_id'],
				'number' => $address['Iaddress']['number'],
				'h_complement' => $address['Iaddress']['h_complement'],
				)
			));		

		if(count($hasAddress)){
			$this->Iaddress->id = $hasAddress['Iaddress']['id'];
		}else{
			$this->Iaddress->create($address);
			if($this->Iaddress->save()){
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
	private function importAssociation($association){
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
			$hasAssociation = $this->Iassociation->findImport('first', array(
				'conditions' => array(
					'entity_id' => $association['Iassociation']['entity_id'],
					'landline_id' => $association['Iassociation']['landline_id'],
					'address_id' => $association['Iassociation']['address_id'],
					'year' => $association['Iassociation']['year'],
					)
				));	

		}


		if(isset($hasAssociation) && count($hasAssociation)){
			$this->Iassociation->id = $hasAssociation['Iassociation']['id'];
		}else{
			$this->Iassociation->create($association);
			$hasCreated = $this->Iassociation->save(); 
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