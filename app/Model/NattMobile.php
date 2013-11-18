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


    public function next($row_count){
        $map = array();
        $pessoa = $this->find('all', array(
            'conditions' => array(
                'CPF_CNPJ !=' => '00000000000000000000',
                ),
            'limit' => "0,{$row_count}"
            ));

        if(count($pessoa)){
            foreach ($pessoa as $k => $v) {
                $map[$k]['pessoa']['CPF_CNPJ'] = $v['NattMobile']['CPF_CNPJ'];
                $map[$k]['pessoa']['NOME_RAZAO'] = $v['NattMobile']['NOME'];
                $map[$k]['pessoa']['MAE'] = '';
                $map[$k]['pessoa']['SEXO'] = '';
                $map[$k]['pessoa']['DT_NASCIMENTO'] = '';
                $map[$k]['telefone'][0]['TELEFONE'] = $v['NattMobile']['TELEFONE'];
                $map[$k]['telefone'][0]['DATA_ATUALIZACAO'] = ($v['NattMobile']['INST'] == '0000-00-00')?$v['NattMobile']['ATUALIZACAO_SISTEMA']:$v['NattMobile']['INST'];
                $map[$k]['telefone'][0]['NUMERO'] = $v['NattMobile']['NUMERO'];
                $map[$k]['telefone'][0]['COMPLEMENTO'] = $v['NattMobile']['COMP'];
                $map[$k]['telefone'][0]['endereco']['CEP'] = $v['NattMobile']['CEP'];
                $map[$k]['telefone'][0]['endereco']['UF'] = $v['NattMobile']['UF'];
                $map[$k]['telefone'][0]['endereco']['CIDADE'] = $v['NattMobile']['CIDADE'];
                $map[$k]['telefone'][0]['endereco']['BAIRRO'] = $v['NattMobile']['BAIRRO'];
                $map[$k]['telefone'][0]['endereco']['RUA'] = '';
                $map[$k]['telefone'][0]['endereco']['NOME_RUA'] = $v['NattMobile']['ENDERECO'];
                $map[$k]['telefone'][0]['endereco']['NUMERO'] = $v['NattMobile']['NUMERO'];
                $map[$k]['telefone'][0]['endereco']['COMPLEMENTO'] = $v['NattMobile']['COMP'];
            
                $this->offset($v['NattMobile']['CPF_CNPJ'], $v['NattMobile']['TELEFONE']);
            }
        }

        return $map;        
    }

    public function offset($doc, $tel){
        $this->deleteAll(array('NattMobile.CPF_CNPJ' => $doc, 'NattMobile.TELEFONE' => $tel), false);
    }

}