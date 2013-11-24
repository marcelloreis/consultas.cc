<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Packages/
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Controller
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Este controlador contem regras de negócio aplicadas ao model Group
 *
 * @package       app.Controller
 * @link http://.framework.nasza.com.br/2.0/controller/Packages.html
 */
class PackagesController extends AppController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'Packages';

	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($params=array()){
		//@override
		parent::index($params);
	}	

	/**
	* Método edit
	* Este método contem regras de negocios para adicionar e editar registros na base de dados
	*
	* @override Metodo AppController.edit
	* @param string $id
	* @return void
	*/
	public function edit($id=null){	
		//@override
		parent::edit($id);

		/**
		 * Verifica se o formulário foi submetido por post
		 */
		if(!empty($id)){
			/**
			* Carrega todos os clientes e seus respectivos usuarios que pertencem ao pacote editado
			*/
			$clients = $this->Package->Client->find('all', array(
				'conditions' => array('Client.package_id' => $id)
				));

			/**
			* Carrega os produtos que pertencem ao pacote do cliente
			*/
			$products = $this->Package->PackagesProduct->find('list', array(
				'fields' => 'product_id',
				'conditions' => array('PackagesProduct.package_id' => $id)
				));

			/**
			* Carrega os ACOs relacionados ao produtos encontrados do pacote
			*/
			$acos_allow = $this->Package->Product->find('list', array(
				'fields' => array('Product.aco_id', 'Product.aco_id'),
				'conditions' => array('Product.id' => $products)
				));				

			/**
			* Monta o action do produto/aco completo
			*/
	        $acos = $this->Acl->Aco->find('all', array('order' => 'Aco.lft ASC', 'recursive' => -1));
	        $parents = array();
	        $acos_full_path = array();
	        foreach ($acos as $key => $data) {
	            $aco =& $acos[$key];
	            $aco_id = $aco['Aco']['id'];

	            // Generate path
	            if ($aco['Aco']['parent_id'] && isset($parents[$aco['Aco']['parent_id']])) {
	                $parents[$aco_id] = $parents[$aco['Aco']['parent_id']] . '/' . $aco['Aco']['alias'];
	            } else {
	                $parents[$aco_id] = $aco['Aco']['alias'];
	            }

        		$acos_full_path[$aco_id] = $parents[$aco_id];
	        }

	        /**
	        * Percorre por todos os clientes que pertencem ao pacote
	        */
	        foreach ($clients as $k => $v) {
	        	/**
	        	* Percorre por todos os usuarios do cliente pertencente ao pacote
	        	*/
	        	foreach ($v['User'] as $k2 => $v2) {
					/**
					* Concede todas as permissoes sobre os produtos contidos no pacote do cliente
					*/
					foreach ($acos_full_path as $k3 => $v3) {
						$node = array('model' => 'User', 'foreign_key' => $v2['id']);
						$action = $v3;
						$perm = in_array($k3, $acos_allow)?'allow':'deny';		
						$this->Acl->{$perm}($node, $action);
					}			        
	        	}
	        }
		}

		$products_active = array();
		if(isset($this->data['Product'])){
			foreach ($this->data['Product'] as $k => $v) {
				$products_active[$k] = (int)$v['id'];
			}
		}
		$this->set(compact('products_active'));
	}
}