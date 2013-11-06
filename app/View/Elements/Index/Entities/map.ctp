<style type="text/css">
.popover {width: 200px !important;}
</style>
<ul id="map">
	<?php 
	foreach($uf as $k => $v){
		/**
		* Parametros padrao para todos os links
		*/
		$link_params = array('id' => strtolower($v), 'escape' => false);

		if(array_key_exists($k, $map_found)){
			$link_params['rel'] = 'popover'; 
			$link_params['data-trigger'] = 'manual'; 
			$link_params['data-placement'] = 'right'; 
			$link_params['title'] = $states[$k]; 
			$link_params['data-html'] = 'true'; 
			$link_params['data-content'] = $this->element('Index/Entities/map-popover-content', array('map_found' => $map_found[$k]));
			$link_params['class'] = 'ativo'; 
		}
		?>
		<li estado="<?php echo strtolower($v)?>" id="c<?php echo strtolower($v)?>"><?php echo $this->Html->link($this->Html->image('null.gif', array('alt' => 'SC')), '#', $link_params)?></li>
		<?php
	}
	?>
</ul>