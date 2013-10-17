
<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered">
            <div class="box-content nopadding">
                <?php echo $this->AppForm->create($modelClass, array('class' => $requestHandler, 'classForm' => 'form-horizontal form-bordered'));?>
                    <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                <!-- <form action="#" method="POST" class='form-horizontal form-bordered'> -->

                    <!-- Carrega o campo de busca -->
                    <div class="span12" style="margin-left: 0px;">
                        <div class="span8">
                            <div class="control-group">
                                <label for="textfield" class="control-label"><?php echo __('Search')?></label>
                                <div class="controls">
                                    <div class="input-append input-prepend">
                                        <span class="add-on"><i class="icon-search"></i></span>
                                        <?php $value = isset($this->params['named']['search'])?$this->params['named']['search']:'';?>
                                        <?php echo $this->AppForm->input('search', array('label' => __('What are you looking for') . ", {$userLogged['given_name']}?", 'value' => $value, 'template' => 'form-input-clean', 'class' => 'input-xxlarge'));?>
                                        <button class="btn" type="button"><?php echo __('Search')?></button>&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="span4">
                            <div class="control-group">
                                <label for="textfield" class="control-label"><?php echo __('Settings')?></label>
                                <div class="controls">
                                <?php 
                                echo $this->Html->link('<i class="glyphicon-user_remove"></i>', array('action' => 'drop'), array('escape' => false, 'class' => 'btn btn-default', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Delete %s and %s'), __d('fields', 'Users'), __d('fields', 'Groups'))), sprintf(__('Are you sure you want to delete all %s and %s?'), __d('fields', 'Users'), __('Controllers'))) . '&nbsp;';
                                echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'drop_perms'), array('escape' => false, 'class' => 'btn btn-default', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Delete %s'), __('Permissions'))), sprintf(__('Are you sure you want to delete all %s?'), __('Permissions'))) . '&nbsp;';
                                echo $this->Html->link('<i class="glyphicon-group"></i>', array('action' => 'update_aros'), array('escape' => false, 'class' => 'btn btn-default', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Refresh %s and %s'), __d('fields', 'Users'), __d('fields', 'Groups')))) . '&nbsp;';
                                echo $this->Html->link('<i class="glyphicon-cogwheels"></i>', array('action' => 'update_acos'), array('escape' => false, 'class' => 'btn btn-default', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Refresh %s'), __('Controllers')))) . '&nbsp;';
                                ?>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- </form> -->
                <?php echo $this->AppForm->end(); ?>
            </div>
        </div>
    </div>
</div>