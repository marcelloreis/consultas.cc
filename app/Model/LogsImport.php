<?php
App::uses('AppModel', 'Model');
/**
 * LogsImport Model
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
 * LogsImport Model
 *
 * @property Country $Country
 * @property City $City
 */
class LogsImport extends AppModel {
	public $useTable = '_logs';
}
