<?php
App::uses('AppModel', 'Model');
/**
 * City Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Cidade, 
 * é responsável também pela validação dos seus dados.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Model
 *
 * City Model
 *
 * @property State $State
 * @property Event $Event
 * @property Student $Student
 */
class City extends AppModel {

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
		'state_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'O campo Estado deve ser preenchido corretamente.',
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'O campo Nome deve ser preenchido corretamente.',
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'State' => array(
			'className' => 'State',
			'foreignKey' => 'state_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/**
	* Método loadByState
	* Este método carrega todos as cidades cadastradas a partir do estado passado pelo parametro
	*
	* @override Metodo AppController.loadByState
	* @param string $id
	* @return void
	*/
	public function loadByState($state_id=null){
		/**
		* Carrega todos as cidades cadastrados
		*/
		$cities = array();
		if($state_id){
			if(!Cache::read("cities_from_{$state_id}", 'components')){
					$cities = $this->find('list', array(
						'recursive' => -1,
						'fields' => array('City.id', 'City.name'),
						'conditions' => array(
							'City.state_id' => $state_id
							)
						));
					Cache::write("cities_from_{$state_id}", $cities, 'components');
			}
			$cities = Cache::read("cities_from_{$state_id}", 'components');
		}

		return $cities;
	}		
}
