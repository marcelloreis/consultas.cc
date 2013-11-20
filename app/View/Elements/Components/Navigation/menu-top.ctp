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
        'label' => '<span>' . __('People Location') . '</span>', 'controller' => 'entities', 'action' => 'people'
        ),
    /**
    * Localizacoes
    */
    array(
        'label' => '<span>' . __('Locales') . '</span>',
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
            )
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
    * Traducoes
    */
    array(
        'label' => '<span>' . __('Translations') . '</span>', 'controller' => 'translations', 'action'
        ),

    );
echo $this->AppUtils->buildMenu($menu, array('classActive' => 'page-active'));