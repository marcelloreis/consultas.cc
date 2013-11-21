<div class="box-title">
	<h3>
		<?php $search_by = isset($this->params['named']['search_by'])?$this->params['named']['search_by']:'doc';?>
		<?php 
		switch ($search_by) {
			case 'name':
				$icon = 'glyphicon-pencil';
				break;
			case 'address':
				$icon = 'glyphicon-globe';
				break;
			case 'landline':
				$icon = 'icon-phone';
				break;
			case 'mobile':
				$icon = 'glyphicon-iphone';
				break;
			default:
				$icon = 'glyphicon-vcard';
				break;
		}
		?>
		<i class="<?php echo $icon?>"></i>
		<?php echo __("Search by {$search_by}")?>
	</h3>
</div>
