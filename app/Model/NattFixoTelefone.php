<?php
App::uses('AppModelClean', 'Model');
/**
 * NattFixoTelefone Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Estado, 
 * é responsável também pela validação dos seus dados.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Model
 *
 * NattFixoTelefone Model
 *
 * @property Country $Country
 * @property City $City
 */
class NattFixoTelefone extends AppModelClean {
	public $useTable = false;
    public $recursive = -1;
    public $useDbConfig = 'natt';
    public $primaryKey = 'CPF_CNPJ';
    public $displayField = 'NOME_RAZAO';
    public $order = 'NattFixoTelefone.CPF_CNPJ';

   public $belongsTo = array(
        'NattFixoEndereco' => array(
            'className' => 'NattFixoEndereco',
            'foreignKey' => 'COD_END'
        )
    );	

    /**
    * Virtual fields
    *
    * @var string
    */
    public $virtualFields = array(
        'line' => "
        concat(
            NattFixoTelefone.DATA_ATUALIZACAO, ';', 
            NattFixoTelefone.TELEFONE, ';', 
            NattFixoTelefone.NUMERO, ';', 
            NattFixoTelefone.COMPLEMENTO
            )",
    );   
}
