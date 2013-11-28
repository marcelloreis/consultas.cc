<?php 
/**
* Oculta o sidebar
*/
$this->assign('sidebar-class-hidden', 'nav-hidden');
?>

<div class="box">
	<div class="box-content">
		<div class="row-fluid">
			<div class="pricing-tables">
				<?php foreach($packages as $k => $v):?>
					<ul class="pricing <?php echo ($v['Package']['popular'])?'highlighted':'';?> <?php echo $v['Package']['color']?> span3">
						<li class="head">
							<div class="name"><?php echo $v['Package']['name']?></div>
							<div class="price">
								$<?php echo !empty($v['Package']['price'])?substr($v['Package']['price'], 0, strpos($v['Package']['price'], ',')):'0';?>
								<?php if($v['Package']['price']):?>
									<?php $avg = ceil($v['Package']['price'] / $this->AppUtils->num2db($this->Session->read("Billing.prices_val.{$v['Package']['id']}.1")));?>
									<span><?php echo "Média de {$avg} consultas"?></span>
								<?php endif?>									
							</div>
						</li>
						<?php foreach($products as $k2 => $v2):?>
							<?php $price = $this->Session->read("Billing.prices_val.{$v['Package']['id']}.{$v2['Product']['id']}");?>
							<li>
								<div class="row-fluid">
									<div class="span7"><?php echo $v2['Product']['name']?></div>
									<div class="span1">|</div>
									<div class="span4"><?php echo (!empty($price))?$price:'0';?>/Consulta</div>
								</div>
							</li>
						<?php endforeach?>
						<li class="button">
							<?php echo $this->Html->link('Comprar', array(), array('class' => 'btn btn-grey-4'))?>
							<?php if(!empty($v['Package']['validity_days'])):?>
								<div class="help-text"><?php echo sprintf('%s Dias de validade', $v['Package']['validity_days'])?></div>
							<?php endif?>
						</li>
					</ul>
				<?php endforeach?>
			</div>
		</div>
	</div>
</div>
