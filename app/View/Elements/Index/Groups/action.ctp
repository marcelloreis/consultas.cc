<?php 
echo $this->Html->link('<i class="icon-search"></i>', array("controller" => $this->params['controller'], "action" => "view", $id), array('title' => 'Vizualizar Registro', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Vizualizar Registro', 'escape' => false)) . '&nbsp;';
echo $this->Html->link('<i class="icon-edit"></i>', array("controller" => $this->params['controller'], "action" => "edit", $id), array('title' => 'Editar Registro', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Editar Registro', 'escape' => false)) . '&nbsp;';

if(isset($this->params['named']['trashed']) && $this->AppPermissions->check("{$this->name}.trash")){
	echo $this->Html->link('<i class="icon-inbox"></i>', array("controller" => $this->params['controller'], "action" => "restore", $id), array('title' => 'Restaurar', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Restaurar', 'escape' => false), 'Tem certeza de que deseja restaurar este registro do lixo?') . '&nbsp;';
}else{
	echo $this->Html->link('<i class="icon-trash"></i>', array("controller" => $this->params['controller'], "action" => "trash", $id), array('title' => 'Lixeira', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Lixeira', 'escape' => false), 'Tem certeza de que deseja mover esse registro para o lixo?') . '&nbsp;';
}
echo $this->Html->link('<i class="icon-unlock"></i>', array("controller" => 'acl', "action" => "permissions", 'aro_id' => $id, 'aro' => 'group', 'plugin' => 'main'), array('title' => 'Permissões', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Permissões', 'escape' => false)) . '&nbsp;';