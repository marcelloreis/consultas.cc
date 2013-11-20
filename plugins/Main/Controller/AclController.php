<?php
App::uses('AclManagerAppController', 'Main.Controller');

/**
 * Acl Manager
 *
 * A CakePHP Plugin to manage Acl
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Frédéric Massart - FMCorz.net
 * @copyright     Copyright 2011, Frédéric Massart
 * @link          http://github.com/FMCorz/AclManager
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class AclController extends AclManagerAppController {

    public $paginate = array();
    protected $_authorizer = null;

    /**
     * beforeFitler
     */
    public function beforeFilter() {
        parent::beforeFilter();

        /**
         * Loading required Model
         */
        $aros = Configure::read('AclManager.models');
        foreach ($aros as $aro) {
            $this->loadModel($aro);
        }

        /**
         * Pagination
         */
        $aros = Configure::read('AclManager.aros');
        foreach ($aros as $aro) {
            $limit = Configure::read("AclManager.{$aro}.limit");
            $limit = empty($limit) ? 5 : $limit;
            $this->paginate[$this->{$aro}->alias] = array(
                'recursive' => -1,
                'limit' => $limit
                );
        }
    }

    /**
    * Restart All
    */
    public function reset_all(){
        $this->drop(false);
        $this->drop_perms(false);
        $this->update_aros(false);
        $this->update_acos(false);
        parent::__loadPermissionsOnSessions();
        $this->Session->setFlash(__("As configurações foram resetadas com sucesso."), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS, 'title' => __('Permissões')), FLASH_SESSION_FORM);
        $this->redirect($this->request->referer());
    }

    /**
     * Delete everything
     */
    public function drop($redirect=true) {
        $this->Acl->Aco->deleteAll(array("1 = 1"));
        $this->Acl->Aro->deleteAll(array("1 = 1"));
        if($redirect){
            $this->Session->setFlash(__("Todas os AROs e ACOs foram removidos."), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS, 'title' => __('Permissões')), FLASH_SESSION_FORM);
            $this->redirect($this->request->referer());
        }
    }

    /**
     * Delete all permissions
     */
    public function drop_perms($redirect=true) {
        if ($this->Acl->Aro->Permission->deleteAll(array("1 = 1"))) {
            $this->Session->setFlash(__("Todas as permissões foram removidas."), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS, 'title' => __('Permissões')), FLASH_SESSION_FORM);
        } else {
            $this->Session->setFlash(__("Não foi possivel remover as permisssões."), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR, 'title' => __('Permissões')), FLASH_SESSION_FORM);
        }
        if($redirect){
            $this->redirect($this->request->referer());
        }
    }

    /**
     * Manage Permissions Ajax
     */
    public function ajax_permissions(){
        $perms =  isset($this->request->data['Perms']) ? $this->request->data['Perms'] : array();

        foreach ($perms as $aco => $aros) {
            $action = str_replace(":", "/", $aco);
            foreach ($aros as $node => $perm) {
                list($model, $id) = explode(':', $node);
                $node = array('model' => $model, 'foreign_key' => $id);
                if ($perm == 'allow') {
                    $this->Acl->allow($node, $action);
                }
                elseif ($perm == 'inherit') {
                    $this->Acl->inherit($node, $action);
                }
                elseif ($perm == 'deny') {
                    $this->Acl->deny($node, $action);
                }
            }
        }

        //Reseta todas as permissoes gravadas na session
        parent::__loadPermissionsOnSessions();
    }


    /**
     * Manage Permissions
     */
    public function permissions() {
        $model = isset($this->request->params['named']['aro']) ? ucfirst(strtolower($this->request->params['named']['aro'])) : null;
        if (!$model || !in_array($model, Configure::read('AclManager.aros'))) {
            $model = Configure::read('AclManager.aros');
            $model = $model[0];
        }

        $Aro = $this->{$model};
        $aro_list = $this->{$Aro->alias}->find('list');
        $aros = $this->paginate($Aro->alias);
        $permKeys = $this->_getKeys();

        /**
        * Verifica se o ARO solicitado existe
        */
        if(!isset($this->params['named']['aro_id']) || !isset($aro_list[$this->params['named']['aro_id']])){
            $this->Session->setFlash(__('O Aro informado nao existe'), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_FORM);
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'plugin' => false));
        }


        /**
         * Se o campo "q" for igual a 1, simula o envio do form por get
         * redirecionando para http://[domain]/[controller]/[action]/seach:value1/namedN:valueN
         */
        $this->__post2get();

        /**
        * Verifica se foi passado algum valor na variavel padrao de busca
        */
        $cond_acos = array('order' => 'Aco.lft ASC', 'recursive' => 1);
        if(isset($this->params['named']['search']) && !empty($this->params['named']['search'])){
            // $cond_acos['conditions'] = array('Aco.alias like' => "%{$this->params['named']['search']}%");
        }

        /**
         * Build permissions info
         */
        $acos = $this->Acl->Aco->find('all', $cond_acos);        
        $perms = array();
        $parents = array();
        foreach ($acos as $key => $data) {
            $aco =& $acos[$key];
            $aco = array('Aco' => $data['Aco'], 'Aro' => $data['Aro'], 'Action' => array());
            $id = $aco['Aco']['id'];

            // Generate path
            if ($aco['Aco']['parent_id'] && isset($parents[$aco['Aco']['parent_id']])) {
                $parents[$id] = $parents[$aco['Aco']['parent_id']] . '/' . $aco['Aco']['alias'];
            } else {
                $parents[$id] = $aco['Aco']['alias'];
            }
            $aco['Action'] = $parents[$id];

            // Fetching permissions per ARO
            $acoNode = $aco['Action'];
            foreach($aros as $aro) {

                $aroId = $aro[$Aro->alias][$Aro->primaryKey];

                /**
                 * Manually checking permission
                 * Part of this logic comes from DbAcl::check()
                 */
                $permissions = Set::extract($aco, "/Aro[model={$Aro->alias}][foreign_key=$aroId]/Permission/.");
                $permissions = array_shift($permissions);
                $allowed = false;
                $inherited = false;
                $inheritedPerms = array();
                $allowedPerms = array();

                foreach ($permKeys as $key) {
                    if (!empty($permissions)) {
                        if ($permissions[$key] == -1) {
                            $allowed = false;
                            break;
                        } elseif ($permissions[$key] == 1) {
                            $allowedPerms[$key] = 1;
                        } elseif ($permissions[$key] == 0) {
                            $inheritedPerms[$key] = 0;
                        }
                    } else {
                        $inheritedPerms[$key] = 0;
                    }
                }

                // Has it been allowed or is it inherited?
                if (count($allowedPerms) === count($permKeys)) {
                    $allowed = true;
                } elseif (count($inheritedPerms) === count($permKeys)) {
                    $aroNode = array('model' => $Aro->alias, 'foreign_key' => $aroId);
                    $allowed = $this->Acl->check($aroNode, $acoNode);
                    $inherited = true;
                }

                $perms[str_replace('/', ':', $acoNode)][$Aro->alias . ":" . $aroId . '-inherit'] = $inherited;
                $perms[str_replace('/', ':', $acoNode)][$Aro->alias . ":" . $aroId] = $allowed;
            }
        }

        $this->request->data = array('Perms' => $perms);
        $this->set('aroAlias', $Aro->alias);
        $this->set('aroDisplayField', $Aro->displayField);
        $this->set(compact('acos', 'aros', 'aro_list'));

        //Reseta todas as permissoes gravadas na session
        parent::__loadPermissionsOnSessions();
    }

    /**
     * Update ACOs
     * Sets the missing actions in the database
     */
    public function update_acos($redirect=true) {
        $hasAro = $this->Acl->Aro->find('count');
        if(!$hasAro){
            $this->Session->setFlash(__('Nenhum ARO foi criado ainda, crie os AROs primeiro.'), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR, 'title' => __('Atualização de ACO\'s')), FLASH_SESSION_FORM);
        }else{

            $count = 0;
            $knownAcos = $this->_getAcos();

            // Root node
            $aco = $this->_action(array(), '');
            if (!$rootNode = $this->Acl->Aco->node($aco)) {
                $rootNode = $this->_buildAcoNode($aco, null);
                $count++;
            }
            $knownAcos = $this->_removeActionFromAcos($knownAcos, $aco);

            // Loop around each controller and its actions
            $allActions = $this->_getActions();
            foreach ($allActions as $controller => $actions) {
                if (empty($actions)) {
                    continue;
                }

                $parentNode = $rootNode;
                list($plugin, $controller) = pluginSplit($controller);

                // Plugin
                    $aco = $this->_action(array('plugin' => $plugin), '/:plugin/');
                $aco = rtrim($aco, '/');        // Remove trailing slash
                $newNode = $parentNode;
                if ($plugin && !$newNode = $this->Acl->Aco->node($aco)) {
                    $newNode = $this->_buildAcoNode($plugin, $parentNode);
                    $count++;
                }
                $parentNode = $newNode;
                $knownAcos = $this->_removeActionFromAcos($knownAcos, $aco);

                // Controller
                $aco = $this->_action(array('controller' => $controller, 'plugin' => $plugin), '/:plugin/:controller');
                if (!$newNode = $this->Acl->Aco->node($aco)) {
                    $newNode = $this->_buildAcoNode($controller, $parentNode);
                    $count++;
                }
                $parentNode = $newNode;
                $knownAcos = $this->_removeActionFromAcos($knownAcos, $aco);

                // Actions
                foreach ($actions as $action) {
                    $aco = $this->_action(array(
                        'controller' => $controller,
                        'action' => $action,
                        'plugin' => $plugin
                        ));
                    if (!$node = $this->Acl->Aco->node($aco)) {
                        $this->_buildAcoNode($action, $parentNode);
                        $count++;
                    }
                    $knownAcos = $this->_removeActionFromAcos($knownAcos, $aco);
                }
            }

            // Some ACOs are in the database but not in the controllers
            if (count($knownAcos) > 0) {
                $acoIds = Set::extract('/Aco/id', $knownAcos);
                $this->Acl->Aco->deleteAll(array('Aco.id' => $acoIds));
            }

            $this->Session->setFlash(sprintf(__("%d ACOs(CONTROLADORES/FUNÇÕES) foram criados/atualizados."), $count), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS, 'title' => __('Atualização de ACO\'s')), FLASH_SESSION_FORM);
        }

        if($redirect){
            $this->redirect($this->request->referer());
        }
    }

    /**
     * Update AROs
     * Sets the missing AROs in the database
     */
    public function update_aros($redirect=true) {

        // Debug off to enable redirect
        Configure::write('debug', 0);

        $count = 0;
        $type = 'Aro';

        // Over each ARO Model
        $objects = Configure::read("AclManager.aros");
        foreach ($objects as $object) {

            $Model = $this->{$object};

            $items = $Model->find('all');
            foreach ($items as $item) {

                $item = $item[$Model->alias];
                $Model->create();
                $Model->id = $item['id'];

                try {
                    $node = $Model->node();
                } catch (Exception $e) {
                    $node = false;
                }

                // Node exists
                if ($node) {
                    $parent = $Model->parentNode();
                    if (!empty($parent)) {
                        $parent = $Model->node($parent, $type);
                    }
                    $parent = isset($parent[0][$type]['id']) ? $parent[0][$type]['id'] : null;

                    // Parent is incorrect
                    if ($parent != $node[0][$type]['parent_id']) {
                        $node = null;
                    }
                }

                // Missing Node or incorrect
                if (empty($node)) {

                    // Extracted from AclBehavior::afterSave (and adapted)
                    $parent = $Model->parentNode();
                    if (!empty($parent)) {
                        if(!$this->_hasAro(key($parent), $parent[key($parent)]['id'])){
                            $this->Session->setFlash(__("Não existe " . __d('fields', ucfirst(strtolower(key($parent)))) . " para o " . __d('fields', $Model->alias) . " {$item['name']} [{$item['id']}], ou o id [{$parent[key($parent)]['id']}] do " . __d('fields', ucfirst(strtolower(key($parent)))) . " nao existe no banco de dados."), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_FORM);
                            $this->redirect(array('controller' => Inflector::pluralize(strtolower($Model->alias)), 'action' => 'edit', $item['id'], 'plugin' => false));
                        }
                        $parent = $Model->node($parent, $type);
                    }
                    $data = array(
                        'parent_id' => isset($parent[0][$type]['id']) ? $parent[0][$type]['id'] : null,
                        'model' => $Model->name,
                        'foreign_key' => $Model->id
                        );

                    // Creating ARO
                    $this->Acl->{$type}->create($data);
                    $this->Acl->{$type}->save();
                    $count++;
                }
            }
        }

        if($redirect){
            $this->Session->setFlash(sprintf(__("%d AROs(GRUPOS E USUÁRIOS) foram criados."), $count), FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS, 'title' => __('Atualização de ARO\'s')), FLASH_SESSION_FORM);
            $this->redirect($this->request->referer());
        }
    }

    /**
    * Verifica se o ARO passado pelo parametro existe
    */
    private function _hasAro($model, $id){
        $hasAro = $this->{$model}->find('count', array('recursive' => '-1', 'conditions' => array('id' => $id)));
        return $hasAro;
    }

    /**
     * Gets the action from Authorizer
     */
    protected function _action($request = array(), $path = '/:plugin/:controller/:action') {
        $plugin = empty($request['plugin']) ? null : Inflector::camelize($request['plugin']) . '/';
        $request = array_merge(array('controller' => null, 'action' => null, 'plugin' => null), $request);
        $authorizer = $this->_getAuthorizer();

        return $authorizer->action($request, $path);
    }

    /**
     * Build ACO node
     *
     * @return node
     */
    protected function _buildAcoNode($alias, $parent_id = null) {
        if (is_array($parent_id)) {
            $parent_id = $parent_id[0]['Aco']['id'];
        }
        $this->Acl->Aco->create(array('alias' => $alias, 'parent_id' => $parent_id));
        $this->Acl->Aco->save();
        return array(array('Aco' => array('id' => $this->Acl->Aco->id)));
    }

    /**
     * Returns all the Actions found in the Controllers
     *
     * Ignores:
     * - protected and private methods (starting with _)
     * - Controller methods
     * - methods matching Configure::read('AclManager.ignoreActions')
     *
     * @return array('Controller' => array('action1', 'action2', ... ))
     */
    protected function _getActions() {
        $ignore = Configure::read('AclManager.ignoreActions');
        $methods = get_class_methods('Controller');
        foreach($methods as $method) {
            $ignore[] = $method;
        }

        $controllers = $this->_getControllers();
        $actions = array();
        foreach ($controllers as $controller) {

            list($plugin, $name) = pluginSplit($controller);

            $methods = get_class_methods($name . "Controller");
            $methods = array_diff($methods, $ignore);
            foreach ($methods as $key => $method) {
                if (strpos($method, "_") === 0 || in_array($controller . '/' . $method, $ignore)) {
                    unset($methods[$key]);
                }
            }
            $actions[$controller] = $methods;
        }

        return $actions;
    }

    /**
     * Returns all the ACOs including their path
     */
    protected function _getAcos() {
        $acos = $this->Acl->Aco->find('all', array('order' => 'Aco.lft ASC', 'recursive' => -1));
        $parents = array();
        foreach ($acos as $key => $data) {

            $aco =& $acos[$key];
            $id = $aco['Aco']['id'];

            // Generate path
            if ($aco['Aco']['parent_id'] && isset($parents[$aco['Aco']['parent_id']])) {
                $parents[$id] = $parents[$aco['Aco']['parent_id']] . '/' . $aco['Aco']['alias'];
            } else {
                $parents[$id] = $aco['Aco']['alias'];
            }
            $aco['Aco']['action'] = $parents[$id];
        }
        return $acos;
    }

    /**
     * Gets the Authorizer object from Auth
     */
    protected function _getAuthorizer() {
        if (!is_null($this->_authorizer)) {
            return $this->_authorizer;
        }
        $authorzeObjects = $this->Auth->_authorizeObjects;
        foreach ($authorzeObjects as $object) {
            if (!$object instanceOf ActionsAuthorize) {
                continue;
            }
            $this->_authorizer = $object;
            break;
        }

        if (empty($this->_authorizer)) {
            $this->Session->setFlash(__('O atributo "$this->Auth->authorize" em AppController não esta configurado ou esta comentado. A função "$this->Auth->allow()" também não pode ser vazia.'), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR, 'title' => __('Permissões')), FLASH_SESSION_FORM);
            $this->redirect($this->referer());
        }
        return $this->_authorizer;
    }

    /**
     * Returns all the controllers from Cake and Plugins
     * Will only browse loaded plugins
     *
     * @return array('Controller1', 'Plugin.Controller2')
     */
    protected function _getControllers() {

        // Getting Cake controllers
        $objects = array('Cake' => array());
        $objects['Cake'] = App::objects('Controller');
        $unsetIndex = array_search("AppController", $objects['Cake']);
        if ($unsetIndex !== false) {
            unset($objects['Cake'][$unsetIndex]);
        }

        // App::objects does not return PagesController
        if (!in_array('PagesController', $objects['Cake'])) {
            array_unshift($objects['Cake'], 'PagesController');
        }

        // Getting Plugins controllers
        $plugins = CakePlugin::loaded();
        foreach ($plugins as $plugin) {
            $objects[$plugin] = App::objects($plugin . '.Controller');
            $unsetIndex = array_search($plugin . "AppController", $objects[$plugin]);
            if ($unsetIndex !== false) {
                unset($objects[$plugin][$unsetIndex]);
            }
        }

        // Around each controller
        $return = array();
        foreach ($objects as $plugin => $controllers) {
            $controllers = str_replace("Controller", "", $controllers);
            foreach ($controllers as $controller) {
                if ($plugin !== "Cake") {
                    $controller = $plugin . "." . $controller;
                }
                if (App::import('Controller', $controller)) {
                    $return[] = $controller;
                }
            }
        }

        return $return;
    }

    /**
     * Returns permissions keys in Permission schema
     * @see DbAcl::_getKeys()
     */
    protected function _getKeys() {
        $keys = $this->Acl->Aro->Permission->schema();
        $newKeys = array();
        $keys = array_keys($keys);
        foreach ($keys as $key) {
            if (!in_array($key, array('id', 'aro_id', 'aco_id'))) {
                $newKeys[] = $key;
            }
        }
        return $newKeys;
    }

    /**
     * Returns an array without the corresponding action
     */
    protected function _removeActionFromAcos($acos, $action) {
        foreach ($acos as $key => $aco) {
            if ($aco['Aco']['action'] == $action) {
                unset($acos[$key]);
                break;
            }
        }
        return $acos;
    }
}

