<?php if(isset($multiple)):?>
    <?php foreach ($multiple as $k => $v):?>
		<div style="margin: 0px;" class="alert <?php echo $class?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $v?>
		</div>
    <?php endforeach?>
<?php elseif(isset($message)):?>
		<div style="margin: 0px;" class="alert <?php echo isset($class)?$class:'warning';?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message?>
		</div>
<?php endif?>