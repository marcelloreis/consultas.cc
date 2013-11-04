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
		"Settings"
		);

	public $components = array('Import');

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
			* Calcula o total de registros que sera importado
			*/
			$this->Import->timing_ini(1, 'Calcula o total de registros que sera importado');
			$this->qt_reg = $this->NattFixoPessoa->find('count', array('conditions' => array('CPF_CNPJ !=' => '00000000000000000000',)));
			$this->Import->timing_end();

			/**
			* Inicia o processo de importacao
			*/
			$this->Import->__log("Iniciando a importacao do Estado [$this->uf]", $this->uf);

			/**
			* Inicializa a transacao das tabelas
			*/
			$this->db['entity'] = $this->Ientity->getDataSource();
			$this->db['landline'] = $this->Ilandline->getDataSource();
			$this->db['address'] = $this->Ilandline->getDataSource();
			$this->db['zipcode'] = $this->Ilandline->getDataSource();
			$this->db['entityLandlineAddress'] = $this->Iassociation->getDataSource();

			do{
				/**
				* Verifica se a chave do modulo de importacao esta ativa
				*/
				$this->Import->timing_ini(2, 'Verifica se a chave do modulo de importacao esta ativa');
				$this->Settings->active($this->name);
				$this->Import->timing_end();

				/**
				* Carrega o proximo registro das tabelas de pessoa, telefone e endereco q ainda nao foram importado
				*/
				$this->Import->timing_ini(3, 'Carrega o proximo registro das tabelas de pessoa, telefone e endereco q ainda nao foram importado');
				$entity = $this->NattFixoPessoa->next();
				$this->Import->timing_end();

				if(isset($entity['pessoa'])){
					/**
					* Inicialiaza a transacao
					*/
					$this->db['entity']->begin();

					/**
					* Gera o hash do nome da entidade
					*/
					$hash = $this->Import->getHash($this->Import->clearName($entity['pessoa']['NOME_RAZAO']));

					/**
					* Trata os dados da entidade para a importacao
					*/
					//Carrega o tipo de documento
					$doc_type = $this->Import->getTypeDoc($entity['pessoa']['CPF_CNPJ'], $this->Import->clearName($entity['pessoa']['NOME_RAZAO']), $this->Import->clearName($entity['pessoa']['MAE']), $this->Import->getBirthday($entity['pessoa']['DT_NASCIMENTO']));
					$this->Import->timing_ini(4, 'Trata os dados da entidade para a importacao');
					$data = array(
						'Ientity' => array(
							'doc' => $entity['pessoa']['CPF_CNPJ'],
							'name' => $this->Import->clearName($entity['pessoa']['NOME_RAZAO']),
							'mother' => $this->Import->clearName($entity['pessoa']['MAE']),
							'type' => $doc_type,
							'gender' => $this->Import->getGender($entity['pessoa']['SEXO'], $doc_type, $entity['pessoa']['NOME_RAZAO']),
							'birthday' => $this->Import->getBirthday($entity['pessoa']['DT_NASCIMENTO']),
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
							'h_mother' => $this->Import->getHash($entity['pessoa']['MAE'], 'h_all'),
							)
						);
					$this->Import->timing_end();

					/**
					* Executa a importacao da tabela Entity
					* e carrega o id da entidade importada
					*/
					$this->Import->timing_ini(5, 'Executa a importacao da tabela Entity');
					$this->importEntity($data);
					$this->Import->timing_end();


					/**
					* Exibe o status da importacao no console 
					*/
					$this->Import->__flush();
					$this->qt_imported++;
					$this->Import->progressBar($this->qt_imported, $this->qt_reg, $this->uf);

					/**
					* Inicializa a importacao dos telefones da entidade encontrada
					*/
					if(isset($entity['telefone'])){
						foreach ($entity['telefone'] as $k => $v) {
							/**
							* Inicializa a transacao
							*/
							$this->db['entity']->begin();
							$this->db['landline']->begin();
							$this->db['address']->begin();
							$this->db['zipcode']->begin();
							$this->db['entityLandlineAddress']->begin();

							/**
							* Desmembra o DDD do Telefone
							*/
							$this->Import->timing_ini(6, 'Trata os dados o telefone para a importacao');
							$ddd_telefone = $v['TELEFONE'];
							$ddd = $this->Import->getDDD($v['TELEFONE']);
							$telefone = $this->Import->getTelefone($v['TELEFONE']);
						
							/**
							* Extrai o ano de atualizacao do telefone
							*/
							$year = $this->Import->getUpdated($v['DATA_ATUALIZACAO']);

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
							$this->Import->timing_end();
							
							/**
							* Executa a importacao do telefone
							* e carrega o id do telefone importado
							*/
							$this->Import->timing_ini(7, 'Executa a importacao do telefone');
							$this->importLandline($data, $v['TELEFONE']);
							$this->Import->timing_end();

							/**
							* Inicializa a importacao do CEP do telefone encontrado
							* Trata os dados do CEP para a importacao
							*/				
							$this->Import->timing_ini(8, 'Trata os dados do CEP para a importacao');		
							$data = array(
								'Izipcode' => array(
									'code' => $this->Import->getZipcode($v['endereco']['CEP']),
									'code_original' => $v['endereco']['CEP']
									)
								);
							$this->Import->timing_end();

							/**
							* Executa a importacao do CEP
							* e carrega o id do CEP importado
							*/
							$this->Import->timing_ini(9, 'Executa a importacao do CEP');
							$this->importZipcode($data);
							$this->Import->timing_end();

							/**
							* Inicializa a importacao do endereco do telefone encontrado
							* Trata os dados do endereço para a importacao
							*/	
							$this->Import->timing_ini(10, 'Trata os dados do endereço para a importacao');
							
							$state_id = $this->Import->getState($v['endereco']['UF'], $this->uf);
							$city_id = $this->Import->getCityId($v['endereco']['CIDADE'], $state_id, $this->Izipcode->id);
							$city = $this->Import->getCity($v['endereco']['CIDADE']);
							$zipcode = $this->Import->getZipcode($v['endereco']['CEP']);
							$number = $this->Import->getStreetNumber($v['NUMERO'], $v['endereco']['NOME_RUA']);

							/**
							* Trata o nome da rua
							*/
							$street = $this->Import->getStreet($v['endereco']['NOME_RUA']);

							/**
							* Gera o hash do nome da rua
							*/
							$hash = $this->Import->getHash($street);

							/**
							* Gera o hash do complemento da rua
							*/
							$hash_complement = $this->Import->getHash($this->Import->getComplement($v['COMPLEMENTO']), null, false);

							/**
							* Carrega um array com todos os estados
							*/
							$map_states = $this->Import->loadStates(true);

							$data = array(
								'Iaddress' => array(
									'state_id' => $state_id,
									'zipcode_id' => $this->Izipcode->id,
									'city_id' => $city_id,
									'state' => $map_states[$state_id],
									'zipcode' => $zipcode,
									'city' => $city,
									'type_address' => $this->Import->getTypeAddress($v['endereco']['RUA'], $v['endereco']['NOME_RUA']),
									'street' => $street,
									'number' => $number,
									'neighborhood' => $this->Import->getNeighborhood($v['endereco']['BAIRRO']),
									'complement' => $this->Import->getComplement($v['COMPLEMENTO']),
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
							$this->Import->timing_end();

							/**
							* Executa a importacao do Endereço
							* e carrega o id do Endereço importado
							*/
							$this->Import->timing_end(11, 'Executa a importacao do Endereço');
							$this->importAddress($data);
							$this->Import->timing_end();

							/**
							* Amarra os registros Entidade, Telefone, CEP e Endereço na tabela associations
							*/

							/**
							* Carrega todos os id coletados ate o momento
							*/
							$this->Import->timing_ini(12, 'Carrega todos os id coletados ate o momento');
							$data = array(
								'Iassociation' => array(
									'entity_id' => $this->Ientity->id,
									'landline_id' => $this->Ilandline->id,
									'address_id' => $this->Iaddress->id,
									'state_id' => $state_id,
									'city_id' => $city_id,
									'zipcode_id' => $this->Izipcode->id,
									'state' => $map_states[$state_id],
									'city' => $city,
									'zipcode' => $zipcode,
									'number' => $number,
									'year' => $year,
									)
								);
							$this->Import->timing_end();
							
							$this->Import->timing_ini(13, 'Executa a importacao dos dados coletados ate o momento');
							if($this->importAssociation($data)){
								/**
								* Registra todas as transacoes
								*/
								$this->db['entity']->commit();
								$this->db['landline']->commit();
								$this->db['address']->commit();
								$this->db['zipcode']->commit();
								$this->db['entityLandlineAddress']->commit();
							}else{
								/**
								* Aborta todas as transacoes relacionadas a entidade
								*/
								$this->db['entity']->rollback();
								$this->db['landline']->rollback();
								$this->db['address']->rollback();
								$this->db['zipcode']->rollback();
								$this->db['entityLandlineAddress']->rollback();							
							}
							$this->Import->timing_end();

							/**
							* Salva as contabilizacoes na base de dados
							*/					
							$this->Import->__counter('entities');
							$this->Import->__counter('landlines');
							$this->Import->__counter('addresses');
							$this->Import->__counter('zipcodes');
							$this->Import->__counter('associations');	
						}
					}

					/**
					* Finaliza todas as transacoes
					*/
					$this->db['entity']->commit();					
				}
			}while($entity && count($entity));

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
				$this->Import->success('entities');
				// $this->Import->__log("Entidade importada com sucesso", $this->uf, true, $this->Ientity->useTable, $this->Ientity->id, $entity['Ientity']['doc']);
			}else{
				$this->Import->fail('entities');
				$this->Import->__log("Falha ao importar a entidade", $this->uf, false, $this->Ientity->useTable, null, $entity['Ientity']['doc'], $this->db['entity']->error);
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
			$this->Import->fail('landlines');
			$this->Import->__log("Telefone inconsistente", $this->uf, false, $this->Ilandline->useTable, null, $landline['Ilandline']['tel_original']);
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
					$this->Import->success('landlines');
					// $this->Import->__log("Telefone importado com sucesso.", $this->uf, true, $this->Ilandline->useTable, $this->Ilandline->id, $landline['Ilandline']['tel_full']);
				}else{
					$this->Import->fail('landlines');
					$this->Import->__log("Falha ao importar o telefone.", $this->uf, false, $this->Ilandline->useTable, null, $landline['Ilandline']['tel_full'], $this->db['Ilandline']->error);
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
			$this->Import->fail('zipcodes');
			$this->Import->__log("CEP inconsistente ou null", $this->uf, false, $this->Izipcode->useTable, null, $zipcode['Izipcode']['code_original']);
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
					$this->Import->success('zipcodes');
					// $this->Import->__log("CEP importado com sucesso.", $this->uf, true, $this->Izipcode->useTable, $this->Izipcode->id, $zipcode['Izipcode']['code_original']);
				}else{
					$this->Import->fail('zipcodes');
					$this->Import->__log("Falha ao importar o CEP.", $this->uf, false, $this->Izipcode->useTable, null, $zipcode['Izipcode']['code_original'], $this->db['Izipcode']->error);
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
				$this->Import->success('addresses');
				// $this->Import->__log("Endereço importado com sucesso.", $this->uf, true, $this->Iaddress->useTable, $this->Iaddress->id);
			}else{
				$this->Import->fail('addresses');
				$this->Import->__log("Falha ao importar o endereço.", $this->uf, false, $this->Iaddress->useTable, null, $address['Iaddress']['state_id'], $this->db['Iaddress']->error);
			}
		}	
	}

	/**
	* Método importAssociation
	* Amarra os registros Entidade, Telefone, CEP e Endereço na tabela associations
	*
	* @return bool $hasCreated
	*/
	private function importAssociation($entityLandlineAddress){
		/**
		* Inicializa o ID das juncoes como null
		*/
		$this->Iassociation->id = null;

		/**
		* Inicializa a variavel $asCreated com false
		*/
		$hasCreated = false;

		if(
			(!empty($entityLandlineAddress['Iassociation']['entity_id']) && $entityLandlineAddress['Iassociation']['entity_id'] != '0')
			&& 
			(
				(!empty($entityLandlineAddress['Iassociation']['landline_id']) && $entityLandlineAddress['Iassociation']['landline_id'] != '0') 
				|| 
				(!empty($entityLandlineAddress['Iassociation']['address_id']) && $entityLandlineAddress['Iassociation']['address_id'] != '0'))
			){

			/**
			* Verifica se a junção já existe
			*/
			$hasAssociation = $this->Iassociation->findImport('first', array(
				'conditions' => array(
					'entity_id' => $entityLandlineAddress['Iassociation']['entity_id'],
					'landline_id' => $entityLandlineAddress['Iassociation']['landline_id'],
					'address_id' => $entityLandlineAddress['Iassociation']['address_id'],
					'year' => $entityLandlineAddress['Iassociation']['year'],
					)
				));	

		}


		if(isset($hasAssociation) && count($hasAssociation)){
			$this->Iassociation->id = $hasAssociation['Iassociation']['id'];
		}else{
			$this->Iassociation->create($entityLandlineAddress);
			$hasCreated = $this->Iassociation->save(); 
			if($hasCreated){
				$this->Import->success('associations');
			}else{
				$this->Import->fail('associations');
				$this->Import->__log("Falha ao importar os dados da tabela associations", $this->uf, false, $this->Iassociation->useTable, $this->Ientity->id);
			}
		}	

		return $hasCreated;
	}

}
