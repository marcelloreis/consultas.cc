<h4><i class="glyphicon-iphone"></i> Buscar por Tel. Móvel</h4>
<div style="border: 1px solid #E5E5E5;" class="row-fluid">
    <!-- Telefone Fixo -->
    <?php echo $this->AppForm->create($modelClass, array('url' => '/entities/mobile', 'defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
        <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
        <div class="control-group">
            <label class="control-label" for="textfield">Digite o DDD</label>
            <div class="controls">
                <?php $ddd = isset($this->params['named']['ddd']) && !empty($this->params['named']['ddd'])?$this->params['named']['ddd']:''?>
                <?php echo $this->AppForm->input('ddd', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-2Digits', 'value' => $ddd, 'placeholder' => 'Digite o DDD'))?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="textfield">Digite o Tel. Móvel</label>
            <div class="controls">
                <?php $mobile = isset($this->params['named']['mobile']) && !empty($this->params['named']['mobile'])?$this->params['named']['mobile']:''?>
                <?php echo $this->AppForm->input('tel', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-phone-9', 'value' => $mobile, 'placeholder' => 'Digite o Tel. Móvel'))?>
            </div>
        </div>
        <?php echo $this->AppForm->btn('Buscar', array('template' => '/Entities/form-input-btn'));?>
    <?php echo $this->AppForm->end()?>
</div>