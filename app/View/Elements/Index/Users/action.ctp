<?php 
echo $this->Html->link('<i class="icon-search"></i>', array("controller" => $this->params['controller'], "action" => "view", $id), array('title' => __('View Record'), 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => __('View Record'), 'escape' => false)) . '&nbsp;';
echo $this->Html->link('<i class="icon-edit"></i>', array("controller" => $this->params['controller'], "action" => "edit", $id), array('title' => __('Edit Record'), 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => __('Edit Record'), 'escape' => false)) . '&nbsp;';

if(isset($this->params['named']['trashed']) && $this->AppPermissions->check("{$this->name}.trash")){
	echo $this->Html->link('<i class="icon-inbox"></i>', array("controller" => $this->params['controller'], "action" => "restore", $id), array('title' => __('Restore'), 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => __('Restore'), 'escape' => false), __('Are you sure you want to restore this record from the trash?')) . '&nbsp;';
}else{
	echo $this->Html->link('<i class="icon-trash"></i>', array("controller" => $this->params['controller'], "action" => "trash", $id), array('title' => __('Trash'), 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => __('Trash'), 'escape' => false), __('Are you sure you want to move this record to the trash?')) . '&nbsp;';
}
echo $this->Html->link('<i class="icon-unlock"></i>', array("controller" => 'acl', "action" => "permissions", 'aro_id' => $id, 'aro' => 'user', 'plugin' => 'main'), array('title' => __('Permissions'), 'class' => 'btn', 'rel' => 'tooltip', 'data-original-title' => __('Permissions'), 'escape' => false)) . '&nbsp;';