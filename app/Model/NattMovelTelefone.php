<?php
App::uses('AppModelClean', 'Model');
/**
 * NattMovelTelefone Model
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
 * NattMovelTelefone Model
 *
 * @property Country $Country
 * @property City $City
 */
class NattMovelTelefone extends AppModelClean {
	public $useTable = false;
    public $recursive = -1;
    public $useDbConfig = 'cel2010';
    public $primaryKey = 'CPF_CNPJ';
    public $displayField = 'NOME';
    public $order = 'NattMovelTelefone.CPF_CNPJ';


    public function read_tables(){
        $db = ConnectionManager::getDataSource('cel2010');
        $map = array_flip($db->listSources());
        unset($map['TELEFONE']);
        ksort($map);
        $sources = array_flip($map);
// $sources = array_slice($sources, 0, 2);

        return $sources;
    }     
}
