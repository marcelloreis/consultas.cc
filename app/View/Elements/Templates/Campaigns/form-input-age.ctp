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
		    	<div class="span1">
		        	<?php echo $this->AppForm->input('ignore_age_null', array('label' => 'Faixa Etária', 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue', 'template' => 'form-input-clean'));?>
		    	</div>
		    	<div class="span3">
					<div class="alert alert-info">
						<h4>Filtro</h4>
						<p>Clique na caixa ao lado para incluir as pessoas que nao tiverem registro de idade.</p>
					</div>
				</div>
		    	<div class="span6">
					<div class="alert alert-info">
						<h4>Informação</h4>
						<p>Para filtrar por faixa etária, insira a idade inicial no campo 'Inicial' e a idade final no campo 'Final'</p>
					</div>
				</div>
		    </div>
	    </div>
</div>