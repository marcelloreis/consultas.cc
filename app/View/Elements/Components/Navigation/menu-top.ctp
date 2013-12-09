<?php 
$menu = array(
    /**
    * Imports
    */
    // array('label' => 'Importações', 'controller' => 'import', 'icon_left' => '<span class="square-16 plix-16"></span>'),

    /**
    * Produtos
    */
    array(
        'label' => '<span>Consultar</span>', 
        'url' => array('controller' => 'entities', 'action' => 'index', '#' => 'entity-search')
        ),
    /**
    * Seguranca
    */
    array(
        'label' => '<span>Segurança</span>',
        'params' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
        'icon_right' => '<span class="caret"></span>',
        'children' => array(
            /**
            * Seguranca/Usuarios
            */
            array('label' => 'Usuários', 'controller' => 'users'),
            /**
            * Seguranca/Grupos
            */
            array('label' => 'Grupos', 'controller' => 'groups'),
            )
        ),
    /**
    * Administrativo
    */
    array(
        'label' => '<span>Administrativo</span>',
        'params' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
        'icon_right' => '<span class="caret"></span>',
        'children' => array(
            /**
            * Administrativo/Clientes
            */
            array('label' => 'Clientes', 'controller' => 'clients'),
            /**
            * Administrativo/Prospect
            */
            array('label' => 'Prospects', 'controller' => 'clients', 'action' => 'prospects'),
            /**
            * Administrativo/Prospect
            */
            array('label' => 'Contratos', 'controller' => 'contracts'),
            /**
            * Administrativo/Clientes/Contas
            */
            array('label' => 'Contas', 'controller' => 'users', 'action' => 'accounts'),
            /**
            * Administrativo/Produtos
            */
            array('label' => 'Produtos', 'controller' => 'products'),
            /**
            * Administrativo/Pacotes
            */
            array('label' => 'Pacotes', 'controller' => 'packages'),
            /**
            * Administrativo/Histórico
            */
            array('label' => 'Bilhetagem', 'controller' => 'billings'),
            /**
            * Administrativo/Boleto
            */
            array('label' => 'Consultas', 'controller' => 'queries'),
            )
        ),
    /**
    * Configuracoes
    */
    array(
        'label' => '<span>Configurações</span>',
        'params' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
        'icon_right' => '<span class="caret"></span>',
        'children' => array(
            /**
            * Países
            */
            array('label' => 'Países', 'controller' => 'countries', 'plugin' => false),
            /**
            * Estados
            */
            array('label' => 'Estados', 'controller' => 'states', 'plugin' => false),
            /**
            * Cidades
            */
            array('label' => 'Cidades', 'controller' => 'cities', 'plugin' => false),
            /**
            * Cidades
            */
            array('label' => 'Traduções', 'controller' => 'translations', 'plugin' => false),
            )
        ),

    );

/**
* Libera somente o menu de consultas para os usuarios que nao forem do grupo admin
*/
if($userLogged['group_id'] != ADMIN_GROUP){
    $menu = array_slice($menu, 0, 1);
}

echo $this->AppUtils->buildMenu($menu, array('classActive' => 'page-active'));