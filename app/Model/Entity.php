<?php
App::uses('AppModelClean', 'Model');
/**
 * Entity Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Entity, 
 * é responsável também pela validação dos seus dados.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Model
 *
 * Entity Model
 *
 * @property Entity $Entity
 */
class Entity extends AppModelClean {

	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'name';

	/**
	* Virtual fields
	*
	* @var string
	*/
	public $virtualFields = array(
    	'age' => "DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Entity.birthday)), '%Y')+0",
    	'gender_str' => "CASE Entity.gender WHEN 1 THEN 'Female' WHEN 2 THEN 'Male' ELSE null END"
	);

	/**
	* Recursive
	*
	* @var integer
	*/
	public $recursive = -1;

	/**
	* Validation rules
	*
	* @var array
	*/
	public $validate = array(
		'doc' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'O campo Doc deve ser preenchido corretamente.',
			),
		),
		'type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'O campo Type deve ser preenchido corretamente.',
			),
		),
	);

	/**
	* hasMany associations
	*
	* @var array
	*/
	public $hasMany = array(
        'Association' => array(
            'className' => 'Association',
            'foreignKey' => 'entity_id'
        )
	);	

	/**
	* hasAndBelongsToMany associations
	*
	* @var array
	*/
	public $hasAndBelongsToMany = array(
		'Address' => array(
			'className' => 'Address',
			'joinTable' => 'associations',
			'foreignKey' => 'address_id',
			'associationForeignKey' => 'entity_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Landline' => array(
			'className' => 'Landline',
			'joinTable' => 'associations',
			'foreignKey' => 'landline_id',
			'associationForeignKey' => 'entity_id',
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
