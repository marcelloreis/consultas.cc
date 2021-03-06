<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Users/
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Controller
 */

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Static content controller
 *
 * Este controlador contem regras de negócio aplicadas ao model User
 *
 * @package       app.Controller
 * @link http://.framework.nasza.com.br/2.0/controller/Users.html
 */
class UsersController extends AppController {

	public function dashboard(){
        $this->redirect(array('controller' => 'entities'));
	}

    /**
    * Método index
    * Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
    *
    * @override Metodo AppController.index
    * @param string $period (Periodo das movimentacoes q serao listadas)
    * @return void
    */
    public function index($params=array()){
        $params = array(
            'conditions' => array('User.client_id' => null)
            );
        //@override
        parent::index($params);
    }   

    /**
    * Método accounts
    * Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
    *
    * @override Metodo AppController.accounts
    * @param string $period (Periodo das movimentacoes q serao listadas)
    * @return void
    */
    public function accounts($params=array()){
        $params = array(
            'conditions' => array('User.client_id NOT' => null)
            );

        /**
        * Carrega os filtros do painel de buscas
        */
        $this->filters = array(
            'client_id' => $this->User->Client->find('list', array('id', 'name')),
            'group_id' => $this->User->Group->find('list', array('id', 'name')),
            );

        //@override
        parent::index($params);

        $this->view = $this->action;
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
        /**
         * Verifica se o formulário foi submetido por post
         */
        if ($this->request->is('post') || $this->request->is('put')) {
            /**
            * Caso o campo password esteja setado, porem vazio, ele sera removido do request->data para q nao seja atualizado
            */
            if(isset($this->request->data['User']['password']) && empty($this->request->data['User']['password'])){
                unset($this->request->data['User']['password']);
            }
        }

        //@override
        parent::edit($id);
    }    

    /**
    * Método edit_account
    * Este método contem regras de negocios para adicionar e editar registros na base de dados
    *
    * @override Metodo Users.edit
    * @param string $id
    * @return void
    */
    public function edit_account($id=null){
        $this->isRedirect = false;
        $this->edit($id);
        $this->render('edit_account');
    }

    /**
    * Método edit_profile
    * Este método contem regras de negocios para adicionar e editar registros na base de dados
    *
    * @override Metodo Users.edit
    * @param string $id
    * @return void
    */
    public function edit_profile($id=null){
        $this->isRedirect = false;
        $this->edit($id);
        $this->render('edit_profile');
    }

