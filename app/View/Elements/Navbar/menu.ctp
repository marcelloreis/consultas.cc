<?php 
echo $this->Html->link('<i class="icon-cog icon-xlarge visible-xs visible-xs-inline"></i>Menu<b class="caret hidden-sm-only"></b>', '#', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'escape' => false));

$menu = array(
    /**
    * Customer
    */
    array('label' => __d('fields', 'Customer'), 'controller' => 'customers'),
    /**
    * Groups
    */
    array('label' => __d('fields', 'Group'), 'controller' => 'groups'),
    );

echo $this->AppUtils->buildMenu($menu, array('classActive' => 'page-active'));
