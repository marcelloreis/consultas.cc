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
    		'recursive' => '-1',
    		'conditions' => array(
                'CPF_CNPJ !=' => '00000000000000000000',
    			)
    		));
        if(isset($pessoa['NattFixoPessoa'])){
        	$map['pessoa'] = $pessoa['NattFixoPessoa'];
            $telefone = $this->NattFixoTelefone->find('all', array(
                'recursive' => '-1',
                'conditions' => array('CPF_CNPJ' => $pessoa['NattFixoPessoa']['CPF_CNPJ']),
                'order' => array('DATA_ATUALIZACAO' => 'DESC'),
                'limit' => 10
                ));

        	if(count($telefone)){
    	    	foreach ($telefone as $k => $v) {
    		    	$endereco = $this->NattFixoTelefone->NattFixoEndereco->find('first', array(
    		    		'recursive' => '-1',
    		    		'conditions' => array('COD_END' => $v['NattFixoTelefone']['COD_END'])
    		    		));
    	    		$map['telefone'][$k] = $v['NattFixoTelefone'];
                    if(isset($endereco['NattFixoEndereco'])){
                        $map['telefone'][$k]['endereco'] = $endereco['NattFixoEndereco'];
                    }
    			}
        	}

        	$this->offset($map['pessoa']['CPF_CNPJ']);
        }

		return $map;    	
    }

    public function offset($doc){
        $this->deleteAll(array('NattFixoPessoa.CPF_CNPJ' => $doc), false);
        $this->NattFixoTelefone->deleteAll(array('NattFixoTelefone.CPF_CNPJ' => $doc), false);
    }
}