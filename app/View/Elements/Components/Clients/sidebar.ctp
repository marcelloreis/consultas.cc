<div id="left">
	<ul class="tiles" style="margin:25px 0 0 25px;">
		<?php if($this->action == 'index'):?>
			<li class="blue">
				<?php echo $this->Html->link('<span><i class="glyphicon-database_plus"></i></span><span class="name">Adicionar</span>', array('action' => 'add', 'plugin' => false), array('escape' => false))?>
			</li>
			<li class="red">
				<?php echo $this->Html->link('<span><i class="glyphicon-list"></i></span><span class="name">Listar todos</span>', array('action' => 'index', 'plugin' => false), array('escape' => false))?>
			</li>
			<li class="grey">
				<?php echo $this->Html->link('<span><i class="icon-trash"></i></span><span class="name">Lixeira</span>', array('action' => 'index', ACTION_TRASH => true, 'plugin' => false), array('escape' => false))?>
			</li>
		<?php else:?>
			<li class="blue">
				<?php $prospect_pkg_id = isset($this->params['named']['prospect_pkg_id'])?$this->params['named']['prospect_pkg_id']:0;?>
				<?php echo $this->Html->link('<span><i class="glyphicon-database_plus"></i></span><span class="name">Adicionar</span>', array('action' => 'add', 'prospect_pkg_id' => $prospect_pkg_id, 'plugin' => false), array('escape' => false))?>
			</li>
			<li class="red">
				<?php echo $this->Html->link('<span><i class="glyphicon-list"></i></span><span class="name">Listar todos</span>', array('action' => 'prospects', 'plugin' => false), array('escape' => false))?>
			</li>
			<li class="grey">
				<?php echo $this->Html->link('<span><i class="icon-trash"></i></span><span class="name">Lixeira</span>', array('action' => 'prospects', ACTION_TRASH => true, 'plugin' => false), array('escape' => false))?>
			</li>
		<?php endif?>
	</ul>
</div>
