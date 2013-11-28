
<?php if(isset($entity['Association']) && count($entity['Association'])):?>
<?php 
/**
* Inicializa o array com as informacoes extras da entidade
*/
$infos = array(
	'extra_mobile' => array(
		'icon' => 'icon-mobile-phone',
		'id' => array(),
		'ini' => 0,
		'end' => 0,
		), 
	'extra_landline' => array(
		'icon' => 'icon-phone',
		'id' => array(),
		'ini' => 0,
		'end' => 0,
		), 
	'extra_locator' => array(
		'icon' => 'icon-globe',
		'id' => array(),
		'ini' => 0,
		'end' => 0,
		), 
	);
foreach ($entity['Association'] as $k => $v) {
	/**
	* Carrega as informacoes extras de telefone movel
	*/
	if(!empty($v['mobile_id'])){
		$infos['extra_mobile']['id'][$v['mobile_id']] = $v['mobile_id'];

		if(empty($infos['extra_mobile']['ini']) || $infos['extra_mobile']['ini'] > $v['year']){
			$infos['extra_mobile']['ini'] = $v['year'];
		}
		if(empty($infos['extra_mobile']['end']) || $infos['extra_mobile']['end'] < $v['year']){
			$infos['extra_mobile']['end'] = $v['year'];
		}
	}

	/**
	* Carrega as informacoes extras de telefone fixo
	*/
	if(!empty($v['landline_id'])){
		$infos['extra_landline']['id'][$v['landline_id']] = $v['landline_id'];
		if(empty($infos['extra_landline']['ini']) || $infos['extra_landline']['ini'] > $v['year']){
			$infos['extra_landline']['ini'] = $v['year'];
		}
		if(empty($infos['extra_landline']['end']) || $infos['extra_landline']['end'] < $v['year']){
			$infos['extra_landline']['end'] = $v['year'];
		}
	}

	/**
	* Carrega as informacoes extras do endereco
	*/
	if(!empty($v['address_id'])){
		$infos['extra_locator']['id'][$v['address_id']] = $v['address_id'];
		if(empty($infos['extra_locator']['ini']) || $infos['extra_locator']['ini'] > $v['year']){
			$infos['extra_locator']['ini'] = $v['year'];
		}
		if(empty($infos['extra_locator']['end']) || $infos['extra_locator']['end'] < $v['year']){
			$infos['extra_locator']['end'] = $v['year'];
		}
	}
}
?>
<div class="row-fluid">
	<div class="span12">
		<div class="box box-color box-bordered">
			<div class="box-title">
				<h3>
					<i class="glyphicon-check"></i>
					<?php echo __('Extra Informations')?>
				</h3>
			</div>
			<div class="box-content">
				<ul class="stats">
					<?php foreach($infos as $k => $v):?>
						<li class="<?php echo (count($v['id']))?'green':'lightgrey';?>">
							<?php foreach($v['id'] as $k2 => $v2):?>
								<?php echo $this->Form->hidden("Assoc.id.{$v2}", array('value' => $v2))?>
							<?php endforeach?>
							<?php echo $this->Html->link('<i class="' . $v['icon'] . '"></i>', '#', array('rel' => "/entities/{$k}", 'class-box' => "assoc-{$k}", 'class' => 'load-assoc', 'escape' => false))?>
							<div class="details">
								<span class="big"><?php echo count($v['id'])?></span>
								<span><?php echo __(Inflector::pluralize(ucfirst(str_replace('extra_', '', $k))))?></span>
							</div>
						</li>
					<?php endforeach?>
					<li class="green">
						<?php echo $this->Html->link('<i class="icon-group"></i>', '#', array('rel' => "/entities/extra_family/{$entity['Entity']['id']}", 'class-box' => "assoc-family", 'class' => 'load-assoc', 'escape' => false))?>
						<div class="details">
							<span class="big"><?php echo __('Parents')?></span>
						</div>
					</li>
					<li class="green">
						<?php echo $this->Html->link('<i class="icon-home"></i>', '#', array('rel' => "/entities/extra_neighbors/{$entity['Entity']['id']}", 'class-box' => "assoc-neighbors", 'class' => 'load-assoc', 'escape' => false))?>
						<div class="details">
							<span class="big"><?php echo __('Neighbors')?></span>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php endif?>