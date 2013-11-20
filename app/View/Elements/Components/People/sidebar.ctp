<div id="left">
	<ul class="tiles" style="margin:25px 0 0 25px;">
		<li class="blue">
			<?php echo $this->Html->link('<span><i class="glyphicon-vcard"></i></span><span class="name">' . __('Search by doc') . '</span>', array('controller' => 'entities', 'action' => 'people', 'search_by' => 'doc', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="green">
			<?php echo $this->Html->link('<span><i class="glyphicon-pencil"></i></span><span class="name">' . __('Search by name') . '</span>', array('controller' => 'entities', 'action' => 'people', 'search_by' => 'name', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="grey">
			<?php echo $this->Html->link('<span><i class="glyphicon-iphone"></i></span><span class="name">' . __('Search by mobile') . '</span>', array('controller' => 'entities', 'action' => 'people', 'search_by' => 'mobile', 'plugin' => false), array('escape' => false))?>
		</li>
		<li class="orange">
			<?php echo $this->Html->link('<span><i class="icon-phone"></i></span><span class="name">' . __('Search by landline') . '</span>', array('controller' => 'entities', 'action' => 'people', 'search_by' => 'landline', 'plugin' => false), array('escape' => false))?>
		</li>
		<!-- <li class="red">
			<?php echo $this->Html->link('<span><i class="glyphicon-globe"></i></span><span class="name">' . __('Search by address') . '</span>', array('controller' => 'entities', 'action' => 'people', 'search_by' => 'address', 'plugin' => false), array('escape' => false))?>
		</li> -->
	</ul>
</div>