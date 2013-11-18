<?php
App::uses('AppModelClean', 'Model');
/**
 * NattFixoEndereco Model
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
 * NattFixoEndereco Model
 */
class NattFixoEndereco extends AppModelClean {
	public $useTable = false;
	public $recursive = -1;
	public $useDbConfig = 'natt';
	public $primaryKey = 'COD_END';
	public $order = 'NattFixoEndereco.COD_END';
}
