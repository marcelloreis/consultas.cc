<h4><i class="glyphicon-iphone"></i> <?php echo __('Search by mobile')?></h4>
<div style="border: 1px solid #E5E5E5;" class="row-fluid">
    <!-- Telefone Fixo -->
    <?php echo $this->AppForm->create($modelClass, array('url' => '/entities/mobile', 'defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
        <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
        <div class="control-group">
            <label class="control-label" for="textfield"><?php echo __('Type DDD')?></label>
            <div class="controls">
                <?php $ddd = isset($this->params['named']['ddd']) && !empty($this->params['named']['ddd'])?$this->params['named']['ddd']:''?>
                <?php echo $this->AppForm->input('ddd', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-2Digits', 'value' => $ddd, 'placeholder' => __('type ddd')))?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="textfield"><?php echo __('Type Mobile')?></label>
            <div class="controls">
                <?php $mobile = isset($this->params['named']['mobile']) && !empty($this->params['named']['mobile'])?$this->params['named']['mobile']:''?>
                <?php echo $this->AppForm->input('tel', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-phone-9', 'value' => $mobile, 'placeholder' => __('type mobile')))?>
            </div>
        </div>
        <?php echo $this->AppForm->btn('Search', array('template' => '/Entities/form-input-btn'));?>
    <?php echo $this->AppForm->end()?>
</div>