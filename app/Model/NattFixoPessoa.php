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
            'type' => 'inner'
        )
    );

    public function next(){
        $map = array();
        $pessoa = $this->find('first', array(
            'conditions' => array(
                'CPF_CNPJ !=' => '00000000000000000000',
                )
            ));

        if(isset($pessoa['NattFixoPessoa'])){
            $map['pessoa'] = $pessoa['NattFixoPessoa'];
            $telefone = $this->NattFixoTelefone->find('all', array(
                'conditions' => array('CPF_CNPJ' => $pessoa['NattFixoPessoa']['CPF_CNPJ']),
                'order' => array('DATA_ATUALIZACAO' => 'DESC'),
                'limit' => 10
                ));

            if(count($telefone)){
                foreach ($telefone as $k => $v) {
                    $endereco = $this->NattFixoTelefone->NattFixoEndereco->find('first', array(
                        'conditions' => array('COD_END' => $v['NattFixoTelefone']['COD_END'])
                        ));
                    $map['telefone'][$k] = $v['NattFixoTelefone'];
                    if(isset($endereco['NattFixoEndereco'])){
                        $map['telefone'][$k]['endereco'] = $endereco['NattFixoEndereco'];
                    }
                }
            }else{
                $map = array();
            }

            $this->offset($pessoa['NattFixoPessoa']['CPF_CNPJ']);
        }

        return $map;        
    }

    public function offset($doc){
        $this->deleteAll(array('NattFixoPessoa.CPF_CNPJ' => $doc), false);
        $this->NattFixoTelefone->deleteAll(array('NattFixoTelefone.CPF_CNPJ' => $doc), false);
    }

    public function next_block($offset, $row_count){
        $map = array();
        $pessoa = $this->find('all', array(
            'conditions' => array(
                'CPF_CNPJ !=' => '00000000000000000000',
                ),
            'limit' => "{$offset},{$row_count}"
            ));

        if(count($pessoa)){
            foreach ($pessoa as $k => $v) {
                $map[$k]['pessoa'] = $v['NattFixoPessoa'];
                $telefone = $this->NattFixoTelefone->find('all', array(
                    'conditions' => array('CPF_CNPJ' => $v['NattFixoPessoa']['CPF_CNPJ']),
                    'order' => array('DATA_ATUALIZACAO' => 'DESC'),
                    'limit' => 10
                    ));

                if(count($telefone)){
                    foreach ($telefone as $k2 => $v2) {
                        $endereco = $this->NattFixoTelefone->NattFixoEndereco->find('first', array(
                            'conditions' => array('COD_END' => $v2['NattFixoTelefone']['COD_END'])
                            ));
                        $map[$k]['telefone'][$k2] = $v2['NattFixoTelefone'];
                        if(isset($endereco['NattFixoEndereco'])){
                            $map[$k]['telefone'][$k2]['endereco'] = $endereco['NattFixoEndereco'];
                        }
                    }
                }else{
                    $map[$k] = array();
                }

                // $this->offset_block($v['NattFixoPessoa']['CPF_CNPJ']);
            }
        }

        return $map;        
    }

    public function offset_block($doc){
        $this->deleteAll(array('NattFixoPessoa.CPF_CNPJ' => $doc), false);
        $this->NattFixoTelefone->deleteAll(array('NattFixoTelefone.CPF_CNPJ' => $doc), false);
    }


    public function buildSource($uf, $extracted){

        for ($i=0; $i < $extracted; $i+=LIMIT_BUILD_SOURCE) { 
            $map = array();
            $pessoa = $this->find('all', array(
                'recursive' => '-1',
                'conditions' => array(
                    'CPF_CNPJ !=' => '00000000000000000000',
                    ),
                'limit' => "{$i}," . LIMIT_BUILD_SOURCE
                ));
            
            foreach ($pessoa as $k => $v) {
                if(isset($v['NattFixoPessoa'])){
                    $map['pessoa'] = $v['NattFixoPessoa'];
                    $telefone = $this->NattFixoTelefone->find('all', array(
                        'recursive' => '-1',
                        'conditions' => array('CPF_CNPJ' => $v['NattFixoPessoa']['CPF_CNPJ']),
                        'order' => array('DATA_ATUALIZACAO' => 'DESC'),
                        'limit' => 10
                        ));

                    if(count($telefone)){
                        foreach ($telefone as $k2 => $v2) {
                            $endereco = $this->NattFixoTelefone->NattFixoEndereco->find('first', array(
                                'recursive' => '-1',
                                'conditions' => array('COD_END' => $v2['NattFixoTelefone']['COD_END'])
                                ));
                            $map['telefone'][$k2] = $v2['NattFixoTelefone'];
                            if(isset($endereco['NattFixoEndereco'])){
                                $map['telefone'][$k2]['endereco'] = $endereco['NattFixoEndereco'];
                            }
                        }

                        $map_serialized = json_encode($map);
                        $this->setSource('_source_' . strtolower($uf));
                        $this->create();
                        $this->save(array('NattFixoPessoa' => array('CPF_CNPJ' => $v['NattFixoPessoa']['CPF_CNPJ'], 'source' => $map_serialized)));
                        $this->setSource("PESSOA_{$uf}");
                    }
                }
            }
        }
    }

    public function next_json(){
        $map = array();
        $pessoa = $this->find('first', array(
            'order' => 'NattFixoPessoa.CPF_CNPJ'
            ));
        $map = json_decode($pessoa['NattFixoPessoa']['source']);
 
        $this->offset_json($pessoa['NattFixoPessoa']['CPF_CNPJ']);
        return $map;        
    }

    public function offset_json($doc){
        $this->deleteAll(array('NattFixoPessoa.CPF_CNPJ' => $doc), false);
    }

}