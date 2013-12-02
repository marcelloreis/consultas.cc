	<div id="navigation">
		<div class="container-fluid">
			<?php 
			echo $this->Html->link('consultas.cc', array('controller' => 'users', 'action' => 'dashboard', 'plugin' => false), array('id' => 'brand', 'escape' => false));
			echo $this->Html->link('<i class="icon-reorder"></i>', '#', array('class' => 'toggle-nav', 'rel' => 'tooltip', 'data-placement' => 'bottom', 'title' => 'Ocultar/Exibir Menu Lateral', 'escape' => false));

			echo $this->element('Components/Navigation/menu-top');
			echo $this->element('Components/Navigation/menu-user');
			?>
		</div>
	</div>


<!-- Top Content -->
<?php echo $this->element('Layouts/Default/top-content')?>

<!-- Top view -->
<?php echo $this->element('Layouts/Default/top-view')?>
