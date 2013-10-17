<div class="row-fluid">
	<div class="span12">
		<div class="box">
			<div class="box-title">
				<h3>
					<i class="icon-table"></i>
					<?php echo __(ucfirst($this->params['action']))?>
				</h3>
			</div>
			<div class="box-content nopadding">
				<?php echo $this->Session->flash(FLASH_SESSION_FORM)?>