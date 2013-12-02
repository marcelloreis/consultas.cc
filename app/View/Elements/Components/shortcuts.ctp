<?php if(isset($balance)):?>
	<div class="pull-right">
			<ul class="stats">
				<!-- <li class='teal'>
					<i class="icon-search"></i>
					<div class="details">
						<span class="big"><?php echo $billing['qt_queries']?></span>
						<span>Consultas</span>
					</div>
				</li> -->
				<li class='satgreen'>
					<i class="icon-money"></i>
					<div class="details">
						<span class="big">R$<?php echo $this->AppUtils->num2br($balance)?></span>
						<span>Saldo</span>
					</div>
				</li>
				<li class='lightred'>
					<i class="icon-calendar"></i>
					<div class="details">
						<span class="big"><?php echo $this->AppUtils->dt2br($validity_orig)?></span>
						<span>Validade</span>
					</div>
				</li>
			</ul>
		</div>
<?php endif?>