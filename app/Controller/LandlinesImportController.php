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

App::uses('AppImportsController', 'Controller');

/**
 * Import content controller
 *
 * Este controlador contem regras de negócio aplicadas ao model State
 *
 * @package       app.Controller
 * @link http://.framework.nasza.com.br/2.0/controller/LandlinesImport.html
 */
class LandlinesImportController extends AppImportsController {
	/**
	* Atributos da classe
	*/
	private $telefones_uf;
	private $pessoa_uf;
	private $endereco_uf;

	/**
	* Método run_binary
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function run_binary($uf=null){
		/**
		* Verifica se a chave do modulo de importacao esta ativa
		*/
		// if(!$this->Settings->active($this->name)){
		// 	die;
		// }

		/**
		* Desabilita o contador mobile e habilita o landline
		*/
		$this->Counter->updateAll(array('Counter.active' => null), array('Counter.table' => 'mobiles'));
		$this->Counter->updateAll(array('Counter.active' => true), array('Counter.table' => 'landlines'));
		
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
			$this->qt_reg = $this->NattFixoPessoa->find('count', array('conditions' => array('CPF_CNPJ !=' => '00000000000000000000')));
			$start_time = time();
			$this->Counter->updateAll(array('Counter.extracted' => $this->qt_reg, 'Counter.start_time' => $start_time), array('table' => 'entities', 'active' => '1'));

            /**
            * Inicializa a transacao das tabelas
            */
            $this->db['entity'] = $this->Ientity->getDataSource();
            $this->db['landline'] = $this->Ilandline->getDataSource();
            $this->db['address'] = $this->Ilandline->getDataSource();
            $this->db['zipcode'] = $this->Ilandline->getDataSource();
            $this->db['entityLandlineAddress'] = $this->Iassociation->getDataSource();

			/**
			* Inicia o processo de importacao
			*/
			$this->AppImport->__log("Importacao Iniciada.", IMPORT_BEGIN, $this->uf);
			for ($i=0; $i < $this->qt_reg; $i+=LIMIT_BUILD_SOURCE) { 

				/**
				* Verifica se a chave do modulo de importacao esta ativa
				*/
				// if(!$this->Settings->active($this->name)){
				// 	die;
				// }

				/**
				* Carrega o proximo registro das tabelas de pessoa, telefone e endereco q ainda nao foram importado
				*/
				$this->AppImport->timing_ini(TUNING_LOAD_NEXT_REGISTER);
				$entities = $this->NattFixoPessoa->next_binary(LIMIT_BUILD_SOURCE);
				$this->AppImport->timing_end();

                /**
                * Inicialiaza a transacao
                */
                $this->db['entity']->begin();
                $this->db['landline']->begin();
                $this->db['address']->begin();
                $this->db['zipcode']->begin();
                $this->db['entityLandlineAddress']->begin();

				foreach ($entities as $k => $v) {	
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
						$hasImported = $this->importEntity($data);
						$this->AppImport->timing_end();

						/**
						* Habilitar este IF somente quando for reimportar o mesmo estado
						*/
						// if($hasImported){
						// 	/**
						// 	* Verifica se a chave do modulo de importacao esta ativa
						// 	*/
						// if(!$this->Settings->active($this->name)){
						// 	die;
						// }
							
						// 	$this->AppImport->__counter('entities');
						// 	continue;
						// }

						/**
						* Exibe o status da importacao no console 
						*/
						// $this->AppImport->progressBar($this->qt_imported, $this->qt_reg, $this->uf);

						/**
						* Inicializa a importacao dos telefones da entidade encontrada
						*/
						if(isset($v['telefone']) && $hasImported){
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
									$city_id = null;
									// $city_id = $this->AppImport->getCityId($v2['endereco']['CIDADE'], $state_id, $this->Izipcode->id);
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
									$hash_complement = $this->AppImport->getHash($this->AppImport->getComplement($v2['COMPLEMENTO'], $v2['endereco']['NOME_RUA']), null, false);

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
											'complement' => $this->AppImport->getComplement($v2['COMPLEMENTO'], $v2['endereco']['NOME_RUA']),
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
										'mobile_id' => null,
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

                /**
                * Registra todas as transacoes
                */
                $this->AppImport->timing_ini(COMMIT_TRANSACTIONS);
                $this->db['entity']->commit();
                $this->db['landline']->commit();
                $this->db['address']->commit();
                $this->db['zipcode']->commit();
                $this->db['entityLandlineAddress']->commit();
                $this->AppImport->timing_end();

				/**
				* Verifica se a chave do modulo de importacao esta ativa
				*/
				if(!$this->Settings->active($this->name)){
					die;
				}
			}

			/**
			* Finaliza o processo de importacao
			*/
			exit();
		}
	}

