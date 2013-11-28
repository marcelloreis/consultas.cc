<?php 
/**
* Oculta o sidebar
*/
$this->assign('sidebar-class-hidden', 'nav-hidden');
?>

<div class="row-fluid">
	<div class="span6">
		<?php echo $this->element('Index/Entities/map')?>
	</div>
	<div class="span6">
		<?php echo $this->element('Index/Entities/map-entities')?>
	</div>
</div>

