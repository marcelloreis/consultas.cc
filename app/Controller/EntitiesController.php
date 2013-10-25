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
    	if($doc || $name){
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
	
	    	/**
	    	* Carrega os dados pertinentes ao produto 'Localizador'
	    	*/
	    	$locator = $address;
	
	    	/**
	    	* Carrega os dados pertinentes ao produto 'Familia'
	    	*/
	    	$family = $this->family($people, $address);
	
	    	$this->set(compact('people', 'landline', 'address', 'locator', 'family'));
    	}

// debug($people);
// debug($family);
// debug($landline);
// debug($address);
// debug($associations);
// die;

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
			* Agrupa os enderecos por ANO e ID
			*/
			foreach ($associations as $k => $v) {
				if(!empty($v['EntityLandlineAddress']['address_id'])){
					$assoc_address["{$v['EntityLandlineAddress']['year']}{$v['EntityLandlineAddress']['address_id']}"] = $v['EntityLandlineAddress'];
				}
			}

			/**
			* Carrega todos os dados dos enderecos contidos na associacao
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
	* Método family
	* Este método corresponde ao produto 'Familia' e retorna todos os possiveis familiares da entidade consultada
	*
	* @return array
	*/
	public function family($people, $address){
		$map = array('Family' => array('mother' => array(), 'children' => array(), 'spouse' => array(), 'brothers' => array()));

		/**
		* Verifica se a entidade pesquisada consiste como uma pessoa fisica
		*/
		if($people['Entity']['type'] != TP_CNPJ){
			/**
			* Busca os irmaos da entidade comparando outras entidades 
			* com o mesmo nome da mae
			*/
			if($people['Entity']['h_mother'] > 0){
				$brothers = $this->Entity->find('all', array(
					'fields' => '*',
					'conditions' => array(
						'Entity.id NOT' => $people['Entity']['id'],
						'Entity.h_all NOT' => $people['Entity']['h_all'],
						'Entity.h_mother' => $people['Entity']['h_mother'],
						)
					));

				foreach ($brothers as $k => $v) {
					$map['Family']['brothers'][] = $v;
				}

				/**
				* Caso nao encontre a quantidade suficiente de supostos irmaos, termina a busca com entidades com o mesmo sobrenome
				*/
				if(count($brothers) < LIMIT_BROTHERS){
					$limit_brothers = LIMIT_BROTHERS - count($brothers);
					$brothers_found = array();

					foreach ($brothers as $k => $v) {
						$brothers_found[] = $v['Entity']['id'];
					}

					$brothers = $this->Entity->find('all', array(
						'fields' => '*',
						'conditions' => array(
							'Entity.h_all NOT' => $people['Entity']['h_all'],
							'Entity.id NOT' => $people['Entity']['id'],
							'Entity.id NOT' => $brothers_found,
							'Entity.h_last1_last2' => $people['Entity']['h_last1_last2'],
							),
						'limit' => $limit_brothers
						));

					foreach ($brothers as $k => $v) {
						if($v['Entity']['name'] == $people['Entity']['mother']){
							$map['Family']['mother'] = $v;
						}else if($v['Entity']['mother'] == $people['Entity']['name']){
							$map['Family']['children'][] = $v;
						}else{
							$map['Family']['brothers'][] = $v;
						}

					}
				}
			}			

			/**
			* Percorre por todos os endereços encontrados da entidade buscando um possivel conjuje
			*/
			foreach ($address as $k => $v) {
				/**
				* Busca o conjuje da entidade comparando outras entidades 
				* com o endereço identico, sexo oposto e nome das maes diferente
				*/
				if(!empty($v['Address']['complement']) && !empty($v['Address']['zipcode_id']) && !empty($people['Entity']['gender']) && $people['Entity']['h_mother'] > 0 && !empty($v['Address']['number'])){
					switch ($people['Entity']['gender']) {
						case FEMALE:
							$opposit_gender = MALE;
							break;
						case MALE:
							$opposit_gender = FEMALE;
							break;
					}
					$spouse = $this->Entity->find('first', array(
						'fields' => '*',
						'joins' => array(
							array('table' => 'entities_landlines_addresses',
						        'alias' => 'EntityLandlineAddress',
						        'type' => 'INNER',
						        'conditions' => array(
						            'EntityLandlineAddress.entity_id = Entity.id',
						        )
						    ),
							array('table' => 'addresses',
						        'alias' => 'Address',
						        'type' => 'INNER',
						        'conditions' => array(
						            'Address.id = EntityLandlineAddress.address_id',
						        )
						    ),
							),
						'conditions' => array(
							'Entity.gender' => $opposit_gender,
							'Entity.h_mother >' => 0,
							'Entity.h_mother !=' => $people['Entity']['h_mother'],
							'Address.zipcode_id' => $v['Address']['zipcode_id'],
							'Address.h_complement' => $v['Address']['h_complement'],
							'Address.number' => $v['Address']['number'],
							),
						'limit' => 1
						));

					/**
					* Interrompe a verificacao caso encontre alguma entidade que atenda todos os criterios
					*/
					if(count($spouse)){
						$map['Family']['spouse'] = $spouse;
						break;
					}
				}
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
		$map = array();
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

	/**
	* Método sameApto
	* Este informa se a entidade esta localizado em apartamento, sala ou loja a partir do complemento
	* do endereço passado por parametro
	*
	* Caso encontre, retorna a numeraçao do ap/sala/loja sem a ultima casa numerica para ser usado como busca de vizinhos
	*
	* @return string
	*/
	private function sameApto($address){
		$lives = false;

		if(isset($address['Address']['complement']) && !empty($address['Address']['complement'])){
			preg_match('/(apartamento|apto|ap|sl|lj).?[0-9]*/si', $address['Address']['complement'], $vet);
			$lives = isset($vet[0])?substr(trim($vet[0]), 0, -1):false;
		}

		return $lives;
	}
	
}
