<div class="form-actions">
	<?php 
	if(!empty($this->data['Campaign']['id']) && !empty($this->data['Campaign']['process_state'])){
		switch ($this->data['Campaign']['process_state']) {
			case CAMPAIGN_RUN_PROCESSED:
				?>
				<div class="alert alert-warning">
					<p><strong>Atenção!</strong></p>
					<p>Não é possível alterar a campanha, pois ela esta sendo processada neste exato momento.</p>
				</div>
				<?php
				break;
			case CAMPAIGN_PROCESSED:
			case CAMPAIGN_DOWNLOADED:
			case CAMPAIGN_DOWNLOADED_EXPIRED:
				?>
				<div class="alert alert-error">
					<p><strong>Atenção!</strong></p>
					<p>
						A campanha já foi processada. <br>
						Ao salvar as alterações, a campanha sera tarifada novamente com os valores vigentes.
					</p>
				</div>
    			<button type="submit" class="btn btn-primary">%value%</button>
				<?php
				echo $this->Html->link('Descartar alterações', array('action' => 'index'), array('class' => 'btn'));
				break;

			default:
				?>
	    		<button type="submit" class="btn btn-primary">%value%</button>
	    		<?php
				echo $this->Html->link('Descartar alterações', array('action' => 'index'), array('class' => 'btn'));
				break;
		}
	}
	?>
</div>