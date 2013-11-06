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
class ImportController extends AppController {
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
	* Método run
	* Este método carrega as estatisticas da importacao vigente
	*
	* @return void
	*/
	public function statistics($uf=null){
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
									// 'state_id' => $state_id,
									// 'city_id' => $city_id,
									// 'zipcode_id' => $this->Izipcode->id,
									// 'state' => $map_states[$state_id],
									// 'city' => $city,
									// 'zipcode' => $zipcode,
									// 'number' => $number,
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
}
