<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered">
            <div class="box-content nopadding">
                <!-- Telefone Fixo -->
                <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-column form-bordered'))?>
                    <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                    <div class="span3">
                        <div style="border-top: 1px solid #E5E5E5;" class="control-group">
                            <label class="control-label" for="textfield" style="width:40%;"><?php echo __('Type DDD')?></label>
                            <div class="controls">
                                <?php $ddd = isset($this->params['named']['ddd']) && !empty($this->params['named']['ddd'])?$this->params['named']['ddd']:''?>
                                <?php echo $this->AppForm->input('ddd', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-2Digits', 'value' => $ddd, 'placeholder' => __('type ddd')))?>
                            </div>
                        </div>
                    </div>
                    <div class="span9">
                        <div style="border-top: 1px solid #E5E5E5;" class="control-group">
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