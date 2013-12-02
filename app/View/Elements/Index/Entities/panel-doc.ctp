<h4><i class="glyphicon-vcard"></i> Buscar por Documento</h4>
<div style="border: 1px solid #E5E5E5;" class="row-fluid">
    <!-- Documento -->
    <?php echo $this->AppForm->create($modelClass, array('url' => '/entities/doc', 'defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
        <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
        <div class="control-group">
            <label class="control-label" for="EntityDoc">Digite o Documento</label>
            <div class="controls">
                <?php $doc = isset($this->params['named']['doc']) && !empty($this->params['named']['doc'])?$this->params['named']['doc']:''?>
                <?php echo $this->AppForm->input('doc', array('template' => 'form-input-clean', 'class' => 'input-block-level msk-int', 'value' => $doc, 'placeholder' => 'Digite o Documento'))?>
            </div>
        </div>
        <?php echo $this->AppForm->btn('Buscar', array('template' => '/Entities/form-input-btn'));?>
    <?php echo $this->AppForm->end()?>
</div>