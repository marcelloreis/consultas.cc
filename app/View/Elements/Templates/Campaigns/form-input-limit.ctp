<?php 
/**
* Carrega o preco de cada registro do mailing de a cordo com o pacote do cliente
*/
$package_id = $this->Session->read('Client.package_id');
$price = $this->Session->read("Billing.prices_val.{$package_id}." . PRODUCT_MAILING);
?>
<div class="control-group">
    <label for="%id%" class="control-label">%label%</label>
	    <div class="controls">
		    <div class="row-fluid">
		    	<div class="span3">
		        	%input%
		    	</div>
		    	<div class="span9">
					<div class="alert alert-info">
						<h4>Limite de Registros</h4>
						<p>
							Seu saldo atual é de <strong>R$<?php echo $this->AppUtils->num2br($balance)?></strong><br>
							Cada registro encontrado nesta campanha custará <strong><?php echo $price?></strong><br>
							A quantidade máxima que essa campanha poderá trazer são <strong><?php echo $this->AppUtils->num2qt(floor($balance/$this->AppUtils->num2db($price)))?></strong> registros
						</p>
					</div>
				</div>
		    </div>
	    </div>
</div>