    /**
    * Método login
    *
    * Este método é responsavel pelas regras de negocio 
    * necessarias para realizar o login do usuario
    */
    public function login() {
        /**
        * Deleta a sessao que guarda o codigo da rede social q o usuario escolheu para usar como login
        */
        $this->Session->delete('User.Social.api');

        /**
        * Verifica qual tipo de login foi escolhido pelo usuário
        */
        $api = isset($this->params['named']['api'])?$this->params['named']['api']:null;
        $api = isset($this->request->data['User']['api'])?$this->request->data['User']['api']:$api;
        if(isset($api)){
            switch ($api) {
                case 'system':
                    $this->saveCredentials();
                    break;
                case 'google':
                    $this->Session->write('User.Social.api', GOOGLE_GROUP);
                    $this->redirect($this->AppGoogle->getAuthUrl());
                    break;
                case 'facebook':
                    $this->Session->write('User.Social.api', FACEBOOK_GROUP);
                    $this->redirect($this->AppFacebook->getAuthUrl());
                    break;
            }
        }


        $this->layout = 'login';
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                /**
                * Carrega todos os precos dos produtos de acordo com os seus pacotes
                */
                $this->loadPrices();
                /**
                * Carrega todos os dados do cliente logado
                */
                $this->loadClient();

                $this->Session->setFlash(sprintf(__("Bem vindo ao %s"), TITLE_APP) . ".", FLASH_TEMPLATE_DASHBOARD, array('class' => FLASH_CLASS_SUCCESS, 'title' => "Olá " . $this->Auth->User('name')), FLASH_TEMPLATE_DASHBOARD);
                parent::__loadPermissionsOnSessions();
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash(__("Seu email ou senha estão incorretos."), FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_LOGIN);
            }
        }
    }

    /**
    * Método forgot_pass
    *
    * Este método inicia o processo de recueracao da senha do usuario
    */
    public function forgot_pass(){
        $this->layout = 'login';
        $this->Session->setFlash("Por favor, preencha o campo abaixo e enviaremos um link para seu e-mail para informar uma nova senha:", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_LOGIN);

        /**
         * Verifica se o formulário foi submetido por post
         */
        if ($this->request->is('post') || $this->request->is('put')) {
            /**
            * Verifica se o campo de email foi informado
            */
            if(!empty($this->request->data['User']['email'])){
                $this->User->recursive = -1;

                /**
                * Verifica se o email informado existe na base de dados
                */
                $hasEmail = $this->User->find('first', array(
                    'conditions' => array(
                        'User.email' => $this->request->data['User']['email']
                        )
                    ));

                if(empty($hasEmail['User'])){
                    $this->Session->setFlash("O e-mail informado não existe em nossa base de dados, informe o seu e-mail cadastrado por favor.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_LOGIN);
                }else{
                    $hasEmail['User']['change_pass_token'] = md5(uniqid());
                    /**
                    * Registra o token da requisicao
                    */
                    $this->User->updateAll(
                        array(
                            'User.change_pass_token' => "'{$hasEmail['User']['change_pass_token']}'",
                            'User.change_pass_expire' => "'" . date('Y-m-d H:i:s', mktime(date('H'), (date('i') + 10), date('s'), date('m'), date('d'), date('Y'))) . "'",
                            ),
                        array(
                            'User.id' => $hasEmail['User']['id']
                            )
                        );

                        $email = new CakeEmail('apps');
                        $email->template('forgot-pass');
                        $email->emailFormat('html');
                        $email->viewVars(array('user' => $hasEmail));

                        $email->sender(array(EMAIL_NO_REPLAY => TITLE_APP));
                        $email->from(array(EMAIL_NO_REPLAY => TITLE_APP));
                        $email->to($hasEmail['User']['email']);
                        $email->subject("Lembrete da senha nova de {$hasEmail['User']['given_name']}");
                        $email->send();     

                    
                    $this->Session->setFlash("Em instantes, você receberá um e-mail com instruções sobre como recuperar sua senha.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_LOGIN);
                }
            }
        }        
    }

    /**
    * Método loadClient
    *
    * Este método carrega todos os dados do cliente logado
    */
    public function loadClient($client_id=null){
        /**
        * Inicializa a variavel que retornara os dados do cliente
        */
        $client = array();

        /**
        * Carrega os dados do usuario logado
        */
        if(!$client_id){
            $user = $this->Auth->user();
            $client_id = $user['client_id'];
        }

        if(!empty($client_id)){
            /**
            * Carrega os dados do cliente logado
            */
            $client = $this->User->Client->find('first', array(
                // 'recursive' => -1,
                'conditions' => array(
                    'Client.id' => $client_id
                    )
                ));

            /**
            * Percorre por todos os dados retornado salvando na session
            */
            foreach ($client['Client'] as $k => $v) {
                if(method_exists($this->Session, 'write')){
                    $this->Session->write("Client.{$k}", $v);
                }
            }

            /**
            * Calcula as competencias do mes vigente a partir da data de pagamento do cliente
            */
            $competence_ini = date('Y-m-d', mktime(0, 0, 0, date('m'), $client['Client']['maturity_day'], date('Y')));
            $competence_end = date('Y-m-d', mktime(0, 0, 0, (date('m') + 1), $client['Client']['maturity_day'], date('Y')));

            /**
            * Carrega os dados da bilhetagem do mes vigente do cliente
            */
            $billing = $this->User->Client->Billing->find('first', array(
                'fields' => array(
                    'Billing.id',
                    ),
                'conditions' => array(
                    'Billing.client_id' => $client_id,
                    'Billing.competence_ini' => $competence_ini,
                    'Billing.competence_end' => $competence_end,
                    )
                )); 

            /**
            * Cria a bilhetagem do mes vigente do cliente, caso nao exista
            */
            if(!count($billing)){
                $data = array(
                    'Billing' => array(
                        'client_id' => $client_id,
                        'package_id' => $client['Package']['id'],
                        'franchise' => $client['Package']['franchise'],
                        'qt_queries' => 0,
                        'competence_ini' => $competence_ini,
                        'competence_end' => $competence_end,
                        'qt_exceeded' => 0,
                        'value_exceeded' => 0,
                        )
                    );
                $this->User->Client->Billing->create();
                $this->User->Client->Billing->save($data);
                $billing = $this->User->Client->Billing->read();
            }

            /**
            * Verifica se o cliente ja efetuou o pagamento da assinatura do pacote
            */
            $hasSignature = $this->User->Client->Invoice->find('count', array(
                'recursive' => -1,
                'conditions' => array(
                    'Invoice.client_id' => $client_id,
                    'Invoice.is_signature' => true,
                    'Invoice.is_paid' => true,
                    )
                ));

            /**
            * Verifica se o cliente ja efetuou pelo menos o primero pagamento
            */
            if(method_exists($this->Session, 'write')){
                $this->Session->write("Client.billing_id", $billing['Billing']['id']);          
                $this->Session->write("Client.package_id", $client['Package']['id']);          
                $this->Session->write("Client.franchise", $client['Package']['franchise']);          
                $this->Session->write("Client.signature", $hasSignature);          
            }
            $client['Client']['billing_id'] = $billing['Billing']['id'];
            $client['Client']['package_id'] = $client['Package']['id'];
            $client['Client']['franchise'] = $client['Package']['franchise'];
            $client['Client']['signature'] = $hasSignature;

        }

        return $client;
    }

    /**
    * Método loadPrices
    *
    * Este método carrega todos os precos dos produtos de acordo com os seus pacotes
    */
    public function loadPrices(){
        /**
        * Inicializa a variavel de retorno da bilhetagem
        */
        $billings = array();

        /**
        * Carrega todos os pacotes cadastrados
        */
        $packages = $this->User->Client->Billing->Package->find('all', array('recursive' => -1));

        /**
        * Percorre por todos os pacotes encontrados
        */
        foreach ($packages as $k => $v) {
            /**
            * Carrega todos os precos encontrados com o pacote
            */
            $prices = $this->User->Client->Billing->Package->Price->find('all', array(
                'recursive' => -1,
                'conditions' => array(
                    'Price.package_id' => $v['Package']['id'],
                    )
                ));    

            /**
            * Percorre por todos os preços encontrados
            */
            foreach ($prices as $k2 => $v2) {
                /**
                * Salva na session os id's e os precos encontrados por cada pacote
                */
                if(method_exists($this->Session, 'write')){
                    $this->Session->write("Billing.prices_val.{$v['Package']['id']}.{$v2['Price']['product_id']}", $v2['Price']['price']);
                    $this->Session->write("Billing.prices_id.{$v['Package']['id']}.{$v2['Price']['product_id']}", $v2['Price']['id']);
                }
                
                $billings['prices_val'][$v['Package']['id']][$v2['Price']['product_id']] = $v2['Price']['price'];
                $billings['prices_id'][$v['Package']['id']][$v2['Price']['product_id']] = $v2['Price']['id'];
            }
        }

        return $billings;
    }

    /**
    * Método change_pass
    *
    * Este método recupera a senha do usuario
    */
    public function change_pass(){
        $this->layout = 'login';
        $validToken = false;
        $this->User->recursive = -1;


        /**
         * Verifica se o formulário foi submetido por post
         */
        if ($this->request->is('post') || $this->request->is('put')) {
            if(!empty($this->request->data['User'])){
                /**
                * Verifica se as senhas conferem
                */
                if($this->request->data['User']['password'] != $this->request->data['User']['password_confirm']){
                    $this->Session->setFlash("A senha informada difere da senha de confirmação.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_LOGIN);
                    $this->redirect($this->referer());
                }else{
                    $this->User->updateAll(
                        array(
                            'User.password' => "'" . AuthComponent::password($this->request->data['User']['password']) . "'",
                            ),
                        array(
                            'User.change_pass_token' => $this->request->data['User']['change_pass_token']
                            )
                        );
                    $this->Session->setFlash("Sua senha foi alterada com sucesso.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_LOGIN);
                    $this->redirect(array('action' => 'login'));
                }
            }
        }else{
            if(!empty($this->params['named']['change_pass_token'])){

                /**
                * Verifica se o token é valido e se ainda nao expirou
                */
                $validToken = $this->User->find('count', 
                    array(
                        'conditions' => array(
                            'User.change_pass_token' => $this->params['named']['change_pass_token'],
                            'User.change_pass_expire >' => date('Y-m-d H:i:s')
                            )
                    ));

                if($validToken){
                    $this->Session->setFlash("Por favor, preencha os campos abaixo para alterar sua senha de acesso a " . TITLE_APP . " (todos os campos são obrigatórios):", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ALERT), FLASH_SESSION_LOGIN);
                }
            }
        }



        $this->set(compact('validToken'));
    }    

    /**
    * Método saveCredentials
    *
    * Este método é responsavel por salvar os dados contidos no 
    * formulário de cadastro disponivel na tela de login
    */
    private function saveCredentials(){
        $data = $this->request->data['User'];
        /**
        * Verifica se o usuario ja esta cadastrado no sistema
        */
        $userSystemAlrealyAdd = $this->User->findByEmail($data['email']);

        if($userSystemAlrealyAdd){
            //Carrega o ID do usuario do sistema entrado para que os dados sejam atualizado ao invez de inseridos
            $data['id'] = $userSystemAlrealyAdd['User']['id'];
            $this->Session->setFlash("Você já estava cadastrado no " . TITLE_APP . " desde " . substr($userSystemAlrealyAdd['User']['created'], 0, 10) . ", atualizamos a sua senha com a que acabou de cadastrar. Seja bem vindo [de novo].", FLASH_TEMPLATE_DASHBOARD, array('class' => FLASH_CLASS_ALERT, 'title' => "Olá " . $userSystemAlrealyAdd['User']['given_name']), FLASH_TEMPLATE_DASHBOARD);
        }else{
            $data['given_name'] = ucfirst(substr($data['name'], 0, strpos($data['name'], ' ')));
            $this->Session->setFlash("Seja bem vindo ao " . TITLE_APP . ".", FLASH_TEMPLATE_DASHBOARD, array('class' => FLASH_CLASS_SUCCESS, 'title' => "Olá " . $data['given_name']), FLASH_TEMPLATE_DASHBOARD);
        }

        /**
        * Insere/Atualiza o usuario na tabela SOCIALS
        */
        $data['status'] = STATUS_ACTIVE;
        $data['password'] = AuthComponent::password($data['password']);
        $this->User->create($data);
        if(!$this->User->save()){
            /**
             * Carrega os erros encontrados ao tentar salvar o formulário
             */
            $this->User->set($this->request->data);
            $errors = $this->User->invalidFields();
            $msgs = array();
            foreach ($errors as $k => $v) {
                if(isset($v[0])){
                    $msgs[$k] = $v[0];
                }
            }

            //Redireciona o usuario para a pagina de login novamente caso o cadastro nao seja bem sucedido
            $this->Session->setFlash(FLASH_SAVE_ERROR, FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR, 'multiple' => $msgs), FLASH_SESSION_LOGIN);
            $this->logout(FLASH_SAVE_ERROR);
        }

        $user = $this->User->read();
        $login = $user['User'];
        $login['Group'] = $user['Group'];
        $login['Social'] = isset($user['Social'])?$user['Social']:false;

        /**
        * Efetua o login do usuario sistema
        */
        if ($this->Auth->login($login)) {
            //Carrega todas as permissoes do usuario/grupo em sessao
            parent::__loadPermissionsOnSessions();
            //Redireciona o usuario para a pagina inicial do sistema
            $this->redirect($this->Auth->redirect());
        } else {
            $this->Session->setFlash("Não foi possível logar no sistema com suas credenciais, tente mais tarde.", FLASH_TEMPLATE, array('class' => FLASH_CLASS_ERROR), FLASH_SESSION_LOGIN);
        }        
    }    

    /**
     * Exclui todas as sessoes do usuario logado e o redireciona para a tela de login
     */
    public function logout($msg="Sessão Encerrada.") {
        $this->Session->setFlash($msg, FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_LOGIN);
        $this->Session->delete('Auth');
        $this->Session->delete('User');
        $this->Session->delete('Billing');
        $this->redirect($this->Auth->logout());
    }
}
