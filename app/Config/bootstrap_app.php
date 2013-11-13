<?php
/**
 * Textos padroes da aplicação
 */	
define('TITLE_HEADER', 'NZ Dados');
define('TITLE_APP', 'NZ Dados');
define('VERSION_APP', '1.0');
define('COPYRIGHT', 'Nasza Produtora');
define('COPYRIGHT_LINK', 'http://nasza.com.br');


/**
 * IDs padroes do projeto
 */
define('TP_CPF', 1);
define('TP_CNPJ', 2);
define('TP_AMBIGUO', 3);
define('TP_INVALID', 4);

define('LIMIT_HASH', 5);
define('LIMIT_SEARCH', 50);
define('LIMIT_FAMILY', 6);
define('LIMIT_NEIGHBORS', 6);
define('LIMIT_BUILD_SOURCE', 100);
define('LIMIT_TABLE_IMPORTS', 10000);
define('PROGRESSBAR_INTERVAL', 1);

define('THEME', 'theme-satblue');

define('FEMALE', 1);
define('MALE', 2);

/**
* Codigos dos logs de importacao
*/
define('IMPORT_BEGIN', 1);
define('IMPORT_ENTITY_FAIL', 2);
define('IMPORT_LANDLINE_INCONSISTENT', 3);
define('IMPORT_LANDLINE_FAIL', 4);
define('IMPORT_ZIPCODE_INCONSISTENT', 5);
define('IMPORT_ZIPCODE_FAIL', 6);
define('IMPORT_ADDRESS_FAIL', 7);
define('IMPORT_ASSOCIATION_FAIL', 8);
define('IMPORT_PAUSED', 9);

/**
* Codigos dos times de importacao
*/
//Calcula o total de registros que sera importado
define('TUNING_COUNT_EXTRACT', 1);
//Verifica se a chave do modulo de importacao esta ativa
define('TUNING_ON_OF', 2);
//Carrega o proximo registro das tabelas de pessoa, telefone e endereco q ainda nao foram importado
define('TUNING_LOAD_NEXT_REGISTER', 3);
//Trata os dados da entidade para a importacao
define('TUNING_ENTITY_LOAD', 4);
//Executa a importacao da tabela Entity
define('TUNING_ENTITY_IMPORT', 5);
//Trata os dados o telefone para a importacao
define('TUNING_LANDLINE_LOAD', 6);
//Executa a importacao do telefone
define('TUNING_LANDLINE_IMPORT', 7);
//Trata os dados do CEP para a importacao
define('TUNING_ZIPCODE_LOAD', 8);
//Executa a importacao do CEP
define('TUNING_ZIPCODE_IMPORT', 9);
//Trata os dados do endereço para a importacao
define('TUNING_ADDRESS_LOAD', 10);
//Executa a importacao do Endereço
define('TUNING_ADDRESS_IMPORT', 11);
//Carrega todos os id coletados ate o momento
define('TUNING_LOAD_ALL_DATA', 12);
//Executa a importacao dos dados coletados ate o momento
define('TUNING_IMPORT_ALL_DATA', 13);
//Carrega o arquivo com os dados serializados que serao importados
define('TUNING_FILE_LOAD', 14);
//Serializa o array
define('TUNING_SERIALIZE', 15);