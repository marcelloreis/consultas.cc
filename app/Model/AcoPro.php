<?php
App::uses('AppModel', 'Model');
/**
 * AcoPro Model
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
 * AcoPro Model
 *
 * @property Country $Country
 * @property City $City
 */
class AcoPro extends AppModel {

/**
 * Table
 *
 * @var string
 */
	public $useTable = 'acos';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'alias';

/**
 * Primary Key
 *
 * @var string
 */
	public $primaryKey = 'alias';

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array('Product');
}
