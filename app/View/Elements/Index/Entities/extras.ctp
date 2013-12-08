<?php if(isset($entity['Association']) && count($entity['Association'])):?>
<?php 
/**
* Inicializa o array com as informacoes extras da entidade
*/
$extras = array(
	'mobile' => array(
		'icon' => 'icon-mobile-phone',
		'id' => array(),
		), 
	'landline' => array(
		'icon' => 'icon-phone',
		'id' => array(),
		), 
	'address' => array(
		'icon' => 'icon-globe',
		'id' => array(),
		), 
	);
foreach ($entity['Association'] as $k => $v) {
	foreach ($extras as $k2 => $v2) {
		/**
		* Carrega as informacoes extras de telefone movel
		*/
		if(!empty($v["{$k2}_id"])){
			$extras[$k2]['id'][$v["{$k2}_id"]] = $v["{$k2}_id"];
		}
	}
}
?>

<div id="entity-extras" class="row-fluid">
	<div class="box box-bordered box-color satblue">
		<div class="box-title">
			<h3><i class="icon-reorder"></i> Informações Extras</h3>
		</div>
		<div class="box-content nopadding">
			<ul class="tabs tabs-inline tabs-top">
				<?php foreach($extras as $k => $v):?>
				<?php $k = str_replace('address', 'locator', $k)?>
				<li>
					<?php foreach($v['id'] as $k2 => $v2):?>
						<?php echo $this->Form->hidden("Assoc.id.{$v2}", array('value' => $v2))?>
					<?php endforeach?>
					<?php echo $this->Html->link('<i class="' . $v['icon'] . '"></i> ' . __(ucfirst($k)) . ' (' . count($v['id']) . ')', array('#' => "tab-{$k}"), array('rel' => "/entities/extra_{$k}", 'data-toggle' => 'tab', 'class-box' => "tab-{$k}", 'class' => 'load-assoc', 'escape' => false))?>
				</li>
				<?php endforeach?>
				<li>
					<?php echo $this->Html->link('<i class="icon-group"></i> Possíveis Parentes', array('#' => "tab-extra_family"), array('rel' => "/entities/extra_family/{$entity['Entity']['id']}", 'data-toggle' => 'tab', 'class-box' => "tab-extra_family", 'class' => 'load-assoc', 'escape' => false))?>
				</li>
				<li>
					<?php echo $this->Html->link('<i class="icon-home"></i> Vizinhos', array('#' => "tab-extra_neighbors"), array('rel' => "/entities/extra_neighbors/{$entity['Entity']['id']}", 'data-toggle' => 'tab', 'class-box' => "tab-extra_neighbors", 'class' => 'load-assoc', 'escape' => false))?>
				</li>
			</ul>
			<div class="tab-content padding tab-content-inline tab-content-bottom">
				<div id="tab-info">
					<h4><span id="assoc-loading" class="hide"><?php echo $this->Html->image('extras-load.gif')?></span><span id="assoc-loading-msg"> Clique nas abas acima para mais informações</span></h4>
				</div>

				<?php foreach($extras as $k => $v):?>
					<?php $k = str_replace('address', 'locator', $k)?>
					<div class="tab-pane" id="<?php echo "tab-{$k}"?>"></div>
				<?php endforeach?>
				<div class="tab-pane" id="tab-extra_family"></div>
				<div class="tab-pane" id="tab-extra_neighbors"></div>
			</div>
		</div>
	</div>
</div>

<?php endif?>