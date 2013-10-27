<div class="row-fluid">
	<div class="span12">
		<div class="box box-color box-bordered">
			<div class="box-title">
				<h3>
					<i class="glyphicon-check"></i>
					<?php echo __('Products')?>
				</h3>
			</div>
			<div class="box-content">
				<div class="basic-margin">
					<?php 
					echo $this->Html->link('<i class="glyphicon-vcard"></i> ' . __('Document Data'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="icon-plus-sign-alt"></i> ' . __('Death'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-iphone"></i> ' . __('Mobile'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="icon-phone"></i> ' . __('Landline'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-globe"></i> ' . __('Locator'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-building"></i> ' . __('Shareholding'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-parents"></i> ' . __('Family'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-home"></i> ' . __('Neighbor'), '#', array('class' => 'btn', 'escape' => false));
					?>
				</div>
			</div>
		</div>
	</div>
</div>