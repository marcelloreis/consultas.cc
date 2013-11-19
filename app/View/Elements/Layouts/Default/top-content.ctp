<div class="container-fluid  <?php echo $this->fetch('sidebar-class-hidden', '')?>" id="content">
	<?php echo $this->fetch('sidebar', $this->element('Components/sidebar'))?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1><?php echo $this->fetch('title-content', __(ucfirst($this->params['controller'])))?></h1>
				</div>

				<?php echo $this->element('Components/shortcuts')?>
				
			</div>
			<?php echo $this->Session->flash(FLASH_SESSION_FORM)?>
			<?php echo $this->element('Components/breadcrumbs')?>