<div class="row-fluid">
    <div class="box box-bordered">
        <div style="border-top: 2px solid #E5E5E5;" class="box-content nopadding">
            <?php echo $this->AppForm->create($modelClass, array('class' => $requestHandler, 'classForm' => 'form-horizontal form-bordered'));?>
                <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                <!-- Carrega o campo de busca -->
                <div class="row-fluid" style="margin-left: 0px;">
                    <div class="span9">
                        <div class="control-group">
                            <label class="control-label"><?php echo __('Search')?></label>
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
                    <div class="span3">
                        <div class="control-group">
                            <label style="padding:10px;" class="control-label">
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Bulk Actions') . ' <span class="caret"></span>', '#', array('class' => 'btn btn-primary dropdown-toggle', 'data-toggle' => 'dropdown', 'escape' => false));?>
                                    <ul class="dropdown-menu">
                                        <?php if(!empty($this->params['named']['trashed']) && $this->params['named']['trashed'] ==  1):?>
                                            <li>
                                                <?php echo $this->Html->link(__('Delete'), '#', array('class' => 'bulkAction', 'data-id' => $modelClass, 'data-url' => "/{$this->params['controller']}/delete"), __('Are you sure you want to delete all %s'))?>
                                            </li>
                                            <li>
                                                <?php echo $this->Html->link(__('Restore'), '#', array('class' => 'bulkAction', 'data-id' => $modelClass, 'data-url' => "/{$this->params['controller']}/restore"), __('Are you sure you want to restore this record from the trash?'))?>
                                            </li>
                                        <?php elseif($this->AppPermissions->check("{$this->name}.trash")):?>   
                                            <li>
                                                <?php echo $this->Html->link(__('Move to trash'), '#', array('class' => 'bulkAction', 'data-id' => $modelClass, 'data-url' => "/{$this->params['controller']}/trash"), __('Are you sure you want to move this record to the trash?'))?>
                                            </li>
                                        <?php endif;?>
                                    </ul>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            <?php echo $this->AppForm->end(); ?>
        </div>
    </div>
</div>