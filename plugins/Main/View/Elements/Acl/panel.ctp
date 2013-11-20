<div class="row-fluid">
    <div class="box box-bordered">
        <div style="border-top: 2px solid #E5E5E5;" class="box-content nopadding">
            <?php echo $this->AppForm->create($modelClass, array('class' => $requestHandler, 'classForm' => 'form-horizontal form-bordered'));?>
                <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                <div class="row-fluid">
                    <div class="span9">
                        <div class="control-group">
                            <label for="textfield" class="control-label"><?php echo __('Settings')?></label>
                            <div class="controls">
                            <?php 
                            echo $this->Html->link('<i class="glyphicon-user_remove"></i>', array('action' => 'drop'), array('escape' => false, 'class' => 'btn btn-default', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Delete %s and %s'), __d('fields', 'Users'), __d('fields', 'Groups'))), sprintf(__('Are you sure you want to delete all %s and %s?'), __d('fields', 'Users'), __('Controllers'))) . '&nbsp;';
                            echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'drop_perms'), array('escape' => false, 'class' => 'btn btn-default', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Delete %s'), __('Permissions'))), sprintf(__('Are you sure you want to delete all %s?'), __('Permissions'))) . '&nbsp;';
                            echo $this->Html->link('<i class="glyphicon-group"></i>', array('action' => 'update_aros'), array('escape' => false, 'class' => 'btn btn-default', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Refresh %s and %s'), __d('fields', 'Users'), __d('fields', 'Groups')))) . '&nbsp;';
                            echo $this->Html->link('<i class="icon-cogs"></i>', array('action' => 'update_acos'), array('escape' => false, 'class' => 'btn btn-default', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Refresh %s'), __('Controllers')))) . '&nbsp;';
                            echo $this->Html->link('<i class="glyphicon-restart"></i>', array('action' => 'reset_all'), array('escape' => false, 'class' => 'btn btn-default', 'rel' => 'tooltip', 'data-original-title' => __('Reset all settings')), sprintf(__('Are you sure you want to reset all settings?'), __('Permissions'))) . '&nbsp;';
                            ?>                                    
                            </div>
                        </div>
                    </div>

                    <div class="span3">
                        <div class="control-group">
                            <label for="textfield" class="control-label"><?php echo __('Bulk Actions')?></label>
                            <div class="controls">
                            <?php 
                            echo $this->Html->link('<i class="icon-unlock"></i>', '#', array('escape' => false, 'class' => 'btn perms-toggle-all btn-default allow', 'rel' => 'tooltip', 'data-original-title' => __('Allow All'))) . '&nbsp;';
                            echo $this->Html->link('<i class="icon-lock"></i>', '#', array('escape' => false, 'class' => 'btn perms-toggle-all btn-default deny', 'rel' => 'tooltip', 'data-original-title' => __('Deny All'))) . '&nbsp;';
                            ?>                                    
                            </div>
                        </div>
                    </div>
                </div>
            <?php echo $this->AppForm->end(); ?>
        </div>
    </div>
</div>