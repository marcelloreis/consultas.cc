<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered">
            <div class="box-content nopadding">
                <!-- Telefone Fixo -->
                <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-column form-bordered'))?>
                    <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                    <div class="control-group address-fields">
                        <label class="control-label" style="padding:10px 0 10px 30px; width:auto;"><i class="icon-phone"></i>&nbsp;<?php echo __('Search by landline')?></label>
                    </div>

                    <div class="span4">
                        <div class="control-group">
                            <label class="control-label" for="textfield" style="width:20%;"><?php echo __('Type DDD')?></label>
                            <div class="controls">
                                <?php $ddd = isset($this->params['named']['ddd']) && !empty($this->params['named']['ddd'])?$this->params['named']['ddd']:''?>
                                <?php echo $this->AppForm->input('ddd', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-2Digits', 'value' => $ddd, 'placeholder' => __('type ddd')))?>
                            </div>
                        </div>
                    </div>
                    <div class="span8">
                        <div class="control-group">
                            <label class="control-label" for="textfield"><?php echo __('Type Landline')?></label>
                            <div class="controls">
                                <?php $landline = isset($this->params['named']['landline']) && !empty($this->params['named']['landline'])?$this->params['named']['landline']:''?>
                                <?php echo $this->AppForm->input('landline', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-phone', 'value' => $landline, 'placeholder' => __('type landline')))?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group"></div>
                    <?php echo $this->AppForm->btn('Search', array('template' => '/Entities/form-input-btn'));?>
                <?php echo $this->AppForm->end()?>
            </div>
        </div>
    </div>
</div>