<?php if(isset($balance)):?>
	<div class="pull-right">
			<ul class="stats">
				<li class='satblue'>
					<i class="icon-search"></i>
					<div class="details">
						<span class="big"><?php echo $this->AppUtils->num2qt($balance)?></span>
						<span>Consultas Restantes</span>
					</div>
				</li>
				<?php if(!empty($value_exceeded)):?>
					<li class='satgreen'>
						<i class="icon-money"></i>
						<div class="details">
							<span class="big">R$<?php echo $value_exceeded?></span>
							<span>Consultas Excedidas</span>
						</div>
					</li>
				<?php endif?>
			</ul>
		</div>
<?php endif?>