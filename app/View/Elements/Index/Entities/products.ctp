<div class="row-fluid">
	<div class="span12">
		<div class="box box-color box-bordered">
			<div class="box-title">
				<h3>
					<i class="glyphicon-check"></i>
					<?php echo __d('app', 'Products')?>
				</h3>
			</div>
			<div class="box-content">
				<div class="basic-margin">
					<?php 
					echo $this->Html->link('<i class="glyphicon-vcard"></i> ' . __d('app', 'Document Data'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="icon-plus-sign-alt"></i> ' . __d('fields', 'Death'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-iphone"></i> ' . __d('fields', 'Mobile'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="icon-phone"></i> ' . __d('fields', 'Landline'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-road"></i> ' . __d('app', 'Address Data'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-building"></i> ' . __d('app', 'Shareholding'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-parents"></i> ' . __d('app', 'Family'), '#', array('class' => 'btn', 'escape' => false));
					echo $this->Html->link('<i class="glyphicon-home"></i> ' . __d('app', 'Vizinhos'), '#', array('class' => 'btn', 'escape' => false));
					?>
				</div>
			</div>
		</div>
	</div>
</div>