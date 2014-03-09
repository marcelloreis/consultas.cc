<?php 
$this->append('css-on-demand');
echo $this->Html->css(array('map'));
$this->end();

/**
* Mapa de posicoes dos badges dos estados
*/
$pos = array(
	//AL
	'2' => array(
		'top' => '-20',
		'left' => '35',
		),
	//AM
	'3' => array(
		'top' => '-90',
		'left' => '105',
		),
	//BA
	'5' => array(
		'top' => '-100',
		'left' => '55',
		),
	//DF
	'7' => array(
		'top' => '-25',
		'left' => '15',
		),
	//ES
	'8' => array(
		'top' => '-40',
		'left' => '25',
		),
	//GO
	'9' => array(
		'top' => '-60',
		'left' => '40',
		),
	//MG
	'11' => array(
		'top' => '-80',
		'left' => '80',
		),
	//MS
	'12' => array(
		'top' => '-65',
		'left' => '40',
		),
	//MT
	'13' => array(
		'top' => '-80',
		'left' => '80',
		),
	//PE
	'16' => array(
		'top' => '-30',
		'left' => '95',
		),
	//PI
	'17' => array(
		'top' => '-60',
		'left' => '40',
		),
	//PR
	'18' => array(
		'top' => '-45',
		'left' => '30',
		),
	//RJ
	'19' => array(
		'top' => '-20',
		'left' => '40',
		),
	//RN
	'20' => array(
		'top' => '-45',
		'left' => '30',
		),
	//RS
	'23' => array(
		'top' => '-75',
		'left' => '45',
		),
	//SC
	'24' => array(
		'top' => '-45',
		'left' => '45',
		),
	//SP
	'26' => array(
		'top' => '-65',
		'left' => '45',
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