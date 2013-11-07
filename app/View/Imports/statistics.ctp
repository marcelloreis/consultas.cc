<?php 
$this->append('scrips-on-demand');
echo $this->Html->script(array(
    'plugins/sparklines/jquery.sparklines.min',
    'plugins/easy-pie-chart/jquery.easy-pie-chart.min',
    'plugins/flot/jquery.flot.min',
    'plugins/flot/jquery.flot.resize.min',
    'https://www.google.com/jsapi',
            ));
$this->end();

$this->append('css-on-demand');
echo $this->Html->css(array(
    'plugins/easy-pie-chart/jquery.easy-pie-chart',
            ));
$this->end();


echo $this->element('Index/Imports/charts-gauge')
?>



<div class="row-fluid">
	<div class="span12">
		<div class="box box-color box-bordered">
			<div class="box-title">
				<h3>
					<i class="icon-th-large"></i>
					<?php echo __('Imports Data')?>
				</h3>
			</div>
			<div class="box-content">

				<div class="row-fluid">
					<div class="span3">
						<ul class="pagestats style-3">
							<li>
								<div class="spark">
									<div data-trackcolor="#fae2e2" data-color="#f96d6d" data-percent="<?php echo $imports['progress']?>" class="chart easyPieChart" style="width: 80px; height: 80px; line-height: 80px;"><?php echo $imports['progress']?>%<canvas height="80" width="80"></canvas></div>
								</div>
								<div class="bottom">
									<span class="name"><?php echo __('Imports Progress')?></span>
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
									<span><?php echo __('Records to Process')?></span>
								</div>
							</li>
							<li class="green">
								<i style="margin-top:10px;" class="glyphicon-inbox_in"></i>
								<div class="details">
									<span class="big"><?php echo $this->AppUtils->num2qt($imports['records_processed'])?></span>
									<span><?php echo __('Records Processed')?></span>
								</div>
							</li>
							<li class="lightgrey">
								<i style="margin-top:10px;" class="glyphicon-stopwatch"></i>
								<div class="details">
									<span class="big"><?php echo $imports['elapsed']?></span>
									<span><?php echo __('Elapsed')?></span>
								</div>
							</li>
							<li class="green">
								<i style="margin-top:10px;" class="glyphicon-stopwatch"></i>
								<div class="details">
									<span class="big"><?php echo $imports['remaining']?></span>
									<span><?php echo __('Remaining')?></span>
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
					<?php echo __('Processed Per Minuts')?>
				</h3>
			</div>
			<div class="box-content">
				<div class="row-fluid">
					<div id='chart_minuts'></div>
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
					<?php echo __('Processed Per Hour')?>
				</h3>
			</div>
			<div class="box-content">
				<div class="row-fluid">
					<div id='chart_hour'></div>
				</div>
			</div>
		</div>					
	</div>
</div>

<div class="row-fluid">
	<?php 
	unset($imports['counters']['associations']);
	?>
	<?php foreach($imports['counters'] as $k => $v):?>
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