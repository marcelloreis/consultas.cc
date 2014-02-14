<?php
/**
 * Textos padroes da aplicação
 */	
define('TITLE_HEADER', 'Check List');
define('TITLE_APP', 'Check List');
define('VERSION_APP', '1.0');
define('COPYRIGHT', 'NASZA - Produtora Full Service');
define('COPYRIGHT_LINK', 'http://nasza.com.br');
define('PROJECT_LINK', 'http://www.checklist.inf.br/');
define('EMAIL_NO_REPLAY', 'noreply@checklist.inf.br');

define('COMPANY_CORPORATE_NAME', 'Check List - Tecnologia em banco de dados');
define('COMPANY_FANCY_NAME', 'Check List - Tecnologia em banco de dados');
define('COMPANY_PHONE', '(11)  4063-9943 ');
define('COMPANY_EMAIL_BUSINESSES', 'comercial@checklist.inf.br');
define('COMPANY_CITY', 'Serra');
define('COMPANY_STATE', 'Espirito Santo');
define('COMPANY_ADDRESS', 'Rua: Marrecas, Nº: 62 - SALA: 1, Bairro: Porto Canoa - CEP:29168-450 ' . COMPANY_CITY . ', ' . COMPANY_STATE);
define('COMPANY_CNPJ', '99.999.999/9999-99');
define('COMPANY_INSCRIPTION', '999.999-99');
define('COMPANY_PARTNER_1', 'Marcelo Martins dos Reis');
define('COMPANY_PARTNER_2', 'Rodrigo Toledo Beltrão');
define('COMPANY_PARTNER_1_ID', '1.717-320');
define('COMPANY_PARTNER_2_ID', 'x.xxx-xxx');
define('COMPANY_PARTNER_1_ID_ISSUED', 'SSP');
define('COMPANY_PARTNER_2_ID_ISSUED', 'SSP');
define('COMPANY_PARTNER_1_ID_ISSUED_DATE', '12/12/2013');
define('COMPANY_PARTNER_2_ID_ISSUED_DATE', '12/12/2013');
define('COMPANY_PARTNER_1_CPF', '101.531.087-70');
define('COMPANY_PARTNER_2_CPF', '004.459.287-60');

/**
 * IDs padroes do projeto
 */
define('PRODUCT_PESSOAS', 1);
define('PRODUCT_EMPRESAS', 2);
define('PRODUCT_OBITO', 3);
define('PRODUCT_BACEN', 4);
define('PRODUCT_EXTRA_TELEFONE_MOVEL', 5);
define('PRODUCT_EXTRA_TELEFONE_FIXO', 6);
define('PRODUCT_LOCALIZADOR', 7);
define('PRODUCT_POSSIVEIS_PARENTES', 8);
define('PRODUCT_VIZINHOS', 9);
define('PRODUCT_SMS', 10);
define('PRODUCT_MAILING', 11);

define('CAMPAIGN_NOT_PROCESSED', 1);
define('CAMPAIGN_RUN_PROCESSED', 2);
define('CAMPAIGN_PROCESSED', 3);

define('TP_TEL_LANDLINE', 1);
define('TP_TEL_MOBILE', 2);

define('TP_CPF', 1);
define('TP_CNPJ', 2);
define('TP_AMBIGUO', 3);
define('TP_INVALID', 4);

define('TP_SEARCH_ID', 1);
define('TP_SEARCH_DOC', 2);
define('TP_SEARCH_PHONE', 3);
define('TP_SEARCH_MOBILE', 4);
define('TP_SEARCH_NAME', 5);
define('TP_SEARCH_ADDRESS', 6);
define('TP_SEARCH_EXTRA_MOBILE', 7);
define('TP_SEARCH_EXTRA_LANDLINE', 8);
define('TP_SEARCH_EXTRA_LOCATOR', 9);
define('TP_SEARCH_EXTRA_FAMILY', 10);
define('TP_SEARCH_EXTRA_NEIGHBORS', 11);
define('TP_SEARCH_SMS', 12);
define('TP_SEARCH_MAILING', 13);

define('LIMIT_HASH', 5);
define('LIMIT_SEARCH', 50);
define('LIMIT_FAMILY', 6);
define('LIMIT_NEIGHBORS', 6);
define('LIMIT_BUILD_SOURCE', 100);
define('LIMIT_TABLE_IMPORTS', 100000);
/**
* Reload
*/
// define('LIMIT_TABLE_IMPORTS', 500);
define('PROGRESSBAR_INTERVAL', 1);

define('THEME', 'theme-satblue');

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
//Commita as transacoes executadas
define('COMMIT_TRANSACTIONS', 16);

/**
* Configuracao do cache das entidades
*/
Cache::config('entities', array(
    'engine' => 'File',
    'duration' => '+999 days',
    'probability' => 100,
    'path' => CACHE . 'entities' . DS,
));

/**
* Configuracao do cache dos componentes do sistema
*/
Cache::config('components', array(
    'engine' => 'File',
    'duration' => '+999 days',
    'probability' => 100,
    'path' => CACHE . 'components' . DS,
));