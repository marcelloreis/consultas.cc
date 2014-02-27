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
            ifnull(NattFixoPessoa.CPF_CNPJ, ''), ';', 
            ifnull(NattFixoPessoa.NOME_RAZAO, ''), ';', 
            ifnull(NattFixoPessoa.MAE, ''), ';', 
            ifnull(NattFixoPessoa.DT_NASCIMENTO, '')
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
        /**
        * Variavel que ira concatenar a linha de dados retonarda
        */
        $ln_map = array();
        $ln_entity = '';
        $ln_landline = '';
        $ln_address = '';

        $entity = $this->find('list', array(
            'recursive' => -1,
            'fields' => array('CPF_CNPJ', 'line'),
            'conditions' => array(
                // 'NattFixoPessoa.CPF_CNPJ' => '33000118000250',
                // 'NattFixoPessoa.CPF_CNPJ' => '09685634734',
                'NattFixoPessoa.CPF_CNPJ !=' => '00000000000000000000',
                ),
            'limit' => "{$row_index},1"
            ));

        /**
        * Carrega os dados da entidade
        */
        $ln_entity = reset($entity);
     
        /**
        * Carrega o documento da entidade
        */
        $doc = key($entity);

        if(count($entity) && !empty($doc)){
            $map_landlines = $this->NattFixoTelefone->find('all', array(
                'fields' => array(
                    'NattFixoTelefone.COD_END', 
                    'NattFixoTelefone.TELEFONE', 
                    'line'
                    ),
                'conditions' => array(
                    'NattFixoTelefone.CPF_CNPJ' => $doc,
                    'NattFixoTelefone.DATA_ATUALIZACAO >=' => '2010'
                    ),
                'order' => array('DATA_ATUALIZACAO' => 'DESC'),
                'limit' => 5
                ));
            krsort($map_landlines);

                $landlines_grouped = array();
                if(count($map_landlines)){
                    /**
                    * Agrupa os telefones iguais ordenando a atualizacao do mais novo para mais antigo
                    */
                    foreach ($map_landlines as $k => $v) {
                        $landlines_grouped[$v['NattFixoTelefone']['TELEFONE']] = $v;
                    }            

                    /**
                    * Percorre por todos os telefones encontrados e buscos seus respectivos enderecos
                    */
                    foreach ($landlines_grouped as $k => $v) {
                        /**
                        * Carrega a os dados do telefone
                        */
                        $ln_landline = ";{$v['NattFixoTelefone']['line']}";

                        $address = $this->NattFixoTelefone->NattFixoEndereco->find('first', array(
                            'fields' => array('line'),
                            'conditions' => array('COD_END' => $v['NattFixoTelefone']['COD_END']),
                            ));
                        if(isset($address['NattFixoEndereco'])){
                            /**
                            * Carrega os dados do endereço
                            */
                            $ln_address = ";{$address['NattFixoEndereco']['line']}";
                        }

                        /**
                        * Concatena todos os dados encontrados
                        */
                        $ln_map[] = "{$ln_entity}{$ln_landline}{$ln_address}";
                    }
                }         
        }

        return $ln_map;        
    }

    public function offset($doc){
        $this->deleteAll(array('NattFixoPessoa.CPF_CNPJ' => $doc), false);
        $this->NattFixoTelefone->deleteAll(array('NattFixoTelefone.CPF_CNPJ' => $doc), false);
    }
}