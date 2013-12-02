<?php 
$breadcrumbs = array();
if(!is_null($this->params['plugin'])){
	array_push($breadcrumbs, $this->params['plugin']);
}
array_push($breadcrumbs, $this->params['controller']);
?>

<div class="breadcrumbs">
	<ul>
		<li>
			<?php echo $this->Html->link('Início', array('controller' => 'users', 'action' => 'dashboard'))?>
			<i class="icon-angle-right"></i>
		</li>
		<?php foreach($breadcrumbs as $k => $v):?>
			<li>
				<?php $last_controller = $v?>
				<?php echo $this->Html->link(__(ucfirst(str_replace('_', ' ', $v))), array('controller' => $v))?>
				<i class="icon-angle-right"></i>
			</li>
		<?php endforeach?>
		<li>
			<?php echo $this->Html->link(__(ucfirst(str_replace('_', ' ', $this->params['action']))), array('controller' => $last_controller, 'action' => $this->params['action']))?>
		</li>
	</ul>
	<div class="close-bread">
		<a href="#"><i class="icon-remove"></i></a>
	</div>
</div>