
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
                                <label for="textfield" class="control-label"></label>
                                <div class="controls">
                                    <?php echo $this->Html->link('<i class="icon-plus"></i> ' . sprintf(__("Add a %s"), __d('fields', ucfirst($this->params['controller']))), array('action' => 'add'), array('class' => 'btn', 'escape' => false))?>
                                    <!-- <button class="btn"><i class="icon-plus"></i> <?php echo sprintf(__("Add a %s"), __d('fields', $this->params['controller']))?></button> -->
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