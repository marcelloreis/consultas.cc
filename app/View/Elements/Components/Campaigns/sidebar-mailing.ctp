<div id="left">
	<ul class="tiles" style="margin:25px 0 0 25px;">
		<li class="blue">
			<?php echo $this->Html->link('<span><i class="glyphicon-database_plus"></i></span><span class="name">Adicionar</span>', array('action' => 'edit_mailing', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="red">
			<?php echo $this->Html->link('<span><i class="glyphicon-list"></i></span><span class="name">Listar todos</span>', array('action' => 'index_mailing', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="grey">
			<?php echo $this->Html->link('<span><i class="icon-trash"></i></span><span class="name">Lixeira</span>', array('action' => 'index_mailing', ACTION_TRASH => true, 'plugin' => false), array('escape' => false))?>
		</li>
	</ul>
</div>
