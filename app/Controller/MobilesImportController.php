<?php
/**
 * Import content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/MobilesImport/
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
 * @link http://.framework.nasza.com.br/2.0/controller/MobilesImport.html
 */
class MobilesImportController extends AppImportsController {
	/**
	* Atributos da classe
	*/
	private $telefones_uf;
	private $pessoa_uf;
	private $endereco_uf;
	private $line;
	private $filename;

	/**
	* Método build_source
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function build_source($uf=null){
		/**
		* Desabilita o contador mobile e habilita o mobile
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
	        * Inicialiaza a varaivel que contara as transacoes efetuadas
	        */
	        $reload_transaction = 0;

			/**
			* Carrega o nome do arquivo que sera armazenado os dados
			*/
			$this->filename = ROOT . "/_db/source/{$this->uf}-natt-mobiles-0-to-" . LIMIT_TABLE_IMPORTS;

			for ($i=0; $i < $this->qt_reg; $i++) { 
				/**
				* Carrega o proximo registro das tabelas de pessoa, telefone e endereco q ainda nao foram importado
				*/
				$this->AppImport->timing_ini(TUNING_LOAD_NEXT_REGISTER);
				$entities = $this->NattFixoPessoa->next_binary($i);
				$this->AppImport->timing_end();

				/**
				* Percorre por todas as entidades encontradas
				*/
				$this->AppImport->timing_ini(TUNING_ENTITY_IMPORT);
				foreach ($entities as $k => $v) {
					if(!$this->Settings->active($this->name)){
						die;
					}

					/**
					* Insere a linha de dados no arquivo
					*/
					file_put_contents($this->filename, "{$v}\n", FILE_APPEND);

					$this->AppImport->success('entities');
					$this->AppImport->__counter('entities');
				}
				$this->AppImport->timing_end();

	            /**
	            * Recarrega as transacoes quando chegar no limite setado
	            */	            
	            if($reload_transaction == LIMIT_TABLE_IMPORTS){
		            /**
		            * Reinicia a contagem das transacoes
		            */	            
	            	$reload_transaction = 0;

	            	$this->filename = ROOT . "/_db/source/{$this->uf}-natt-mobiles-{$i}-to-" . (LIMIT_TABLE_IMPORTS + $i);

				}            

	            /**
	            * Contabiliza as transacoes para recarrega-las
	            */
	            $reload_transaction++;
			}

