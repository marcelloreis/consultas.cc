<?php
App::uses('AppModelClean', 'Model');
/**
 * NattFixoPessoa Model
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
 * NattFixoPessoa Model
 *
 * @property Country $Country
 * @property City $City
 */
class NattFixoPessoa extends AppModelClean {
    public $useTable = false;
    public $recursive = -1;
    public $useDbConfig = 'natt';
    public $primaryKey = 'CPF_CNPJ';
    public $displayField = 'NOME_RAZAO';
    public $order = 'NattFixoPessoa.CPF_CNPJ';

    /**
    * Virtual fields
    *
    * @var string
    */
    public $virtualFields = array(
        'line' => "
        concat(
            NattFixoPessoa.CPF_CNPJ, ';', 
            NattFixoPessoa.NOME_RAZAO, ';', 
            NattFixoPessoa.MAE, ';', 
            NattFixoPessoa.DT_NASCIMENTO, ';', 
            NattFixoTelefone.DATA_ATUALIZACAO, ';', 
            NattFixoTelefone.TELEFONE, ';', 
            NattFixoEndereco.CEP, ';', 
            NattFixoEndereco.CIDADE, ';', 
            NattFixoEndereco.RUA, ';', 
            NattFixoEndereco.NOME_RUA, ';', 
            NattFixoTelefone.NUMERO, ';', 
            NattFixoEndereco.BAIRRO, ';', 
            NattFixoTelefone.COMPLEMENTO
            )",
    );

    public $hasMany = array(
        'NattFixoTelefone' => array(
            'className' => 'NattFixoTelefone',
            'foreignKey' => 'CPF_CNPJ',
            'type' => 'INNER'
        )
    );

    public function next_binary($row_index){
        $entity = $this->find('list', array(
            'recursive' => -1,
            'fields' => array('CPF_CNPJ', 'CPF_CNPJ'),
            'conditions' => array(
                'NattFixoPessoa.CPF_CNPJ !=' => '00000000000000000000',
                ),
            'limit' => "{$row_index},1"
            ));

        $entities = $this->find('list', array(
            'recursive' => -1,
            'fields' => array("NattFixoTelefone.SEQ", 'line'),
            'joins' => array(
                array(
                    'table' => $this->NattFixoTelefone->useTable,
                    'alias' => 'NattFixoTelefone',
                    'type' => 'INNER',
                    'conditions' => array(
                        'NattFixoTelefone.CPF_CNPJ = NattFixoPessoa.CPF_CNPJ',
                    )
                ),
                array(
                    'table' => $this->NattFixoTelefone->NattFixoEndereco->useTable,
                    'alias' => 'NattFixoEndereco',
                    'type' => 'INNER',
                    'conditions' => array(
                        'NattFixoTelefone.COD_END = NattFixoEndereco.COD_END',
                    )
                ),
            ),
            'conditions' => array(
                'NattFixoTelefone.DATA_ATUALIZACAO >' => '2012',
                'NattFixoTelefone.CPF_CNPJ' => $entity[key($entity)],
                ),
            'order' => array('NattFixoTelefone.DATA_ATUALIZACAO' => 'DESC'),
            // 'limit' => "{$row_index},{$row_count}"
            'limit' => "5"
            ));


        return $entities;        
    }

    public function offset($doc){
        $this->deleteAll(array('NattFixoPessoa.CPF_CNPJ' => $doc), false);
        $this->NattFixoTelefone->deleteAll(array('NattFixoTelefone.CPF_CNPJ' => $doc), false);
    }
}