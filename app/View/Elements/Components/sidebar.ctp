<div id="left">
	<ul class="tiles" style="margin:25px 0 0 25px;">
		<li class="blue">
			<?php echo $this->Html->link('<span><i class="glyphicon-group"></i></span><span class="name">' . __('People') . '</span>', array('controller' => 'entities', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="red">
			<?php echo $this->Html->link('<span><i class="glyphicon-building"></i></span><span class="name">' . __('Companies') . '</span>', array('controller' => 'companies', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="lime">
			<?php echo $this->Html->link('<span><i class="glyphicon-check"></i></span><span class="name">' . __('Check') . '</span>', array('controller' => 'checklist', 'plugin' => false), array('escape' => false))?>
		</li>
	</ul>
</div>