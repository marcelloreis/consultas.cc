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

    public $hasMany = array(
        'NattFixoTelefone' => array(
            'className' => 'NattFixoTelefone',
            'foreignKey' => 'CPF_CNPJ',
            'type' => 'INNER'
        )
    );

    public function next($row_count){
        $map = array();
        $pessoa = $this->find('all', array(
            'fields' => array(
                'NattFixoPessoa.CPF_CNPJ',
                'NattFixoPessoa.NOME_RAZAO',
                'NattFixoPessoa.MAE',
                'NattFixoPessoa.SEXO',
                'NattFixoPessoa.DT_NASCIMENTO',
                ),
            'conditions' => array(
                'CPF_CNPJ !=' => '00000000000000000000',
                ),
            'limit' => "0,{$row_count}"
            ));

        if(count($pessoa)){
            foreach ($pessoa as $k => $v) {
                $map[$k]['pessoa'] = $v['NattFixoPessoa'];
                $telefone = $this->NattFixoTelefone->find('all', array(
                    'fields' => array(
                        'NattFixoTelefone.TELEFONE',
                        'NattFixoTelefone.CPF_CNPJ',
                        'NattFixoTelefone.CEP',
                        'NattFixoTelefone.COD_END',
                        'NattFixoTelefone.COMPLEMENTO',
                        'NattFixoTelefone.NUMERO',
                        'NattFixoTelefone.DATA_ATUALIZACAO'
                        ),
                    'conditions' => array('CPF_CNPJ' => $v['NattFixoPessoa']['CPF_CNPJ']),
                    'group' => array('NattFixoTelefone.TELEFONE'),
                    'order' => array('DATA_ATUALIZACAO' => 'DESC'),
                    'limit' => 10
                    ));

                if(count($telefone)){
                    foreach ($telefone as $k2 => $v2) {
                        $endereco = $this->NattFixoTelefone->NattFixoEndereco->find('first', array(
                            'fields' => array(
                                'NattFixoEndereco.RUA',
                                'NattFixoEndereco.NOME_RUA',
                                'NattFixoEndereco.BAIRRO',
                                'NattFixoEndereco.CIDADE',
                                'NattFixoEndereco.UF',
                                'NattFixoEndereco.CEP'
                                ),
                            'conditions' => array('COD_END' => $v2['NattFixoTelefone']['COD_END']),
                            ));
                        $map[$k]['telefone'][$k2] = $v2['NattFixoTelefone'];
                        if(isset($endereco['NattFixoEndereco'])){
                            $map[$k]['telefone'][$k2]['endereco'] = $endereco['NattFixoEndereco'];
                        }
                    }
                }else{
                    /**
                    * Elimina as entidades que nao tiverem ao menos 1 telefone relacionado
                    */
                    unset($map[$k]);
                }

                $this->offset($v['NattFixoPessoa']['CPF_CNPJ']);
            }
        }

        return $map;        
    }

    public function offset($doc){
        $this->deleteAll(array('NattFixoPessoa.CPF_CNPJ' => $doc), false);
        $this->NattFixoTelefone->deleteAll(array('NattFixoTelefone.CPF_CNPJ' => $doc), false);
    }
}