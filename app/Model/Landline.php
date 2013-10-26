<?php
App::uses('AppModelClean', 'Model');
/**
 * Landline Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Landline, 
 * é responsável também pela validação dos seus dados.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Model
 *
 * Landline Model
 *
 * @property Address $Address
 * @property Landline $Landline
 */
class Landline extends AppModelClean {

	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'tel_full';

	/**
	* Recursive
	*
	* @var integer
	*/
	public $recursive = -1;	

	/**
	* hasMany associations
	*
	* @var array
	*/
	public $hasMany = array(
        'Association' => array(
            'className' => 'Association',
            'foreignKey' => 'landline_id'
        )
	);	

	/**
	* hasAndBelongsToMany associations
	*
	* @var array
	*/
	public $hasAndBelongsToMany = array(
		'Entity' => array(
			'className' => 'Entity',
			'joinTable' => 'associations',
			'foreignKey' => 'entity_id',
			'associationForeignKey' => 'landline_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
}
