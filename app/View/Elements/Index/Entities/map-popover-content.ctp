<?php foreach($map_found as $k => $v):?>
	<div class='row-fluid'>
		<blockquote>
			<?php echo str_replace('"', "'", $this->Html->link("<span style='margin-right:10px;' class='label label-info'>{$v['qt']}</span>{$v['city']}", $v['url'], array('class' => 'btn btn-mini')))?>&nbsp;
		</blockquote>
	</div>
<?php endforeach?>