	/**
	* Método run_binary
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function run_text(){
		/**
		* Desabilita o contador mobile e habilita o landline
		*/
		$this->Counter->updateAll(array('Counter.active' => null), array('Counter.table' => 'mobiles'));
		$this->Counter->updateAll(array('Counter.active' => true), array('Counter.table' => 'landlines'));
		
        /**
        * Inicializa a transacao das tabelas
        */
        $this->db['entity'] = $this->Ientity->getDataSource();
        $this->db['landline'] = $this->Ilandline->getDataSource();
        $this->db['address'] = $this->Ilandline->getDataSource();
        $this->db['zipcode'] = $this->Ilandline->getDataSource();
        $this->db['entityLandlineAddress'] = $this->Iassociation->getDataSource();

        /**
        * Carrega a pasta onde contem os dados em txt
        */
        $this->NattFixoPessoa->folder = ROOT . "/_db/source";

        /**
        * Carrega o layout dos dados que sera importados
        */
        $this->NattFixoPessoa->source_year = 2012;

        /**
        * Informa o conteudo do layout ao sistema
        */
        $this->NattFixoPessoa->delimiter = ';';
        $this->NattFixoPessoa->jumpFirstLine = false;
        $map_fields = array(
			'doc' => 'NRF',
			'name' => 'NOME',
			'mother' => '',
			'gender' => '',
			'birthday' => '',
			'ddd' => '',
			'tel' => '',
			'tel_full' => 'TEL_FULL',
			'zipcode' => 'CEP',
			'cod_end' => '',
			'complement' => 'INS_COMPL',
			'number' => 'INS_NUM_EN',
			'year' => '',
			'type_address' => '',
			'street' => 'INS_ENDERE',
			'neighborhood' => 'INS_BAIRRO',
			'city' => 'CIDADE',
			'state' => 'UF',
    	);
		$this->NattFixoPessoa->load_map_positions($map_fields, 'TEL_FULL;NRF;NOME;INS_ENDERE;INS_NUM_EN;INS_COMPL;INS_BAIRRO;CIDADE;UF;CEP');

		/**
		* Carrega o path de todos os arquivos contidos na pasta de recursos em texto
		*/
		$sources = $this->NattFixoPessoa->read_sources();

		/**
		* Contabiliza a quantidade de registros encontrado
		*/
		$this->qt_reg = 0;
		foreach ($sources as $k => $v) {
			$shell = shell_exec("wc -l {$v}");
			$qt = substr($shell, 0, strpos($shell, ' '));
			$this->qt_reg += $qt;

			/**
			* Desconsidera a linha do layout (quando houver)
			*/
			if($this->NattFixoPessoa->jumpFirstLine){
				$this->qt_reg--;
			}
		}
		$start_time = time();
		$this->Counter->updateAll(array('Counter.extracted' => $this->qt_reg, 'Counter.start_time' => $start_time), array('table' => 'entities', 'active' => '1'));

		/**
		* Inicia o processo de importacao
		*/
		$this->AppImport->__log("Importacao Iniciada.", IMPORT_BEGIN, $this->uf);

