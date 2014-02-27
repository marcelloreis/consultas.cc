<?php 
echo $this->Html->link('<i class="icon-print"></i>', array("controller" => $this->params['controller'], "action" => BANK_ACTIVE, $token, '0'), array('title' => 'Imprimir Boleto', 'target' => '_blank', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Imprimir Boleto', 'escape' => false)) . ' ';
echo $this->Html->link('<i class="icon-envelope-alt"></i>', array("controller" => $this->params['controller'], "action" => "send", $id), array('title' => 'Enviar Boleto', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Enviar Boleto', 'escape' => false)) . ' ';
echo $this->Html->link('<i class="icon-edit"></i>', array("controller" => $this->params['controller'], "action" => "edit", $id), array('title' => 'Editar Registro', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Editar Registro', 'escape' => false)) . ' ';

if(isset($this->params['named']['trashed']) && $this->AppPermissions->check("{$this->name}.trash")){
	echo $this->Html->link('<i class="icon-inbox"></i>', array("controller" => $this->params['controller'], "action" => "restore", $id), array('title' => 'Restaurar', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Restaurar', 'escape' => false), 'Tem certeza de que deseja restaurar este registro do lixo?') . ' ';
}else{
	echo $this->Html->link('<i class="icon-trash"></i>', array("controller" => $this->params['controller'], "action" => "trash", $id), array('title' => 'Lixeira', 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => 'Lixeira', 'escape' => false), 'Tem certeza de que deseja mover esse registro para o lixo?') . ' ';
}
?>