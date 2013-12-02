<h4><i class="glyphicon-pencil"></i> Buscar por nome</h4>
<div style="border: 1px solid #E5E5E5;" class="row-fluid">
    <!-- Nome -->
    <?php echo $this->AppForm->create($modelClass, array('url' => '/entities/name', 'defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
        <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
        <div class="control-group">
            <label class="control-label" for="EntityName">Digite o nome</label>
            <div class="controls">
                <?php $name = isset($this->params['named']['name']) && !empty($this->params['named']['name'])?$this->params['named']['name']:''?>
                <?php echo $this->AppForm->input('name', array('template' => 'form-input-clean', 'class' => 'input-block-level', 'value' => $name, 'placeholder' => 'Digite o nome'))?>
            </div>
        </div>
        <?php echo $this->AppForm->btn('Buscar', array('template' => '/Entities/form-input-btn'));?>
    <?php echo $this->AppForm->end()?>
</div>