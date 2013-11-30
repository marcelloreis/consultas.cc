<?php if(isset($entity['Association']) && count($entity['Association'])):?>
<?php 
/**
* Inicializa o array com as informacoes extras da entidade
*/
$extras = array(
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
		$extras['extra_mobile']['id'][$v['mobile_id']] = $v['mobile_id'];

		if(empty($extras['extra_mobile']['ini']) || $extras['extra_mobile']['ini'] > $v['year']){
			$extras['extra_mobile']['ini'] = $v['year'];
		}
		if(empty($extras['extra_mobile']['end']) || $extras['extra_mobile']['end'] < $v['year']){
			$extras['extra_mobile']['end'] = $v['year'];
		}
	}

	/**
	* Carrega as informacoes extras de telefone fixo
	*/
	if(!empty($v['landline_id'])){
		$extras['extra_landline']['id'][$v['landline_id']] = $v['landline_id'];
		if(empty($extras['extra_landline']['ini']) || $extras['extra_landline']['ini'] > $v['year']){
			$extras['extra_landline']['ini'] = $v['year'];
		}
		if(empty($extras['extra_landline']['end']) || $extras['extra_landline']['end'] < $v['year']){
			$extras['extra_landline']['end'] = $v['year'];
		}
	}

	/**
	* Carrega as informacoes extras do endereco
	*/
	if(!empty($v['address_id'])){
		$extras['extra_locator']['id'][$v['address_id']] = $v['address_id'];
		if(empty($extras['extra_locator']['ini']) || $extras['extra_locator']['ini'] > $v['year']){
			$extras['extra_locator']['ini'] = $v['year'];
		}
		if(empty($extras['extra_locator']['end']) || $extras['extra_locator']['end'] < $v['year']){
			$extras['extra_locator']['end'] = $v['year'];
		}
	}
}
?>

<div class="row-fluid">
	<div class="box box-bordered box-color satblue">
		<div class="box-title">
			<h3><i class="icon-reorder"></i> Informações Extras</h3>
		</div>
		<div class="box-content nopadding">
			<ul class="tabs tabs-inline tabs-top">
				<?php foreach($extras as $k => $v):?>
				<li>
					<?php foreach($v['id'] as $k2 => $v2):?>
						<?php echo $this->Form->hidden("Assoc.id.{$v2}", array('value' => $v2))?>
					<?php endforeach?>
					<?php echo $this->Html->link('<i class="' . $v['icon'] . '"></i> ' . __(Inflector::pluralize(ucfirst(str_replace('extra_', '', $k)))), array('#' => "tab-{$k}"), array('rel' => "/entities/{$k}", 'data-toggle' => 'tab', 'class-box' => "tab-{$k}", 'class' => 'load-assoc', 'escape' => false))?>
				</li>
				<?php endforeach?>
				<li>
					<?php echo $this->Html->link('<i class="icon-group"></i> Família', array('#' => "tab-extra_family"), array('rel' => "/entities/extra_family/{$entity['Entity']['id']}", 'data-toggle' => 'tab', 'class-box' => "tab-extra_family", 'class' => 'load-assoc', 'escape' => false))?>
				</li>
				<li>
					<?php echo $this->Html->link('<i class="icon-group"></i> Vizinhos', array('#' => "tab-extra_neighbors"), array('rel' => "/entities/extra_neighbors/{$entity['Entity']['id']}", 'data-toggle' => 'tab', 'class-box' => "tab-extra_neighbors", 'class' => 'load-assoc', 'escape' => false))?>
				</li>
			</ul>
			<div class="tab-content padding tab-content-inline tab-content-bottom">
				<?php foreach($extras as $k => $v):?>
				<div class="tab-pane" id="<?php echo "tab-{$k}"?>"></div>
				<?php endforeach?>
				<div class="tab-pane" id="tab-extra_family"></div>
				<div class="tab-pane" id="tab-extra_neighbors"></div>
			</div>
		</div>
	</div>
</div>

<?php endif?>