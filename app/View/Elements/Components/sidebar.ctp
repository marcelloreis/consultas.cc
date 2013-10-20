<div id="left">
	<ul class="tiles" style="margin:25px 0 0 25px;">
		<li class="blue">
			<?php echo $this->Html->link('<span><i class="glyphicon-group"></i></span><span class="name">' . __d('app', 'People') . '</span>', array('controller' => 'entities', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="red">
			<?php echo $this->Html->link('<span><i class="glyphicon-building"></i></span><span class="name">' . __d('app', 'Companies') . '</span>', array('controller' => 'companies', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="lime">
			<?php echo $this->Html->link('<span><i class="glyphicon-check"></i></span><span class="name">' . __d('app', 'Check') . '</span>', array('controller' => 'checklist', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="brown">
			<?php echo $this->Html->link('<span><i class="glyphicon-bank"></i></span><span class="name">' . __d('app', 'Bacen') . '</span>', array('controller' => 'bacen', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="blue">
			<?php echo $this->Html->link('<span><i class="icon-ambulance"></i></span><span class="name">' . __d('app', 'Deaths') . '</span>', array('controller' => 'deaths', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="blue">
			<?php echo $this->Html->link('<span><i class="icon-search"></i></span><span class="name">' . __d('app', 'Locator') . '</span>', array('controller' => 'locator', 'plugin' => false), array('escape' => false))?>
		</li>
	</ul>
</div>