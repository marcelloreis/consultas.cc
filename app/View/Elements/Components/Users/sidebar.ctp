<div id="left">
	<ul class="tiles" style="margin:25px 0 0 25px;">
		<?php if($this->action == 'index'):?>
			<li class="blue">
				<?php echo $this->Html->link('<span><i class="glyphicon-database_plus"></i></span><span class="name">' . __('Add') . '</span>', array('action' => 'add', 'plugin' => false), array('escape' => false))?>
			</li>
			<li class="red">
				<?php echo $this->Html->link('<span><i class="glyphicon-list"></i></span><span class="name">' . __('List') . '</span>', array('action' => 'index', 'plugin' => false), array('escape' => false))?>
			</li>
			<li class="grey">
				<?php echo $this->Html->link('<span><i class="icon-trash"></i></span><span class="name">' . __('Trash') . '</span>', array('action' => 'index', ACTION_TRASH => true, 'plugin' => false), array('escape' => false))?>
			</li>
		<?php else:?>
			<li class="blue">
				<?php $client_id = isset($this->params['named']['client_id'])?$this->params['named']['client_id']:0;?>
				<?php echo $this->Html->link('<span><i class="glyphicon-database_plus"></i></span><span class="name">' . __('Add') . '</span>', array('action' => 'add', 'client_id' => $client_id, 'plugin' => false), array('escape' => false))?>
			</li>
			<li class="red">
				<?php echo $this->Html->link('<span><i class="glyphicon-list"></i></span><span class="name">' . __('List') . '</span>', array('action' => 'accounts', 'plugin' => false), array('escape' => false))?>
			</li>
			<li class="grey">
				<?php echo $this->Html->link('<span><i class="icon-trash"></i></span><span class="name">' . __('Trash') . '</span>', array('action' => 'accounts', ACTION_TRASH => true, 'plugin' => false), array('escape' => false))?>
			</li>
		<?php endif?>
	</ul>
</div>
