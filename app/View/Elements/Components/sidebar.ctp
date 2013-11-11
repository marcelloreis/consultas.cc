<?php if(!$this->fetch('sidebar')):?>
	<div id="left">
		<ul class="tiles" style="margin:25px 0 0 25px;">
			<li class="blue">
				<?php echo $this->Html->link('<span><i class="glyphicon-check"></i></span><span class="name">' . __('Add') . '</span>', array('controller' => 'checklist', 'plugin' => false), array('escape' => false))?>
			</li>
			<li class="red">
				<?php echo $this->Html->link('<span><i class="glyphicon-check"></i></span><span class="name">' . __('List') . '</span>', array('controller' => 'checklist', 'plugin' => false), array('escape' => false))?>
			</li>
		</ul>
	</div>
<?php endif?>
<?php echo $this->fetch('sidebar')?>
