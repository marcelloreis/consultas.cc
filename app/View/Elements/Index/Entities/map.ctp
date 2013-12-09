<?php 
$this->append('css-on-demand');
echo $this->Html->css(array('map'));
$this->end();

/**
* Mapa de posicoes dos badges dos estados
*/
$pos = array(
	'8' => array(
		'top' => '-40',
		'left' => '25',
		),
	'19' => array(
		'top' => '-20',
		'left' => '40',
		),
	);
?>


<style type="text/css">
.popover {width: 200px !important;}
</style>
<ul id="map">
	<?php 
// debug($entity['map']);die;
	foreach($uf as $k => $v){
		/**
		* Parametros padrao para todos os links
		*/
		$link_params = array('id' => strtolower($v), 'escape' => false);

		$link_params['class'] = 'map-tooltip'; 
		$link_params['title'] = "{$states[$k]}"; 
		$badge = '';
		if(array_key_exists($k, $entity['map'])){
			$link_params['data-toggle'] = 'tooltip'; 
			$link_params['class'] = 'map-tooltip ativo'; 
			$badge = '<span style="z-index:100;position: relative;top:' . $pos[$k]['top'] . 'px;left:' . $pos[$k]['left'] . 'px" class="badge badge-info">' . $entity['map'][$k]['qt'] . '</span>';
		}
		?>
		<li estado="<?php echo strtolower($v)?>" id="c<?php echo strtolower($v)?>"><?php echo $this->Html->link($this->Html->image('null.gif', array('alt' => $link_params['title'])) . $badge, 'javascript:void(0);', $link_params)?></li>
		<?php
	}
	?>
</ul>