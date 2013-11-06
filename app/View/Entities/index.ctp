<?php 
/**
* Adiciona o painel de funcoes da grid
*/
echo $this->element('Index/Entities/panel');

$map = strtolower($modelClass);
?>

<div class="row-fluid">
	<div class="span6">
		<?php echo $this->element('Index/Entities/map')?>
	</div>
	<div class="span6">
		<?php echo $this->element('Index/Entities/map-entities')?>
	</div>
</div>

