<?php 
/**
* Oculta o sidebar
*/
$this->assign('sidebar-class-hidden', 'nav-hidden');

/**
* Painel de pesquisas
*/
echo $this->element('Index/Entities/panel');

/**
* Informacoes principais do consultado
*/
echo $this->element('Index/Entities/main');

/**
* Botoes para carregar o restante dos produtos
*/
echo $this->element('Index/Entities/extras');
?>




