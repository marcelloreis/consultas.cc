	<div id="navigation">
		<div class="container-fluid">
			<a href="#" id="brand">FLAT</a>
			<a href="#" class="toggle-nav" rel="tooltip" data-placement="bottom" title="Toggle navigation"><i class="icon-reorder"></i></a>
			
			<?php echo $this->element('Components/menu-top')?>
			<?php echo $this->element('Components/menu-user')?>


		</div>
	</div>


<!-- Top Content -->
<?php echo $this->element('Layouts/Default/top-content')?>

<!-- Top view -->
<?php echo $this->element('Layouts/Default/top-view')?>
