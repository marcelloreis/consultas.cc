<?php if(isset($billing)):?>
	<div class="pull-right">
			<ul class="stats">
				<li class='satgreen'>
					<i class="icon-money"></i>
					<div class="details">
						<span class="big">R$<?php echo $this->AppUtils->num2br($billing['balance'])?></span>
						<span>Saldo</span>
					</div>
				</li>
				<li class='lightred'>
					<i class="icon-calendar"></i>
					<div class="details">
						<span class="big"><?php echo $billing['validity_txt']?></span>
						<span>Validade</span>
					</div>
				</li>
			</ul>
		</div>
<?php endif?>