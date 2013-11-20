<?php 
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
$this->end();

$this->append('scrips-on-demand');
echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'));
$this->end();
?>

<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered">
            <div class="box-content nopadding">
                <!-- Endereços -->
                <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-column form-bordered'))?>
                    <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                    <?php echo $this->form->hidden('address', array('value' => '1'));?>
                    <div class="control-group address-fields">
                        <label class="control-label" style="padding:10px 0 10px 30px; width:auto;"><i class="glyphicon-globe"></i>&nbsp;<?php echo __('Search by address')?></label>
                    </div>

                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="textfield"><?php echo __('Zipcode')?></label>
                            <div class="controls">
                                <?php $zipcode = isset($this->params['named']['zipcode']) && !empty($this->params['named']['zipcode'])?$this->params['named']['zipcode']:''?>
                                <?php echo $this->AppForm->input('zipcode', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-zipcode', 'value' => $zipcode, 'placeholder' => __('type zipcode')))?>
                            </div>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="control-group">
                            <label class="control-label" for="textfield"><?php echo __('Nº Ini')?></label>
                            <div class="controls">
                                <?php $number_ini = isset($this->params['named']['number_ini']) && !empty($this->params['named']['number_ini'])?$this->params['named']['number_ini']:''?>
                                <?php echo $this->AppForm->input('number_ini', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-int', 'value' => $number_ini, 'placeholder' => __('type number')))?>
                            </div>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="control-group">
                            <label class="control-label" for="textfield"><?php echo __('Nº End')?></label>
                            <div class="controls">
                                <?php $number_end = isset($this->params['named']['number_end']) && !empty($this->params['named']['number_end'])?$this->params['named']['number_end']:''?>
                                <?php echo $this->AppForm->input('number_end', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-int', 'value' => $number_end, 'placeholder' => __('type number')))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group"></div>

                    <div class="control-group address-fields">
                        <label class="control-label"><?php echo __('Street Name')?></label>
                        <div class="controls">
                            <?php $street = isset($this->params['named']['street']) && !empty($this->params['named']['street'])?$this->params['named']['street']:''?>
                            <?php echo $this->AppForm->input('street', array('template' => 'form-input-clean', 'value' => $street, 'class' => 'input-block-level', 'placeholder' => __('type street name')))?>
                        </div>
                    </div>

                    <div class="span6 address-fields">
                        <div class="control-group">
                            <label class="control-label"><?php echo __('State')?></label>
                            <div class="controls">
                                <div class="input-xlarge">
                                    <?php echo $this->AppForm->input('state_id', array('template' => 'form-input-clean', 'empty' => __('Select'), 'options' => $states, 'class' => 'input-block-level chosen-select select-state'))?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="span6 address-fields">
                        <div class="control-group">
                            <label class="control-label"><?php echo __('City')?></label>
                            <div class="controls">
                                <div class="input-xlarge">
                                    <?php echo $this->AppForm->input('city_id', array('template' => 'form-input-clean', 'empty' => __('Select a state'), 'options' => $cities, 'class' => 'input-block-level chosen-select'))?>
                                </div>
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