<?php 
/**
* Oculta o sidebar
*/
$this->assign('sidebar-class-hidden', 'nav-hidden');

/**
* Oculta a barra de navegacao
*/
$this->start('navigation');
echo '&nbsp;';
$this->end();

$this->append('css-on-demand');
echo $this->Html->css(array(
    'plugins/easy-pie-chart/jquery.easy-pie-chart',
            ));
$this->end();

echo $this->Html->script(array(
    'plugins/sparklines/jquery.sparklines.min',
    'plugins/easy-pie-chart/jquery.easy-pie-chart.min',
    'plugins/flot/jquery.flot.min',
    'plugins/flot/jquery.flot.resize.min',
            ), array('defer' => true));

echo $this->Html->script('https://www.google.com/jsapi');

echo $this->element('Index/Imports/charts-gauge');
// echo $this->element('Index/Imports/real-time');
?>
<?php if(!empty($uf)):?>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<i class="glyphicon-stopwatch"></i>
						Painel de controle de importação binária
					</h3>
				</div>
				<div class="box-content">
					<div class="row-fluid">
						<div class="span3">
							<p>
								<?php echo $this->Html->link('Importar Fixos', array('action' => 'reload_binary', 'landlines', $uf), array('class' => 'btn-block btn btn-large'))?>
							</p>
						</div>					
						<div class="span3">
							<p>
								<?php echo $this->Html->link('Importar Móveis', array('action' => 'reload_binary', 'mobiles', $uf), array('class' => 'btn-block btn btn-large'))?>
							</p>
						</div>					
						<div class="span3">
							<p>
								<?php echo $this->Html->link('Parar Importação', array('action' => 'lock', 0), array('class' => 'btn-block btn btn-large'))?>
							</p>
						</div>					
					</div>
				</div>
			</div>					
		</div>
	</div>
<?php else:?>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<i class="glyphicon-stopwatch"></i>
						Painel de controle de importação por texto
					</h3>
				</div>
				<div class="box-content">
					<div class="row-fluid">
						<div class="span3">
							<p>
								<?php echo $this->Html->link('Importar Fixos', array('action' => 'reload_text', 'landlines'), array('class' => 'btn-block btn btn-large'))?>
							</p>
						</div>					
						<div class="span3">
							<p>
								<?php echo $this->Html->link('Importar Móveis', array('action' => 'reload_text', 'mobiles'), array('class' => 'btn-block btn btn-large'))?>
							</p>
						</div>					
						<div class="span3">
							<p>
								<?php echo $this->Html->link('Parar Importação', array('action' => 'lock', 0), array('class' => 'btn-block btn btn-large'))?>
							</p>
						</div>					
					</div>
				</div>
			</div>					
		</div>
	</div>
<?php endif?>

<?php if(isset($imports['records_processed'])):?>
	<div id="dados-importados" class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<i class="icon-th-large"></i>
						Dados Importados 
					</h3>
					<div class="pull-right"><code>Previsão: <?php echo date('l H:i', mktime((date('H')+$imports['remaining_times']['hour']), (date('i')+$imports['remaining_times']['min']), (date('s')+$imports['remaining_times']['sec']), date('m'), (date('d')+$imports['remaining_times']['day']), date('Y')))?></code></div>
				</div>
				<div class="box-content">
					<div class="row-fluid">
						<div class="span3">
							<ul class="pagestats style-3">
								<li>
									<div class="spark">
										<div data-trackcolor="#fae2e2" data-color="#f96d6d" data-percent="<?php echo $imports['progress']?>" class="chart easyPieChart progress" style="width: 80px; height: 80px; line-height: 80px;"><?php echo $imports['progress']?>%<canvas height="80" width="80"></canvas></div>
									</div>
									<div class="bottom">
										<span class="name">Progresso da Importação</span>
									</div>
								</li>
							</ul>
						</div>

						<div class="span9">
							<ul class="stats">
								<li class="lightgrey">
									<i style="margin-top:10px;" class="glyphicon-inbox_out"></i>
									<div class="details">
										<span class="big"><?php echo $this->AppUtils->num2qt($imports['records_to_process'])?></span>
										<span class="records_to_process">Registros Encontrados</span>
									</div>
								</li>
								<li class="green">
									<i style="margin-top:10px;" class="glyphicon-inbox_in"></i>
									<div class="details">
										<span class="big records_processed"><?php echo $this->AppUtils->num2qt($imports['records_processed'])?></span>
										<span>Registros Processados</span>
									</div>
								</li>
								<li class="lightgrey">
									<i style="margin-top:10px;" class="glyphicon-stopwatch"></i>
									<div class="details">
										<span class="big elapsed"><?php echo $imports['elapsed']?></span>
										<span>Percorrido</span>
									</div>
								</li>
								<li class="green">
									<i style="margin-top:10px;" class="glyphicon-stopwatch"></i>
									<div class="details">
										<span class="big remaining"><?php echo $imports['remaining']?></span>
										<span>Restante</span>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>					
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<i class="glyphicon-stopwatch"></i>
						Processos por minuto/hora/dia
					</h3>
				</div>
				<div class="box-content">
					<div class="row-fluid">
						<div class="span4" style="height: 400px;" id='chart_minuts'></div>
						<div class="span4" style="height: 400px;" id='chart_hour'></div>
						<div class="span4" style="height: 400px;" id='chart_day'></div>
					</div>
				</div>
			</div>					
		</div>
	</div>

	<!-- div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<i class="glyphicon-stopwatch"></i>
						Processos por hora
					</h3>
				</div>
				<div class="box-content">
					<div class="row-fluid">
						<div id='chart_hour'></div>
					</div>
				</div>
			</div>					
		</div>
	</div -->

	<!-- div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<i class="glyphicon-stopwatch"></i>
						Processos por dia
					</h3>
				</div>
				<div class="box-content">
					<div class="row-fluid">
						<div id='chart_day'></div>
					</div>
				</div>
			</div>					
		</div>
	</div -->

	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3>
						<i class="glyphicon-stopwatch"></i>
						Tempo dos procesos
					</h3>
				</div>
				<div class="box-content">
					<div class="row-fluid">
						<div class="span3" id="chart_next" style="height: 500px; padding-top: 100px;"></div>
						<div class="span9" id="chart_timing" style="height: 500px;"></div>
					</div>
				</div>
			</div>					
		</div>
	</div>

	<div class="row-fluid">
		<?php 
		unset($imports['statistics']['associations']);
		?>
		<?php foreach($imports['statistics'] as $k => $v):?>
			<div class="span3">
				<div class="box box-color box-bordered">
					<div class="box-title">
						<h3>
							<i class="icon-th-large"></i>
							<?php echo __($k)?>
						</h3>
					</div>
					<div class="box-content">
						<div class="row-fluid">
							<ul class="tiles tiles-small">
								<li class="green">
									<span class="label label-info"><?php echo $this->AppUtils->num2qt($v['success'])?></span>
									<a href="#"><span><i class="glyphicon-ok_2"></i></span><span class='name'><?php echo __('Success')?></span></a>
								</li>
								<li class="lightred">
									<span class="label label-info"><?php echo $this->AppUtils->num2qt($v['fails'])?></span>
									<a href="#"><span><i class="icon-ban-circle"></i></span><span class='name'><?php echo __('Fails')?></span></a>
								</li>
							</ul>
						</div>
					</div>
				</div>					
			</div>
		<?php endforeach?>
	</div>
<?php endif?>