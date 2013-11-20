<div id="left">
	<ul class="tiles" style="margin:25px 0 0 25px;">
		<li class="blue">
			<?php echo $this->Html->link('<span><i class="glyphicon-database_plus"></i></span><span class="name">' . __('Add') . '</span>', array('action' => 'add', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="red">
			<?php echo $this->Html->link('<span><i class="glyphicon-list"></i></span><span class="name">' . __('List') . '</span>', array('action' => 'index', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="grey">
			<?php echo $this->Html->link('<span><i class="icon-trash"></i></span><span class="name">' . __('Trash') . '</span>', array('action' => 'index', ACTION_TRASH => true, 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="green">
			<?php echo $this->Html->link('<span><i class="glyphicon-restart"></i></span><span class="name">' . __('Load Locale') . '</span>', array('controller' => 'translations', 'action' => 'loadLocale', 'plugin' => false), array('escape' => false))?>
		</li>
	</ul>
</div>