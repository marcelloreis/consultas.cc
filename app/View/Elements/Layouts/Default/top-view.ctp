<div class="row-fluid">
	<div class="span12">
		<div class="box">
			<?php if(!$this->fetch('title-view')):?>
				<div class="box-title">
					<h3>
						<i class="icon-table"></i>
						<?php echo __(ucfirst($this->params['action']))?>
					</h3>
				</div>
			<?php endif?>
			<?php echo $this->fetch('title-view')?>
			<div class="box-content">