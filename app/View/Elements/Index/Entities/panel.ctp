<?php 
$params = $this->params['named'];
?>
<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered">
            <div class="box-title">
                <h3>
                    <i class="glyphicon-search"></i>
                    <?php echo __('Searches')?>
                </h3>
                <ul class="tabs">
                    <li class="<?php echo isset($params['doc'])?'active':'';?>">
                        <a href="#t1" data-toggle="tab"><?php echo __('By Document')?></a>
                    </li>
                    <li class="<?php echo isset($params['name'])?'active':'';?>">
                        <a href="#t2" data-toggle="tab"><?php echo __('By Name')?></a>
                    </li>
                    <li class="<?php echo isset($params['landline'])?'active':'';?>">
                        <a href="#t3" data-toggle="tab"><?php echo __('By Landline')?></a>
                    </li>
                    <li class="<?php echo isset($params['zipcode']) || isset($params['street'])?'active':'';?>">
                        <a href="#t4" data-toggle="tab"><?php echo __('By Address')?></a>
                    </li>
                </ul>
            </div>
            <div class="box-content nopadding">
                <div class="tab-content">
                    <!-- Documento -->
                    <div class="tab-pane <?php echo isset($params['doc'])?'active':'';?>" id="t1">
                        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
                            <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                            <div class="control-group">
                                <label class="control-label" for="textfield"><?php echo __('Type Document')?></label>
                                <div class="controls">
                                    <div class="input-append input-prepend">
                                        <span class="add-on"><i class="icon-search"></i></span>
                                        <?php $doc = isset($this->params['named']['doc']) && !empty($this->params['named']['doc'])?$this->params['named']['doc']:''?>
                                        <?php echo $this->AppForm->input('doc', array('template' => 'form-input-clean', 'value' => $doc, 'placeholder' => __('type document')))?>
                                        <button type="submit" class="btn"><?php echo __('Search')?></button>
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->AppForm->end()?>
                    </div>

                    <!-- Nome -->
                    <div class="tab-pane <?php echo isset($params['name'])?'active':'';?>" id="t2">
                        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
                            <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                            <div class="control-group">
                                <label class="control-label" for="textfield"><?php echo __('Type Name')?></label>
                                <div class="controls">
                                    <div class="input-append input-prepend">
                                        <span class="add-on"><i class="icon-search"></i></span>
                                        <?php $name = isset($this->params['named']['name']) && !empty($this->params['named']['name'])?$this->params['named']['name']:''?>
                                        <?php echo $this->AppForm->input('name', array('template' => 'form-input-clean', 'value' => $name, 'placeholder' => __('type name')))?>
                                        <button type="submit" class="btn"><?php echo __('Search')?></button>
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->AppForm->end()?>
                    </div>

                    <!-- Telefone Fixo -->
                    <div class="tab-pane <?php echo isset($params['landline'])?'active':'';?>" id="t3">
                        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-column form-bordered'))?>
                            <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label" for="textfield" style="width:20%;"><?php echo __('Type DDD')?></label>
                                    <div class="controls">
                                        <div class="input-append input-prepend">
                                            <span class="add-on"><i class="icon-phone"></i></span>
                                            <?php $ddd = isset($this->params['named']['ddd']) && !empty($this->params['named']['ddd'])?$this->params['named']['ddd']:''?>
                                            <?php echo $this->AppForm->input('ddd', array('template' => 'form-input-clean', 'value' => $ddd, 'class' => 'input-small', 'placeholder' => __('type ddd')))?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="span8">
                                <div class="control-group">
                                    <label class="control-label" for="textfield"><?php echo __('Type Landline')?></label>
                                    <div class="controls">
                                        <div class="input-append input-prepend">
                                            <span class="add-on"><i class="icon-phone"></i></span>
                                            <?php $landline = isset($this->params['named']['landline']) && !empty($this->params['named']['landline'])?$this->params['named']['landline']:''?>
                                            <?php echo $this->AppForm->input('landline', array('template' => 'form-input-clean', 'value' => $landline, 'placeholder' => __('type landline')))?>
                                            <button type="submit" style="margin-left:15px" class="btn"><?php echo __('Search')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->AppForm->end()?>
                    </div>

                    <!-- Endereços -->
                    <div class="tab-pane <?php echo isset($params['zipcode']) || isset($params['street'])?'active':'';?>" id="t4">
                        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-column form-bordered'))?>
                            <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                            <?php echo $this->form->hidden('address', array('value' => '1'));?>



                            <div class="control-group">
                                <label class="control-label" for="textfield"><?php echo __('Type Zipcode')?></label>
                                <div class="controls">
                                    <div class="input-append input-prepend">
                                        <span class="add-on"><?php echo __('Zipcode')?></span>
                                        <?php $zipcode = isset($this->params['named']['zipcode']) && !empty($this->params['named']['zipcode'])?$this->params['named']['zipcode']:''?>
                                        <?php echo $this->AppForm->input('zipcode', array('template' => 'form-input-clean', 'value' => $zipcode, 'class' => 'input-small zipcode-field', 'placeholder' => __('type zipcode')))?>
                                        &nbsp;
                                        <span style="margin-left:10px;" class="add-on"><?php echo __('Number ini')?></span> 
                                        <?php $number_ini = isset($this->params['named']['number_ini']) && !empty($this->params['named']['number_ini'])?$this->params['named']['number_ini']:''?>
                                        <?php echo $this->AppForm->input('number_ini', array('template' => 'form-input-clean', 'value' => $number_ini, 'class' => 'input-small', 'placeholder' => __('type number')))?>
                                        &nbsp;
                                        <span style="margin-left:10px;" class="add-on"><?php echo __('Number End')?></span>
                                        <?php $number_end = isset($this->params['named']['number_end']) && !empty($this->params['named']['number_end'])?$this->params['named']['number_end']:''?>
                                        <?php echo $this->AppForm->input('number_end', array('template' => 'form-input-clean', 'value' => $number_end, 'class' => 'input-small', 'placeholder' => __('type number')))?>
                                        <button type="submit" style="margin-left:15px" class="btn"><?php echo __('Search')?></button>
                                    </div>
                                </div>
                            </div>


                            <div class="control-group address-fields">
                                <label class="control-label" style="padding:10px 0 10px 30px;"><i class="glyphicon-globe"></i>&nbsp;<?php echo __('Type Address')?></label>
                            </div>


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
                                            <?php echo $this->AppForm->input('state_id', array('template' => 'form-input-clean', 'empty' => __('Select'), 'options' => $states, 'class' => 'chosen-select select-state'))?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="span6 address-fields">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('City')?></label>
                                    <div class="controls">
                                        <div class="input-xlarge">
                                            <?php echo $this->AppForm->input('city_id', array('template' => 'form-input-clean', 'empty' => __('Select a state'), 'options' => $cities, 'class' => 'chosen-select'))?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                        <?php echo $this->AppForm->end()?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>