<div class="control-group">
    <label for="%id%" class="control-label">%label%</label>
	    <div class="controls">
		    <div class="row-fluid">
		    	<div class="span1">
		        	<?php echo $this->AppForm->input('age_ini', array('label' => 'Faixa Etária', 'placeholder' => 'Inicial', 'class' => 'input-mini msk-int', 'template' => 'form-input-clean'))?>
		    	</div>
		    	<div class="span1">
		        	<?php echo $this->AppForm->input('age_end', array('label' => 'Faixa Etária', 'placeholder' => 'Final', 'class' => 'input-mini msk-int', 'template' => 'form-input-clean'))?>
		    	</div>
		    	<div class="span9 offset1">
					<div class="alert alert-info">
						<h4>Informação</h4>
						<p>Para filtrar por faixa etária, insira a idade inicial no campo 'Inicial' e a idade final no campo 'Final'</p>
					</div>
				</div>
		    </div>
	    </div>
</div>