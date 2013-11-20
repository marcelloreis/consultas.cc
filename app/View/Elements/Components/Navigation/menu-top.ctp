<?php 
$menu = array(
    /**
    * Imports
    */
    // array('label' => __('Import'), 'controller' => 'import', 'icon_left' => '<span class="square-16 plix-16"></span>'),

    /**
    * Produtos
    */
    array(
        'label' => '<span>' . __('Consultation Registration') . '</span>', 'controller' => 'entities', 'action' => 'people'
        ),
    /**
    * Seguranca
    */
    array(
        'label' => '<span>' . __('Security') . '</span>',
        'params' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
        'icon_right' => '<span class="caret"></span>',
        'children' => array(
            /**
            * Seguranca/Usuarios
            */
            array('label' => __('Users'), 'controller' => 'users'),
            /**
            * Seguranca/Grupos
            */
            array('label' => __('Groups'), 'controller' => 'groups'),
            )
        ),
    /**
    * Administrativo
    */
    array(
        'label' => '<span>' . __('Administrative') . '</span>',
        'params' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
        'icon_right' => '<span class="caret"></span>',
        'children' => array(
            /**
            * Administrativo/Clientes
            */
            array('label' => __('Clients'), 'controller' => 'clients'),
            /**
            * Administrativo/Clientes/Contas
            */
            array('label' => __('Accounts'), 'controller' => 'users', 'account' => true),
            /**
            * Administrativo/Pacotes
            */
            array('label' => __('Packages'), 'controller' => 'packages'),
            /**
            * Administrativo/Produtos
            */
            array('label' => __('Products'), 'controller' => 'products'),
            /**
            * Administrativo/Histórico
            */
            array('label' => __('Historical'), 'controller' => 'historical'),
            /**
            * Administrativo/Boleto
            */
            array('label' => __('Invoices'), 'controller' => 'invoices'),
            )
        ),
    /**
    * Configuracoes
    */
    array(
        'label' => '<span>' . __('Settings') . '</span>',
        'params' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
        'icon_right' => '<span class="caret"></span>',
        'children' => array(
            /**
            * Países
            */
            array('label' => __('Countries'), 'controller' => 'countries', 'plugin' => false),
            /**
            * Estados
            */
            array('label' => __('States'), 'controller' => 'states', 'plugin' => false),
            /**
            * Cidades
            */
            array('label' => __('Cities'), 'controller' => 'cities', 'plugin' => false),
            /**
            * Cidades
            */
            array('label' => __('Translations'), 'controller' => 'translations', 'plugin' => false),
            )
        ),

    );

if($userLogged['group_id'] != ADMIN_GROUP){
    $menu = array_slice($menu, 0, 1);
}

echo $this->AppUtils->buildMenu($menu, array('classActive' => 'page-active'));