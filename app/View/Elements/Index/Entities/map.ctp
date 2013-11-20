<?php 
$this->append('css-on-demand');
echo $this->Html->css(array('map'));
$this->end();
?>


<style type="text/css">
.popover {width: 200px !important;}
</style>
<ul id="map">
	<?php 
// debug($map_found);die;
	foreach($uf as $k => $v){
		/**
		* Parametros padrao para todos os links
		*/
		$link_params = array('id' => strtolower($v), 'escape' => false);

		$url = '#';
		$link_params['class'] = 'map-tooltip'; 
		$link_params['title'] = "{$states[$k]}"; 
		$badge = '';
		if(array_key_exists($k, $map_found)){
			// $link_params['rel'] = 'popover'; 
			$link_params['data-toggle'] = 'tooltip'; 
			// $link_params['data-trigger'] = 'manual'; 
			// $link_params['data-placement'] = 'right'; 
			// $link_params['data-html'] = 'true'; 
			// $link_params['data-content'] = $this->element('Index/Entities/map-popover-content', array('map_found' => $map_found[$k]));
			$link_params['class'] = 'map-tooltip ativo'; 
			$url = $map_found[$k]['url'];
			$badge = '<span style="z-index:100;position: relative;top:-40px;left:25px" class="badge badge-info">' . $map_found[$k]['qt'] . '</span>';
		}
		?>
		<li estado="<?php echo strtolower($v)?>" id="c<?php echo strtolower($v)?>"><?php echo $this->Html->link($this->Html->image('null.gif', array('alt' => 'SC')) . $badge, $url, $link_params)?></li>
		<?php
	}
	?>
</ul>