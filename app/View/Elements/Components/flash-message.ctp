<?php if(isset($multiple)):?>
    <?php foreach ($multiple as $k => $v):?>
		<div class="alert <?php echo $class?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $v?>
		</div>
    <?php endforeach?>
<?php elseif(isset($message)):?>
		<div class="alert <?php echo isset($class)?$class:'warning';?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message?>
		</div>
<?php endif?>