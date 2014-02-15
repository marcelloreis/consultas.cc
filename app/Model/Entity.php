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
	* Behavior
	*/
	// public $actsAs = array('Containable');

	/**
	* Virtual fields
	*
	* @var string
	*/
	public $virtualFields = array(
    	'age' => "DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Entity.birthday)), '%Y')+0",
    	'gender_str' => "CASE Entity.gender WHEN 1 THEN 'Feminino' WHEN 2 THEN 'Masculino' ELSE null END",
    	'type_str' => "CASE Entity.type WHEN 1 THEN 'Fisica' WHEN 2 THEN 'Juridica' WHEN 3 THEN 'Ambiguo' WHEN 4 THEN 'Invalido' ELSE null END",
    	'first_name' => "SUBSTRING_INDEX(Entity.name, ' ', 1)",
    	'birthday_str' => "DATE_FORMAT(Entity.birthday, '%d/%m/%Y')",
	);

	/**
	* Recursive
	*
	* @var integer
	*/
	// public $recursive = -1;

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
            'foreignKey' => 'entity_id',
            'order' => array('Association.year' => 'desc'),
            'type' => 'INNER'
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
			'foreignKey' => 'entity_id',
			'associationForeignKey' => 'address_id',
			'unique' => 'keepExisting',
			'order' => array('Association.year' => 'desc')
		),
		'Landline' => array(
			'className' => 'Landline',
			'joinTable' => 'associations',
			'foreignKey' => 'entity_id',
			'associationForeignKey' => 'landline_id',
			'unique' => 'keepExisting',
			'order' => array('Association.year' => 'desc')
		),
		'Mobile' => array(
			'className' => 'Mobile',
			'joinTable' => 'associations',
			'foreignKey' => 'entity_id',
			'associationForeignKey' => 'mobile_id',
			'unique' => 'keepExisting',
			'order' => array('Association.year' => 'desc')
		)
	);

	/**
	* Procura a entidade a partir do hash
	*/
	public function _findName($hash){	
		$this->recursive = 1;
		$this->limit = LIMIT_SEARCH;

		/**
		* Procura por entidades com o nome identico ao passado por parametro
		*/
		$entities = $this->find('all', array(
			'conditions' => array(
				'Entity.h_all' => $hash['h_all'],
			),
			'limit' => LIMIT_SEARCH
		));		

		/**
		* Caso nao encontre nenhuma entidade com o mesmo nome pesquisado
		* procura por outras entidades com o mesmo sobre nome
		*/
		if(!count($entities)){
			$entities = $this->find('all', array(
				'conditions' => array(
					'Entity.h2' => $hash['h2'],
					'Entity.h3' => $hash['h3'],
					'Entity.h4' => $hash['h4'],
					'Entity.h5' => $hash['h5'],
					),
				'limit' => LIMIT_SEARCH
				));		
		}

		/**
		* Caso nao encontre nenhuma entidade com o mesmo sobre nome
		* procura por outras entidades os ultimos 2 nomes iguais
		*/
		if(!count($entities)){
			$entities = $this->find('all', array(
				'conditions' => array(
					'Entity.h_last1_last2' => $hash['h_last1_last2'],
					),
				'limit' => LIMIT_SEARCH
				)
			);		
		}

		/**
		* Caso nao encontre nenhuma entidade com os ultimos 2 nomes iguais
		* procura por entidades com qualquer semelhança no nome
		*/			
		if(!count($entities)){
			$entities = $this->find('all', array(
				'conditions' => array(
					'OR' => array(
						'Entity.h_all' => $hash['h_all'],
						'Entity.h_last' => $hash['h_last'],
						'Entity.h_first_last' => $hash['h_first_last'],
						'Entity.h_last1_last2' => $hash['h_last1_last2'],
						'Entity.h_first1_first2' => $hash['h_first1_first2'],
						)
					),
				'limit' => LIMIT_SEARCH
				)
			);		
		}

		return $entities;
	}
}
