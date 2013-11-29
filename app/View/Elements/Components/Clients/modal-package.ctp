<!-- Modal Confirmar compra -->
<div id="modalPackage-<?php echo $prospect_pkg_id?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo $package_name?></h3>
	</div>
	<div class="modal-body">
		<p>Caso você já possua um plano vigente com créditos disponíveis, ao comprar o plano <strong><?php echo $package_name?></strong>, seu saldo antigo será adicionado ao seu saldo atual. Os valores das consultas serão de acordo com o plano <strong><?php echo $package_name?></strong>, bem como a validade dos créditos.</p>
	</div>
	<div class="modal-footer">
		<?php echo $this->Html->link('Cancelar', '#', array('class' => 'btn', 'data-dismiss' => 'modal', 'aria-hidden' => true))?>
		<?php echo $this->Html->link('Continuar', array('controller' => 'clients', 'action' => 'add', 'prospect_pkg_id' => $prospect_pkg_id), array('class' => 'btn btn-primary', 'aria-hidden' => true))?>
	</div>
</div>
