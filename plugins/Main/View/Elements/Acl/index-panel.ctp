<div class="panel-body">
        <div class="row text-small">
            <div class="col-sm-4 m-b-mini">
                <select style="width:130px" class="input-sm inline form-control">
                    <option value="0">Ação em lote</option>
                    <option value="1">Apagar selecionados</option>
                    <option value="2">Edição em lote</option>
                    <option value="3">Exportar</option>
                </select>
                <button class="btn btn-sm btn-white">Aplicar</button>
            </div>


            <div class="col-sm-4 m-b-mini">
                <?php 
                echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'drop'), array('escape' => false, 'class' => 'btn btn-default', 'data-toggle' => 'tooltip', 'data-original-title' => sprintf(__('Delete %s and %s'), __d('fields', 'Users'), __d('fields', 'Groups')), 'onclick' => "return confirm('" . sprintf(__('Are you sure you want to delete all %s and %s?'), __d('fields', 'Users'), __('Controllers')) . "');")) . '&nbsp;';
                echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'drop_perms'), array('escape' => false, 'class' => 'btn btn-default', 'data-toggle' => 'tooltip', 'data-original-title' => sprintf(__('Delete %s'), __('Permissions')), 'onclick' => "return confirm('" . sprintf(__('Are you sure you want to delete all %s?'), __('Permissions')) . "');")) . '&nbsp;';
                echo $this->Html->link('<i class="icon-refresh"></i>', array('action' => 'update_aros'), array('escape' => false, 'class' => 'btn btn-default', 'data-toggle' => 'tooltip', 'data-original-title' => sprintf(__('Refresh %s and %s'), __d('fields', 'Users'), __d('fields', 'Groups')))) . '&nbsp;';
                echo $this->Html->link('<i class="icon-refresh"></i>', array('action' => 'update_acos'), array('escape' => false, 'class' => 'btn btn-default', 'data-toggle' => 'tooltip', 'data-original-title' => sprintf(__('Refresh %s'), __('Controllers')))) . '&nbsp;';
                ?>
            </div>


            <div class="col-sm-4">
                <div class="input-group">
                    <input type="text" class="input-sm form-control">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-sm btn-white">Buscar</button>
                    </span>
                </div>
            </div>
        </div>
    </div>