<?php
App::uses('AppModelClean', 'Model');
/**
 * NattMobile Model
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
 * NattMobile Model
 *
 * @property Country $Country
 * @property City $City
 */
class NattMobile extends AppModelClean {
    public $useTable = false;
	public $recursive = -1;
	public $useDbConfig = 'cel2010';
    public $primaryKey = 'CPF_CNPJ';
    public $displayField = 'NOME';
    public $order = 'NattMobile.CPF_CNPJ';


    public function next(){
        $map = array();
        $pessoa = $this->find('first', array(
            'conditions' => array(
                'CPF_CNPJ !=' => '00000000000000000000',
                )
            ));

        if(isset($pessoa['NattMobile'])){
            $map['pessoa']['CPF_CNPJ'] = $pessoa['NattMobile']['CPF_CNPJ'];
            $map['pessoa']['NOME_RAZAO'] = $pessoa['NattMobile']['NOME'];
            $map['pessoa']['MAE'] = '';
            $map['pessoa']['SEXO'] = '';
            $map['pessoa']['DT_NASCIMENTO'] = '';
            $map['telefone'][0]['TELEFONE'] = $pessoa['NattMobile']['TELEFONE'];
            $map['telefone'][0]['DATA_ATUALIZACAO'] = $pessoa['NattMobile']['ATUALIZACAO_SISTEMA'];
            $map['telefone'][0]['NUMERO'] = $pessoa['NattMobile']['NUMERO'];
            $map['telefone'][0]['COMPLEMENTO'] = $pessoa['NattMobile']['COMP'];
            $map['telefone'][0]['endereco']['CEP'] = $pessoa['NattMobile']['CEP'];
            $map['telefone'][0]['endereco']['UF'] = $pessoa['NattMobile']['UF'];
            $map['telefone'][0]['endereco']['CIDADE'] = $pessoa['NattMobile']['CIDADE'];
            $map['telefone'][0]['endereco']['BAIRRO'] = $pessoa['NattMobile']['BAIRRO'];
            $map['telefone'][0]['endereco']['RUA'] = '';
            $map['telefone'][0]['endereco']['NOME_RUA'] = $pessoa['NattMobile']['ENDERECO'];
            $map['telefone'][0]['endereco']['NUMERO'] = $pessoa['NattMobile']['NUMERO'];
            $map['telefone'][0]['endereco']['COMPLEMENTO'] = $pessoa['NattMobile']['COMP'];

            $this->offset($pessoa['NattMobile']['CPF_CNPJ'], $pessoa['NattMobile']['TELEFONE']);
        }

        return $map;        
    }

    public function offset($doc, $tel){
        $this->deleteAll(array('NattMobile.CPF_CNPJ' => $doc, 'NattMobile.TELEFONE' => $tel), false);
    }

}