			/**
			* Finaliza o processo de importacao
			*/
			exit();
		}
	}

	/**
	* Método run_text
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function run_text(){
		/**
		* Desabilita o contador mobile e habilita o mobile
		*/
		$this->Counter->updateAll(array('Counter.active' => true), array('Counter.table' => 'mobiles'));
		$this->Counter->updateAll(array('Counter.active' => null), array('Counter.table' => 'landlines'));
		
        /**
        * Inicializa a transacao das tabelas
        */
        $this->db['entity'] = $this->Ientity->getDataSource();
        $this->db['mobile'] = $this->Imobile->getDataSource();
        $this->db['address'] = $this->Iaddress->getDataSource();
        $this->db['zipcode'] = $this->Izipcode->getDataSource();
        $this->db['associations'] = $this->Iassociation->getDataSource();

        /**
        * Carrega a pasta onde contem os dados em txt
        */
        $this->Imobile->folder = ROOT . "/_db/source";

        /**
        * Carrega o layout dos dados que sera importados
        */
        $this->Imobile->source_year = 2012;

        /**
        * Carrega o lote da importacao
        */
        $map = $this->Ientity->find('first', array(
        	'recursive' => -1,
        	'fields' => array('Ientity.lote'),
        	'order' => array('Ientity.lote' => 'desc'),
        	));
        $this->Imobile->lote = (!empty($map['Ientity']['lote']))?($map['Ientity']['lote'] + 1):1;

        /**
        * Informa o conteudo do layout ao sistema
        */
        $this->Imobile->delimiter = ';';
        $this->Imobile->map_pos = array(
			'doc' => '',
			'name' => 2,
			'mother' => '',
			'gender' => '',
			'birthday' => '',
			'ddd' => 0,
			'tel' => 1,
			'tel_full' => '',
			'zipcode' => 8,
			'cod_end' => '',
			'complement' => 6,
			'number' => 5,
			'year' => '',
			'type_address' => 3,
			'street' => 4,
			'neighborhood' => 7,
			'city' => 9,
			'state' => 10,
    	);

		/**
		* Carrega o path de todos os arquivos contidos na pasta de recursos em texto
		*/
		$sources = $this->Imobile->read_sources();

		/**
		* Contabiliza a quantidade de registros encontrado
		*/
		$this->qt_reg = 0;
		foreach ($sources as $k => $v) {
			/**
			* Verifica a primeira linha do arquivo é o layout
			*/
			$first_line = shell_exec("head -1 {$v}");
			if(preg_match("/{$this->Imobile->delimiter}nome{$this->Imobile->delimiter}|{$this->Imobile->delimiter}endereco{$this->Imobile->delimiter}|{$this->Imobile->delimiter}complement{$this->Imobile->delimiter}|{$this->Imobile->delimiter}bairro{$this->Imobile->delimiter}|{$this->Imobile->delimiter}cidade{$this->Imobile->delimiter}|{$this->Imobile->delimiter}uf{$this->Imobile->delimiter}/si", $first_line)){
				/**
				* Remove a linha de layout do arquivo
				*/
				$source_temp = "{$v}.tmp";
				shell_exec("sed '1d' {$v} > {$source_temp}");
				shell_exec("mv {$source_temp} {$v}");
			}			


			$shell = shell_exec("wc -l {$v}");
			$qt = substr($shell, 0, strpos($shell, ' '));
			$this->qt_reg += $qt;
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
	        $this->db['mobile']->begin();
	        $this->db['address']->begin();
	        $this->db['zipcode']->begin();
	        $this->db['associations']->begin();

	        /**
	        * Inicialiaza a varaivel que contara as transacoes efetuadas
	        */
	        $reload_transaction = 0;

	        // preg_match('/tel([a-z]{2})fixo\.txt$/si', $v, $vet);
	        preg_match('/([a-z]{2})[0-9]?\.txt$/si', $v, $vet);
	        $this->uf = preg_replace('/[^a-z]/si', '', $vet[1]);

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
			        $this->AppImport->timing_ini(TUNING_COMMIT_TRANSACTIONS);
			        $this->db['entity']->commit();
			        $this->db['mobile']->commit();
			        $this->db['address']->commit();
			        $this->db['zipcode']->commit();
			        $this->db['associations']->commit();
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
	            * Contabiliza as transacoes para recarrega-las
	            */
	            $reload_transaction++;

	            /**
	            * Recarrega as transacoes quando chegar no limite setado
	            */	            
	            if($reload_transaction == LIMIT_TABLE_IMPORTS){
		            /**
		            * Reinicia a contagem das transacoes
		            */	            
	            	$reload_transaction = 0;

			        /**
			        * Registra todas as transacoes
			        */
			        $this->AppImport->timing_ini(TUNING_COMMIT_TRANSACTIONS);
			        $this->db['entity']->commit();
			        $this->db['mobile']->commit();
			        $this->db['address']->commit();
			        $this->db['zipcode']->commit();
			        $this->db['associations']->commit();
			        $this->AppImport->timing_end();


			        /**
			        * Inicialiaza a transacao
			        */
			        $this->db['entity']->begin();
			        $this->db['mobile']->begin();
			        $this->db['address']->begin();
			        $this->db['zipcode']->begin();
			        $this->db['associations']->begin();
	            }

	            /**
	            * Carrega o array com os dados a serem importados a partir linha extraida do arquivo
	            */
				$this->AppImport->timing_ini(TUNING_LOAD_NEXT_REGISTER);
	            $entity = $this->Imobile->txt2array($ln);
	            $this->AppImport->timing_end();

				if(isset($entity['NAME'])){
					/**
					* Gera o hash do nome da entidade
					*/
					$hash = $this->AppImport->getHash($this->AppImport->clearName($entity['NAME']));

					/**
					* Trata os dados da entidade para a importacao
					*/
					//Carrega o tipo de documento
					$doc_type = $this->AppImport->getTypeDoc($entity['DOC'], $this->AppImport->clearName($entity['NAME']), $this->AppImport->clearName($entity['MOTHER']), $this->AppImport->getBirthday($entity['BIRTHDAY']));
					$doc = !empty($entity['DOC'])?$entity['DOC']:null;					
					$this->AppImport->timing_ini(TUNING_ENTITY_LOAD);
					$data = array(
						'Ientity' => array(
							'doc' => $doc,
							'name' => $this->AppImport->clearName($entity['NAME']),
							'mother' => $this->AppImport->clearName($entity['MOTHER']),
							'type' => $doc_type,
							'gender' => $this->AppImport->getGender($doc_type, $entity['NAME']),
							'birthday' => $this->AppImport->getBirthday($entity['BIRTHDAY']),
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
							'h_mother' => $this->AppImport->getHash($entity['MOTHER'], 'h_all'),
							'lote' => $this->Imobile->lote,
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
					* Inicializa a importacao dos telefones da entidade encontrada
					*/
					if(!empty($this->Ientity->id)){
						/**
						* Desmembra o DDD do Telefone
						*/
						$this->AppImport->timing_ini(TUNING_LANDLINE_LOAD);
						$ddd_telefone = $entity['TEL_FULL'];
						$ddd = $this->AppImport->getDDDMobile($entity['TEL_FULL']);
						$telefone = $this->AppImport->getMobile($entity['TEL_FULL']);
					
						/**
						* Extrai o ano de atualizacao do telefone
						*/
						$year = $this->Imobile->source_year;

						/**
						* Trata os dados o telefone para a importacao
						*/
						$data = array(
							'Imobile' => array(
								'year' => $year,
								'ddd' => $ddd,
								'tel' => $telefone,
								'tel_full' => "{$ddd}{$telefone}",
								'tel_original' => $entity['TEL_FULL'],
								'lote' => $this->Imobile->lote,
								)
							);
						$this->AppImport->timing_end();

						/**
						* Executa a importacao do telefone
						* e carrega o id do telefone importado
						*/
						$this->AppImport->timing_ini(TUNING_LANDLINE_IMPORT);
						$this->importMobile($data);
						$this->AppImport->timing_end();


						/**
						* Inicializa a importacao do CEP do telefone encontrado
						* Trata os dados do CEP para a importacao
						*/				
						$this->AppImport->timing_ini(TUNING_ZIPCODE_LOAD);		
						$data = array(
							'Izipcode' => array(
								'code' => $this->AppImport->getZipcode($entity['ZIPCODE']),
								'code_original' => $entity['ZIPCODE'],
								'lote' => $this->Imobile->lote,
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

						$state_id = $this->AppImport->getState($entity['STATE'], $this->uf);
						$city_id = null;
						// $city_id = $this->AppImport->getCityId($entity['CITY'], $state_id, $this->Izipcode->id);
						$city = $this->AppImport->getCity($entity['CITY']);
						$zipcode = $this->AppImport->getZipcode($entity['ZIPCODE']);
						$number = $this->AppImport->getStreetNumber($entity['NUMBER'], $entity['STREET']);
						$complement = $this->AppImport->getComplement($entity['COMPLEMENT'], $entity['STREET']);
						$type_address = $this->AppImport->getTypeAddress($entity['TYPE_ADDRESS'], $entity['STREET']);
						$neighborhood = $this->AppImport->getNeighborhood($entity['NEIGHBORHOOD']);

						/**
						* Trata o nome da rua
						*/
						$street = $this->AppImport->getStreet($entity['STREET']);

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
								'lote' => $this->Imobile->lote,
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
								'mobile_id' => $this->Imobile->id,
								'landline_id' => null,
								'address_id' => $this->Iaddress->id,
								'year' => $year,
								'lote' => $this->Imobile->lote,
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
						$this->AppImport->__counter('mobiles');
						$this->AppImport->__counter('addresses');
						$this->AppImport->__counter('zipcodes');
						$this->AppImport->__counter('associations');	
					}else{
						file_put_contents(ROOT . '/_db/settings/logs', "Linha: {$i}\r\n{$ln}\r\n\r\n\r\n", FILE_APPEND);
					}

					/**
					* Salva as contabilizacoes na base de dados
					*/					
					$this->AppImport->__counter('entities');
				}else{
					file_put_contents(ROOT . '/_db/settings/logs', "Linha: {$i}\r\n{$ln}\r\n\r\n\r\n", FILE_APPEND);
				}
	        }
	        fclose ($txt);

	        /**
	        * Registra todas as transacoes
	        */
	        $this->AppImport->timing_ini(TUNING_COMMIT_TRANSACTIONS);
	        $this->db['entity']->commit();
	        $this->db['mobile']->commit();
	        $this->db['address']->commit();
	        $this->db['zipcode']->commit();
	        $this->db['associations']->commit();
	        $this->AppImport->timing_end();
		}


		/**
		* Finaliza o processo de importacao
		*/
		exit();
	}

	/**
	* Método run_binary
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function run_binary(){
		file_put_contents(ROOT . '/_db/settings/on_off', '1');

		/**
		* Desabilita o contador mobile e habilita o mobile
		*/
		$this->Counter->updateAll(array('Counter.active' => null), array('Counter.table' => 'landlines'));
		$this->Counter->updateAll(array('Counter.active' => true), array('Counter.table' => 'mobiles'));
		$this->Counter->updateAll(array('Counter.success' => null, 'Counter.fails' => null, 'Counter.extracted' => null, 'Counter.start_time' => null), array('Counter.active' => true));

        /**
        * Inicializa a transacao das tabelas
        */
        $this->db['entity'] = $this->Ientity->getDataSource();
        $this->db['mobile'] = $this->Imobile->getDataSource();
        $this->db['address'] = $this->Iaddress->getDataSource();
        $this->db['zipcode'] = $this->Izipcode->getDataSource();
        $this->db['associations'] = $this->Iassociation->getDataSource();

        /**
        * Carrega o lote da importacao
        */
        $map = $this->Ientity->find('first', array(
        	'recursive' => -1,
        	'fields' => array('Ientity.lote'),
        	'order' => array('Ientity.id' => 'desc'),
        	));
        $this->Imobile->lote = (!empty($map['Ientity']['lote']))?($map['Ientity']['lote'] + 1):1;

		/**
		* Carrega o nome de todas as tabelas q serao importadas
		*/
		$sources = $this->NattMovelTelefone->read_tables();

		/**
		* Contabiliza a quantidade de registros encontrado
		*/
		$this->qt_reg = 0;
		foreach ($sources as $k => $v) {
			$this->NattMovelTelefone->setSource($v);
			$this->qt_reg += $this->NattMovelTelefone->find('count', array(
				'recursive' => -1,				
				'conditions' => array(
					'NattMovelTelefone.CPF_CNPJ !=' => '00000000000000',
					),
				));
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
			* Seleciona a tabela q serao importados as entidades
			*/
			$this->NattMovelTelefone->setSource($v);

	        /**
	        * Inicialiaza a transacao
	        */
	        $this->db['entity']->begin();
	        $this->db['mobile']->begin();
	        $this->db['address']->begin();
	        $this->db['zipcode']->begin();
	        $this->db['associations']->begin();

	        /**
	        * Inicialiaza a varaivel que contara as transacoes efetuadas
	        */
	        $reload_transaction = 0;

	        /**
	        * Seleciona o estado padrao
	        */
	        $this->uf = $v;

			$this->qt_reg = $this->NattMovelTelefone->find('count');
	    	for ($i=0; $i < $this->qt_reg; $i+=LIMIT_BUILD_SOURCE) { 
		        /**
		        * Carrega todas as entidades da tabela selecionada
		        */
		        $this->AppImport->timing_ini(TUNING_LOAD_NEXT_REGISTER);
				$entities = $this->NattMovelTelefone->find('all', array(
						'recursive' => -1,
						'conditions' => array(
							'NattMovelTelefone.CPF_CNPJ !=' => '00000000000000',
							'NattMovelTelefone.transf' => null,
							),
						'limit' => "0," . LIMIT_BUILD_SOURCE,
					));
		        $this->AppImport->timing_end();

		        /**
		        * Percorre por todos os registros retornados no find
		        */
		        foreach ($entities as $k2 => $v2) {
					/**
					* Verifica se a chave do modulo de importacao esta ativa
					*/
					if(!$this->Settings->active($this->name)){
				        /**
				        * Registra todas as transacoes
				        */
				        $this->AppImport->timing_ini(TUNING_COMMIT_TRANSACTIONS);
				        $this->db['entity']->commit();
				        $this->db['mobile']->commit();
				        $this->db['address']->commit();
				        $this->db['zipcode']->commit();
				        $this->db['associations']->commit();
				        $this->AppImport->timing_end();
						die;
					}

		            /**
		            * Contabiliza as transacoes para recarrega-las
		            */
		            $reload_transaction++;

		            /**
		            * Recarrega as transacoes quando chegar no limite setado
		            */	            
		            if($reload_transaction >= LIMIT_TABLE_IMPORTS){
			            /**
			            * Reinicia a contagem das transacoes
			            */	            
		            	$reload_transaction = 0;

				        /**
				        * Registra todas as transacoes
				        */
				        $this->AppImport->timing_ini(TUNING_COMMIT_TRANSACTIONS);
				        $this->db['entity']->commit();
				        $this->db['mobile']->commit();
				        $this->db['address']->commit();
				        $this->db['zipcode']->commit();
				        $this->db['associations']->commit();
				        $this->AppImport->timing_end();


				        /**
				        * Inicialiaza a transacao
				        */
				        $this->db['entity']->begin();
				        $this->db['mobile']->begin();
				        $this->db['address']->begin();
				        $this->db['zipcode']->begin();
				        $this->db['associations']->begin();
		            }

					if(isset($v2['NattMovelTelefone']['NOME'])){
						/**
						* Gera o hash do nome da entidade
						*/
						$hash = $this->AppImport->getHash($this->AppImport->clearName($v2['NattMovelTelefone']['NOME']));

						/**
						* Trata os dados da entidade para a importacao
						*/
						//Carrega o tipo de documento
						$doc_type = $this->AppImport->getTypeDoc($v2['NattMovelTelefone']['CPF_CNPJ'], $this->AppImport->clearName($v2['NattMovelTelefone']['NOME']), null, null);
						$doc = !empty($v2['NattMovelTelefone']['CPF_CNPJ'])?$v2['NattMovelTelefone']['CPF_CNPJ']:null;
						$doc = (empty($v2['NattMovelTelefone']['CPF_CNPJ']) || $v2['NattMovelTelefone']['CPF_CNPJ'] == '00000000000000')?null:$v2['NattMovelTelefone']['CPF_CNPJ'];
						$this->AppImport->timing_ini(TUNING_ENTITY_LOAD);
						$data = array(
							'Ientity' => array(
								'doc' => $doc,
								'name' => $this->AppImport->clearName($v2['NattMovelTelefone']['NOME']),
								'mother' => null,
								'type' => $doc_type,
								'gender' => $this->AppImport->getGender($doc_type, $v2['NattMovelTelefone']['NOME']),
								'birthday' => null,
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
								'h_mother' => null,
								'lote' => $this->Imobile->lote,
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
						* Inicializa a importacao dos telefones da entidade encontrada
						*/
						if(!empty($this->Ientity->id)){
							/**
							* Desmembra o DDD do Telefone
							*/
							$this->AppImport->timing_ini(TUNING_LANDLINE_LOAD);
							$ddd_telefone = $v2['NattMovelTelefone']['TELEFONE'];
							$ddd = $this->AppImport->getDDDMobile($v2['NattMovelTelefone']['TELEFONE']);
							$telefone = $this->AppImport->getMobile($v2['NattMovelTelefone']['TELEFONE']);
						
							/**
							* Extrai o ano de atualizacao do telefone
							*/
							$year = $this->AppImport->getUpdated($v2['NattMovelTelefone']['ATUALIZACAO_SISTEMA']);

							/**
							* Trata os dados o telefone para a importacao
							*/
							$data = array(
								'Imobile' => array(
									'year' => $year,
									'ddd' => $ddd,
									'tel' => $telefone,
									'tel_full' => "{$ddd}{$telefone}",
									'tel_original' => $v2['NattMovelTelefone']['TELEFONE'],
									'lote' => $this->Imobile->lote,
									)
								);
							$this->AppImport->timing_end();

							/**
							* Executa a importacao do telefone
							* e carrega o id do telefone importado
							*/
							$this->AppImport->timing_ini(TUNING_LANDLINE_IMPORT);
							$this->importMobile($data);
							$this->AppImport->timing_end();


							/**
							* Inicializa a importacao do CEP do telefone encontrado
							* Trata os dados do CEP para a importacao
							*/				
							$this->AppImport->timing_ini(TUNING_ZIPCODE_LOAD);		
							$data = array(
								'Izipcode' => array(
									'code' => $this->AppImport->getZipcode($v2['NattMovelTelefone']['CEP']),
									'code_original' => $v2['NattMovelTelefone']['CEP'],
									'lote' => $this->Imobile->lote,
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

							$state_id = $this->AppImport->getState($v2['NattMovelTelefone']['UF'], $this->uf);
							$city_id = null;
							// $city_id = $this->AppImport->getCityId($v2['NattMovelTelefone']['CIDADE'], $state_id, $this->Izipcode->id);
							$city = $this->AppImport->getCity($v2['NattMovelTelefone']['CIDADE']);
							$zipcode = $this->AppImport->getZipcode($v2['NattMovelTelefone']['CEP']);
							$number = $this->AppImport->getStreetNumber($v2['NattMovelTelefone']['NUMERO'], $v2['NattMovelTelefone']['ENDERECO']);
							$complement = $this->AppImport->getComplement($v2['NattMovelTelefone']['COMP'], $v2['NattMovelTelefone']['ENDERECO']);
							$type_address = $this->AppImport->getTypeAddress($v2['NattMovelTelefone']['LOG'], $v2['NattMovelTelefone']['ENDERECO']);
							$neighborhood = $this->AppImport->getNeighborhood($v2['NattMovelTelefone']['BAIRRO']);

							/**
							* Trata o nome da rua
							*/
							$street = $this->AppImport->getStreet($v2['NattMovelTelefone']['ENDERECO']);

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
									'lote' => $this->Imobile->lote,
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
									'mobile_id' => $this->Imobile->id,
									'landline_id' => null,
									'address_id' => $this->Iaddress->id,
									'year' => $year,
									'lote' => $this->Imobile->lote,
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
							$this->AppImport->__counter('mobiles');
							$this->AppImport->__counter('addresses');
							$this->AppImport->__counter('zipcodes');
							$this->AppImport->__counter('associations');	
						}else{
							file_put_contents(ROOT . '/_db/settings/logs', "DOC: {$v2['NattMovelTelefone']['CPF_CNPJ']}\r\nESTADO: {$this->uf}\r\n\r\n\r\n", FILE_APPEND);
						}

						/**
						* Salva as contabilizacoes na base de dados
						*/					
						$this->AppImport->__counter('entities');
					}

					/**
					* Seta o registro como transferido
					*/
					$this->AppImport->timing_ini(TUNING_UPDATED);
					$this->NattMovelTelefone->updateAll(array('transf' => true), array('NattMovelTelefone.CPF_CNPJ' => $v2['NattMovelTelefone']['CPF_CNPJ'], 'NattMovelTelefone.TELEFONE' => $v2['NattMovelTelefone']['TELEFONE']));
					$this->AppImport->timing_end();
		        }
	    	}


	        /**
	        * Limpa toda a tabela depois q ela é importada para liberar espaço em disco
	        */
	        $this->NattMovelTelefone->query("TRUNCATE TABLE CEL2010.{$v}");

	        /**
	        * Registra todas as transacoes
	        */
	        $this->AppImport->timing_ini(TUNING_COMMIT_TRANSACTIONS);
	        $this->db['entity']->commit();
	        $this->db['mobile']->commit();
	        $this->db['address']->commit();
	        $this->db['zipcode']->commit();
	        $this->db['associations']->commit();
	        $this->AppImport->timing_end();
		}

		/**
		* Finaliza o processo de importacao
		*/
		exit();
	}
}
