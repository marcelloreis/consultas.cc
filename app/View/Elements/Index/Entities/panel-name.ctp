<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered">
            <div class="box-content nopadding">
                <!-- Nome -->
                <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
                    <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                    <div class="control-group address-fields">
                        <label class="control-label" style="padding:10px 0 10px 30px; width:auto;"><i class="glyphicon-pencil"></i>&nbsp;<?php echo __('Search by name')?></label>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="textfield"><?php echo __('Type Name')?></label>
                        <div class="controls">
                            <?php $name = isset($this->params['named']['name']) && !empty($this->params['named']['name'])?$this->params['named']['name']:''?>
                            <?php echo $this->AppForm->input('name', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-alpha', 'value' => $name, 'placeholder' => __('type name')))?>
                        </div>
                    </div>
                    <?php echo $this->AppForm->btn('Search', array('template' => '/Entities/form-input-btn'));?>
                <?php echo $this->AppForm->end()?>
            </div>
        </div>
    </div>
</div>