<div class="control-group">
    <label for="%id%" class="control-label">%label%</label>
	    <div class="controls">
		    <div class="row-fluid">
		    	<div class="span3">
		        	<?php echo $this->AppForm->input('limit', array('label' => 'Limite', 'class' => 'input-mini msk-int', 'template' => 'form-input-clean'))?>
		    	</div>
		    	<div class="span9">
					<div class="alert alert-info">
						<h4>Limite de Registros</h4>
						<p>Informe a quantidade máxima que deseja trazer nesta campanha.</p>
						<p>Caso não informe nenhum valor, a campanha poderá ter registros ilimitados.</p>
					</div>
				</div>
		    </div>
	    </div>
</div>