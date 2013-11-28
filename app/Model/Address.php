<?php
App::uses('AppModelClean', 'Model');
/**
 * Address Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Address, 
 * é responsável também pela validação dos seus dados.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Model
 *
 * Address Model
 *
 * @property Address $Address
 * @property Address $Address
 */
class Address extends AppModelClean {

	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'street';

	/**
	* Recursive
	*
	* @var integer
	*/
	public $recursive = -1;		

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array(
		'State' => array(
			'type' => 'INNER'
			), 
		'City' => array(
			'type' => 'INNER'
			), 
		'Zipcode' => array(
			'type' => 'INNER'
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
            'foreignKey' => 'address_id',
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
		'Entity' => array(
			'className' => 'Entity',
			'joinTable' => 'associations',
			'foreignKey' => 'address_id',
			'associationForeignKey' => 'entity_id',
			'unique' => 'keepExisting',
		)
	);

	/**
	* Procura os enderecos a partir das informacoes passadas por parametro
	*/
	public function _findAddress($cond){
		$this->recursive = 1;

    	/**
    	* Inicializa a variavel que guardara as condicoes da busca
    	*/
    	$params = array('limit' => LIMIT_SEARCH);

    	/**
    	* Verifica se o CEP foi informado
    	*/
    	if(!empty($cond['zipcode'])){
    		$params['conditions']['Zipcode.code'] = preg_replace('/[^0-9]/', '', $cond['zipcode']);
    		/**
    		* Verifica se os numeros iniciais e finais foram informados
    		*/
    		if(!empty($cond['number_ini'])){
    			$cond['number_end'] = !empty($cond['number_end'])?$cond['number_end']:LIMIT_SEARCH;
    			$params['conditions']['Address.number BETWEEN ? AND ?'] = array($cond['number_ini'], $cond['number_end']);
    			$params['limit'] = $cond['number_end'];
    			$params['order'] = 'Address.number';
    		}
    	}else if(!empty($cond['street'])){
			$params['conditions']['Address.h_all'] = $cond['street'];
			/**
			* Verifica se o Estado foi informado
			*/
			if(!empty($cond['state_id'])){
				$params['conditions']['Address.state_id'] = $cond['state_id'];
			}
			/**
			* Verifica se a Cidade foi informada
			*/
			if(!empty($cond['city_id'])){
				$params['conditions']['Address.city_id'] = $cond['city_id'];
			}
    	}

		$addresses = $this->find('all', $params);

		return $addresses;
	}
}