		/**
		* Percorre por todos os arquivos/recursos encontrados
		*/
		foreach ($sources as $k => $v) {
	        /**
	        * Inicialiaza a transacao
	        */
	        $this->db['entity']->begin();
	        $this->db['landline']->begin();
	        $this->db['address']->begin();
	        $this->db['zipcode']->begin();
	        $this->db['entityLandlineAddress']->begin();

	        /**
	        * Percorre por todas as linhas o arquivo, importando os dados contidos nas linhas para um array
	        */
	        $i = 0;
	        $txt = fopen ($v, "r");
	        while (!feof ($txt)){
				/**
				* Verifica se a chave do modulo de importacao esta ativa
				*/
				if(!$this->Settings->active($this->name)){
			        /**
			        * Registra todas as transacoes
			        */
			        $this->AppImport->timing_ini(COMMIT_TRANSACTIONS);
			        $this->db['entity']->commit();
			        $this->db['landline']->commit();
			        $this->db['address']->commit();
			        $this->db['zipcode']->commit();
			        $this->db['entityLandlineAddress']->commit();
			        $this->AppImport->timing_end();
					die;
				}

	            /**
	            * Contabiliza os registros processados
	            */
	            $i++;	            

		        /**
		        * Carrega a linha
		        */
		        $ln = fgets($txt, 4096);

	            /**
	            * Pula a primeira linha (layout do documento)
	            */
	            if($i === 1){
	                /**
	                * Verifica se o layout do arquivo esta diferente do layout informado
	                */
	                if($this->NattFixoPessoa->jumpFirstLine && trim(rtrim($ln, "\r\n")) != $this->NattFixoPessoa->layout){
	                    $log = date('Y-m-d') . ": O Layout do arquivo '{$v}' nao confere com o layout do arquivo.\r\n";
	                    $log .= "Layout informado:  {$this->NattFixoPessoa->layout}\r\n";
	                    $log .= "Layout encontrado: {$ln}\r\n";
	                    file_put_contents(dirname(dirname(dirname(__FILE__))) . '/_db/settings/logs', $log);
	                    die($log);
	                }
	                continue;
	            }

	            /**
	            * Carrega o array com os dados a serem importados a partir linha extraida do arquivo
	            */
				$this->AppImport->timing_ini(TUNING_LOAD_NEXT_REGISTER);
	            $entity = $this->NattFixoPessoa->txt2array($ln);
	            $this->AppImport->timing_end();

				if(isset($entity['pessoa'])){
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
					$hasImported = $this->importEntity($data);
					$this->AppImport->timing_end();

					/**
					* Inicializa a importacao dos telefones da entidade encontrada
					*/
					if($hasImported && !empty($entity['telefone'][key($entity['telefone'])]['TELEFONE'])){
						foreach ($entity['telefone'] as $v2) {
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
							if(!empty($v2['endereco']['NOME_RUA'])){
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

								$state_id = $this->AppImport->getState($v2['endereco']['UF']);
								$city_id = null;
								// $city_id = $this->AppImport->getCityId($v2['endereco']['CIDADE'], $state_id, $this->Izipcode->id);
								$city = $this->AppImport->getCity($v2['endereco']['CIDADE']);
								$zipcode = $this->AppImport->getZipcode($v2['endereco']['CEP']);
								$number = $this->AppImport->getStreetNumber($v2['NUMERO'], $v2['endereco']['NOME_RUA']);
								$complement = $this->AppImport->getComplement($v2['COMPLEMENTO'], $v2['endereco']['NOME_RUA']);
								$type_address = $this->AppImport->getTypeAddress($v2['endereco']['RUA'], $v2['endereco']['NOME_RUA']);
								$neighborhood = $this->AppImport->getNeighborhood($v2['endereco']['BAIRRO']);

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
								$hash_complement = $this->AppImport->getHash($complement, null, false);

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
										'type_address' => $type_address,
										'street' => $street,
										'number' => $number,
										'neighborhood' => $neighborhood,
										'complement' => $complement,
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
									'mobile_id' => null,
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
	        fclose ($txt);

	        /**
	        * Registra todas as transacoes
	        */
	        $this->AppImport->timing_ini(COMMIT_TRANSACTIONS);
	        $this->db['entity']->commit();
	        $this->db['landline']->commit();
	        $this->db['address']->commit();
	        $this->db['zipcode']->commit();
	        $this->db['entityLandlineAddress']->commit();
	        $this->AppImport->timing_end();
		}


		/**
		* Finaliza o processo de importacao
		*/
		exit();
	}
}