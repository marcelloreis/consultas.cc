<?php
App::uses('ProjectController', 'Controller');
/**
 * Entities Controller
 *
 * O controller 'Entities' é responsável por gerenciar 
 * toda a lógica do model 'Entity' e renderizar o seu retorno na interface da aplicação.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Controller
 *
 * @property Entity $Entity
 */
class EntitiesController extends ProjectController {

	/**
	* Método people
	* Este método contem regras de negocios que permitem buscar na base de dados por quais quer parametro
	*
	* @override Metodo AppController.people
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function people($params=array()){
    	/**
		 * Se o campo "q" for igual a 1, simula o envio do form por get
		 * redirecionando para http://[domain]/[controller]/[action]/seach:value1/namedN:valueN
    	 */
    	$this->__post2get();

    	/**
    	* Carrega os dados pertinentes ao produto 'Pessoas'
    	*/
    	$doc = isset($this->params['named']['doc']) && !empty($this->params['named']['doc'])?$this->params['named']['doc']:false;
    	$name = isset($this->params['named']['name']) && !empty($this->params['named']['name'])?$this->params['named']['name']:false;
    	/**
    	* Verifica se o parametro 'doc' foi setado
    	*/
    	if($doc){
    		$people = $this->peopleByDoc($doc);
    	}else if($name){
    		$people = $this->peopleByName($name);
    	}

		/**
		* Carrega todas as chaves associativas da entidade
		*/
 		$associations = $this->loadAssociations($people);

    	/**
    	* Carrega os dados pertinentes ao produto 'Telefone Fixo'
    	*/
    	$landline = $this->landline($associations);

    	/**
    	* Carrega os dados pertinentes ao produto 'Endereços'
    	*/
    	$address = $this->address($associations);

// debug($people);
// debug($landline);
// debug($address);
// debug($associations);
// die;

    	$this->set(compact('people', 'landline', 'address'));
	}	

	/**
	* Método landline
	* Este método corresponde ao produto 'Telefones Fixos' e retorna todos os telefones fixos da entidade a partir dos id's contidos em $associations
	*
	* @return array
	*/
	public function landline($associations){
		$map = array();
		$assoc_landlines = array();
    	if($associations){
			/**
			* Agrupa os telefones por ANO e ID
			*/
			foreach ($associations as $k => $v) {
				if(!empty($v['EntityLandlineAddress']['landline_id'])){
					$assoc_landlines["{$v['EntityLandlineAddress']['year']}{$v['EntityLandlineAddress']['landline_id']}"] = $v['EntityLandlineAddress'];
				}
			}

			/**
			* Carrega todos os dados dos telefones contidos na associacao
			*/
    		foreach ($assoc_landlines as $k => $v) {
				$landline = $this->Entity->Landline->find('default_first', array(
					'conditions' => array('Landline.id' => $v['landline_id'])
					));
				$landline['Landline']['year'] = $v['year'];
				$map[$v['id']] = $landline;
    		}
    	}

    	return $map;
	}

	/**
	* Método address
	* Este método corresponde ao produto 'Endereços' e retorna todos os telefones fixos da entidade a partir dos id's contidos em $associations
	*
	* @return array
	*/
	public function address($associations){
		$map = array();
		$assoc_address = array();
    	if($associations){
			/**
			* Agrupa os telefones por ANO e ID
			*/
			foreach ($associations as $k => $v) {
				if(!empty($v['EntityLandlineAddress']['address_id'])){
					$assoc_address["{$v['EntityLandlineAddress']['year']}{$v['EntityLandlineAddress']['address_id']}"] = $v['EntityLandlineAddress'];
				}
			}

			/**
			* Carrega todos os dados dos telefones contidos na associacao
			*/
    		foreach ($assoc_address as $k => $v) {
				$address = $this->Entity->Address->find('default_first', array(
					'conditions' => array('Address.id' => $v['address_id'])
					));

				$address['Address']['year'] = $v['year'];
				$map[$v['id']] = $address;
    		}
    	}

    	return $map;
	}

	/**
	* Método peopleByDoc
	* Este método retorna todos os dados da entidade a partir do documento passados por parametro
	*
	* @return array
	*/
	private function peopleByDoc($doc){
		/**
		* Remove tudo que nao for numeros do documento
		*/
		$doc = preg_replace('/[^0-9]/', '', $doc);

		/**
		* Busca a entidade a partir do documento passado pelo parametro sem a verificacao dos trasheds e deleteds 'default_first'
		*/
		$map = $this->Entity->find('default_first', array('conditions' => array('Entity.doc' => $doc)));
		/**
		* Retorna FALSE caso nao encontre nenhum registro na base de dados
		*/
		$map = count($map)?$map:false;

		return $map;
	}		

	/**
	* Método peopleByName
	* Este método retorna todos os dados da entidade a partir do nome passados por parametro
	*
	* @return array
	*/
	private function peopleByName($name){
		//Regra de negocio aqui
	}		

	/**
	* Método loadAssociations
	* Este método carrega todas as chaves associativas da entidade, telefones fixos e seus respectivos endereços a partir do id da entidade
	*
	* @return array
	*/
	private function loadAssociations($entity){
		if(isset($entity['Entity']['id']) && !empty($entity['Entity']['id'])){
			$map = $this->Entity->EntityLandlineAddress->find('default_all', array(
				'conditions' => array('EntityLandlineAddress.entity_id' => $entity['Entity']['id']),
				'order' => array('EntityLandlineAddress.year' => 'desc')
				)
			);
		}   

		/**
		* Retorna FALSE caso nao encontre nenhum registro na base de dados
		*/
		$map = count($map)?$map:false;

		return $map;
	}
}
