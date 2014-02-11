<div class="control-group">
    <label for="%id%" class="control-label">Localização</label>
	    <div class="controls">
		    <div class="row-fluid">

		        <div class="span4">
		        	<h5>Selecione o Estado</h5>
		            <?php echo $this->AppForm->input('state_id', array('empty' => 'Selecione', 'options' => $states, 'placeholder' => 'Bairros', 'class' => 'input-block-level chosen-select select-state', 'template' => 'form-input-clean'))?>
		        	<h5>Selecione a Cidade</h5>
		        	<div>
		            	<?php echo $this->AppForm->input('city_id', array('empty' => 'Selecione um Estado', 'options' => $cities, 'placeholder' => 'Bairros', 'class' => 'input-block-level chosen-select city-campaigns', 'template' => 'form-input-clean'))?>
		        	</div>
		        </div>
		        
		        <div class="span4">
		            %input%
		        </div>
		    	<div class="span4">
					<div class="alert alert-info">
						<h4>Um Bairro por linha</h4>
						<p>
							Formatos aceitos:<br/>
							Copacabana</br>
							Leblon</br>
							Bairro da Liberdade</br>
							Boa Viagem</br> 							
						</p>
					</div>
				</div>
		    </div>
	    </div>
</div>