<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Clients/
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
 * @link http://.framework.nasza.com.br/2.0/controller/Clients.html
 */
class ClientsController extends AppController {

	/**
	* Controller name
	*
	* @var string
	*/
	public $name = 'Clients';

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
		$this->isRedirect = false;

		//@override
		parent::edit($id);		

		/**  
		 * Verifica se o formulário foi submetido por post
		 */
		if ($this->request->is('post') || $this->request->is('put')) {
			/**
			* Carrega todos os produtos que pertencem ao pacote selecionado
			*/
			if(!empty($this->request->data['Client']['package_id'])){
				/**
				* Carrega os ACOs relacionados ao produtos encontrados do pacote
				*/
				$package = $this->Client->Package->find('first', array(
					'conditions' => array('Package.id' => $this->request->data['Client']['package_id'])
					));

				/**
				* Carrega os produtos que pertencem ao pacote do cliente
				*/
				$products = $this->Client->Package->PackagesProduct->find('list', array(
					'fields' => 'product_id',
					'conditions' => array('PackagesProduct.package_id' => $package['Package']['id'])
					));

				/**
				* Carrega os ACOs relacionados ao produtos encontrados do pacote
				*/
				$acos_allow = $this->Client->Package->Product->find('list', array(
					'fields' => array('Product.aco_id', 'Product.aco_id'),
					'conditions' => array('Product.id' => $products)
					));

				/**
				* Cria um usuario default para o cliente cadastrado
				*/
				$map_name = explode(' ', $this->request->data['Client']['fancy_name']);
				$data = array(
					'User' => array(
						'client_id' => $this->Client->id,
						'group_id' => $package['Group']['id'],
						'name' => $this->request->data['Client']['fancy_name'],
						'given_name' => $map_name[0],
						'password' => preg_replace('/[^0-9]/si', '', $this->request->data['Client']['tel1']),
						'email' => $this->request->data['Client']['email'],
						'status' => true,
						)
					);
				$this->Package->User->create();

				/**
				* Verifica se existe alguma conta criada com o 
				*/
				$hasUser = $this->Package->User->findByEmail($this->request->data['Client']['email']);
				if(count($hasUser)){
					$data['User']['id'] = $hasUser['User']['id'];
				}

				if($this->Package->User->save($data)){

					/**
					* Monta o action do produto/aco completo
					*/
			        $acos = $this->Acl->Aco->find('all', array('order' => 'Aco.lft ASC', 'recursive' => -1));
			        $parents = array();
			        $acos_full_path = array();
			        foreach ($acos as $key => $data) {
			            $aco =& $acos[$key];
			            $id = $aco['Aco']['id'];

			            // Generate path
			            if ($aco['Aco']['parent_id'] && isset($parents[$aco['Aco']['parent_id']])) {
			                $parents[$id] = $parents[$aco['Aco']['parent_id']] . '/' . $aco['Aco']['alias'];
			            } else {
			                $parents[$id] = $aco['Aco']['alias'];
			            }

		        		$acos_full_path[$id] = $parents[$id];
			        }

					/**
					* Concede todas as permissoes sobre os produtos contidos no pacote do cliente
					*/
					foreach ($acos_full_path as $k => $v) {
						$node = array('model' => 'User', 'foreign_key' => $this->Package->User->id);
						$action = $v;
						$perm = in_array($k, $acos_allow)?'allow':'deny';		
						$this->Acl->{$perm}($node, $action);
					}			        

					$this->Session->setFlash(__(FLASH_SAVE_SUCCESS), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
					$this->redirect(array('action' => 'edit', $this->Client->id));
				}else{
					$this->Package->User->set($this->request->data);
					$errors = $this->Package->User->invalidFields();
					$msgs = array();
					foreach ($errors as $k => $v) {
						if(isset($v[0])){
							$msgs[$k] = $v[0];
						}
					}
					$this->Session->setFlash(__(FLASH_SAVE_ERROR), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR, 'multiple' => $msgs), FLASH_SESSION_FORM);

				}
			}
		}
	}
}