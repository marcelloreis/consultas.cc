<h4><i class="icon-phone"></i> Buscar por Tel. Fixo</h4>
<div style="border: 1px solid #E5E5E5;" class="row-fluid">
    <!-- Telefone Fixo -->
    <?php echo $this->AppForm->create($modelClass, array('url' => '/entities/landline', 'defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
        <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
        <div class="control-group">
            <label class="control-label" for="textfield">Digite o DDD</label>
            <div class="controls">
                <?php $ddd = isset($this->params['named']['ddd']) && !empty($this->params['named']['ddd'])?$this->params['named']['ddd']:''?>
                <?php echo $this->AppForm->input('ddd', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-2Digits', 'value' => $ddd, 'placeholder' => 'Digite o DDD'))?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="textfield">Digite o Tel. Fixo</label>
            <div class="controls">
                <?php $landline = isset($this->params['named']['landline']) && !empty($this->params['named']['landline'])?$this->params['named']['landline']:''?>
                <?php echo $this->AppForm->input('tel', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-phone', 'value' => $landline, 'placeholder' => 'Digite o Tel. Fixo'))?>
            </div>
        </div>
        <?php echo $this->AppForm->btn('Buscar', array('template' => '/Entities/form-input-btn'));?>
    <?php echo $this->AppForm->end()?>
</div>