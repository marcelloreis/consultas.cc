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
	private $line;
	private $filename;

	/**
	* Método run_binary
	* Este método importa os telefones Fixos no modelo da base de dados do Natt para o Sistema
	*
	* @return void
	*/
	public function build_source($uf=null){
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
	        * Inicialiaza a varaivel que contara as transacoes efetuadas
	        */
	        $reload_transaction = 0;

			/**
			* Carrega o nome do arquivo que sera armazenado os dados
			*/
			$this->filename = ROOT . "/_db/source/{$this->uf}-natt-landlines-0-to-" . LIMIT_TABLE_IMPORTS;

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

	            	$this->filename = ROOT . "/_db/source/{$this->uf}-natt-landlines-{$i}-to-" . (LIMIT_TABLE_IMPORTS + $i);

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
	* Método juventudeweb
	* Este método importa o nome da mae e a data de aniversarios de todos os cidadoes brasileiros
	*
	* @return void
	*/
	public function juventudeweb(){
		/**
		* Carrega o Model da juventudeweb
		*/
		$this->loadModel('Juventudeweb');

		/**
		* Percorre por todos os CPFs validos Brasileiros
		*/
		for ($i=49773942; $i < 50000000000; $i++) { 

			if(!$this->Settings->active()){
				die;
			}

			if($this->AppImport->validateCpf($i)){
				/**
				* Carrega a informacao da base do governo federal
				*/
				// @$source = file_get_contents("http://www.juventudeweb.mte.gov.br/pesquisa_jovem.asp?cpf={$i}");

		        $url = "http://www.juventudeweb.mte.gov.br/pesquisa_jovem.asp?cpf={$i}";
		        $session = curl_init($url);
		        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		        $source = curl_exec($session);

				if(!empty($source)){
					/**
					* Extrai o nome do cidadao
					*/
					preg_match('/\("txtnome"\).value="(.*?)";\/\/NOmejovem/si', $source, $vet);
					$name = $vet[1];

					/**
					* Extrai o nome da mae do cidadao
					*/
					preg_match('/\("txtnomemae"\).value="(.*?)";\/\/bairro/si', $source, $vet);
					$mother = $vet[1];

					/**
					* Descarta registros invalidos
					*/
					if(!empty($name) && !strstr($name, 'RECEITA FEDERAL PARA USO DO SISTEMA') && !strstr($mother, 'MAE DESCONHECIDA')){
						/**
						* Trata os dados da entidade para a importacao
						*/
						//Carrega o tipo de documento
						$mother = empty($mother)?null:$mother;

						$data = array(
							'Juventudeweb' => array(
								'doc' => $i,
								'name' => $this->AppImport->clearName($name),
								'mother' => $this->AppImport->clearName($mother),
								)
							);

						$this->Juventudeweb->create();
						$this->Juventudeweb->save($data);
					}
				}else{
					file_put_contents(ROOT . '/_db/settings/logs', "{$i}\r\n", FILE_APPEND);					
				}
			}
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
        $this->db['address'] = $this->Iaddress->getDataSource();
        $this->db['zipcode'] = $this->Izipcode->getDataSource();
        $this->db['associations'] = $this->Iassociation->getDataSource();

        /**
        * Carrega a pasta onde contem os dados em txt
        */
        $this->Ilandline->folder = ROOT . "/_db/source";

        /**
        * Carrega o layout dos dados que sera importados
        */
        $this->Ilandline->source_year = 2013;

        /**
        * Carrega o lote da importacao
        */
        $map = $this->Ientity->find('first', array(
        	'recursive' => -1,
        	'fields' => array('Ientity.lote'),
        	'order' => array('Ientity.lote' => 'desc'),
        	));
        $this->Ilandline->lote = $map['Ientity']['lote']+1;

        /**
        * Informa o conteudo do layout ao sistema
        */
        $this->Ilandline->delimiter = ';';
        $this->Ilandline->map_pos = array(
			'doc' => 1,
			'name' => 2,
			'mother' => '',
			'gender' => '',
			'birthday' => '',
			'ddd' => '',
			'tel' => '',
			'tel_full' => 0,
			'zipcode' => 9,
			'cod_end' => '',
			'complement' => 5,
			'number' => 4,
			'year' => '',
			'type_address' => '',
			'street' => 3,
			'neighborhood' => 6,
			'city' => 7,
			'state' => 8,
    	);

		/**
		* Carrega o path de todos os arquivos contidos na pasta de recursos em texto
		*/
		$sources = $this->Ilandline->read_sources();

		/**
		* Contabiliza a quantidade de registros encontrado
		*/
		$this->qt_reg = 0;
		foreach ($sources as $k => $v) {
			/**
			* Verifica a primeira linha do arquivo é o layout
			*/
			$first_line = shell_exec("head -1 {$v}");
			if(preg_match("/{$this->Ilandline->delimiter}nome{$this->Ilandline->delimiter}|{$this->Ilandline->delimiter}endereco{$this->Ilandline->delimiter}|{$this->Ilandline->delimiter}complement{$this->Ilandline->delimiter}|{$this->Ilandline->delimiter}bairro{$this->Ilandline->delimiter}|{$this->Ilandline->delimiter}cidade{$this->Ilandline->delimiter}|{$this->Ilandline->delimiter}uf{$this->Ilandline->delimiter}/si", $first_line)){
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
	        $this->db['landline']->begin();
	        $this->db['address']->begin();
	        $this->db['zipcode']->begin();
	        $this->db['associations']->begin();

	        /**
	        * Inicialiaza a varaivel que contara as transacoes efetuadas
	        */
	        $reload_transaction = 0;

	        preg_match('/tel([a-z]{2})fixo\.txt$/si', $v, $vet);
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
			        $this->AppImport->timing_ini(COMMIT_TRANSACTIONS);
			        $this->db['entity']->commit();
			        $this->db['landline']->commit();
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
			        $this->AppImport->timing_ini(COMMIT_TRANSACTIONS);
			        $this->db['entity']->commit();
			        $this->db['landline']->commit();
			        $this->db['address']->commit();
			        $this->db['zipcode']->commit();
			        $this->db['associations']->commit();
			        $this->AppImport->timing_end();


			        /**
			        * Inicialiaza a transacao
			        */
			        $this->db['entity']->begin();
			        $this->db['landline']->begin();
			        $this->db['address']->begin();
			        $this->db['zipcode']->begin();
			        $this->db['associations']->begin();
	            }

	            /**
	            * Carrega o array com os dados a serem importados a partir linha extraida do arquivo
	            */
				$this->AppImport->timing_ini(TUNING_LOAD_NEXT_REGISTER);
	            $entity = $this->Ilandline->txt2array($ln);
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
					$this->AppImport->timing_ini(TUNING_ENTITY_LOAD);
					$data = array(
						'Ientity' => array(
							'doc' => $entity['DOC'],
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
							'lote' => $this->Ilandline->lote,
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
						$ddd = $this->AppImport->getDDD($entity['TEL_FULL']);
						$telefone = $this->AppImport->getTelefone($entity['TEL_FULL']);
					
						/**
						* Extrai o ano de atualizacao do telefone
						*/
						$year = $this->Ilandline->source_year;

						/**
						* Trata os dados o telefone para a importacao
						*/
						$data = array(
							'Ilandline' => array(
								'year' => $year,
								'ddd' => $ddd,
								'tel' => $telefone,
								'tel_full' => "{$ddd}{$telefone}",
								'tel_original' => $entity['TEL_FULL'],
								'lote' => $this->Ilandline->lote,
								)
							);
						$this->AppImport->timing_end();

						/**
						* Executa a importacao do telefone
						* e carrega o id do telefone importado
						*/
						$this->AppImport->timing_ini(TUNING_LANDLINE_IMPORT);
						$this->importLandline($data);
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
								'lote' => $this->Ilandline->lote,
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
								'lote' => $this->Ilandline->lote,
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
								'landline_id' => $this->Ilandline->id,
								'mobile_id' => null,
								'address_id' => $this->Iaddress->id,
								'year' => $year,
								'lote' => $this->Ilandline->lote,
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
	        $this->AppImport->timing_ini(COMMIT_TRANSACTIONS);
	        $this->db['entity']->commit();
	        $this->db['landline']->commit();
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