<?php 
echo $this->Html->link('<i class="icon-print"></i>', array("controller" => $this->params['controller'], "action" => "contract", $id), array('title' => 'Imprimir Contrato', 'class' => 'btn', 'target' => '_blank', 'rel' => 'tooltip', 'data-original-title' => 'Imprimir Contrato', 'escape' => false)) . ' ';
echo $this->Html->link('<i class="glyphicon-inbox_in"></i>', array("controller" => $this->params['controller'], "action" => "download", $id, "{$cnpj}.pdf"), array('title' => 'Baixar Contrato', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Baixar Contrato', 'escape' => false)) . ' ';
echo $this->Html->link('<i class="icon-search"></i>', array("controller" => $this->params['controller'], "action" => "view", $id), array('title' => 'Visualizar Registro', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Visualizar Registro', 'escape' => false)) . ' ';
echo $this->Html->link('<i class="icon-edit"></i>', array("controller" => $this->params['controller'], "action" => "edit", $id), array('title' => 'Editar Registro', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Editar Registro', 'escape' => false)) . ' ';

if(isset($this->params['named']['trashed']) && $this->AppPermissions->check("{$this->name}.trash")){
	echo $this->Html->link('<i class="icon-inbox"></i>', array("controller" => $this->params['controller'], "action" => "restore", $id), array('title' => 'Restaurar', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Restaurar', 'escape' => false), 'Tem certeza de que deseja restaurar este registro do lixo?') . ' ';
}else{
	echo $this->Html->link('<i class="icon-trash"></i>', array("controller" => $this->params['controller'], "action" => "trash", $id), array('title' => 'Lixeira', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Lixeira', 'escape' => false), 'Tem certeza de que deseja mover esse registro para o lixo?') . ' ';
}
?>