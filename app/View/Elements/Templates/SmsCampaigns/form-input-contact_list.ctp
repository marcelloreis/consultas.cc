<div class="control-group">
    <label for="%id%" class="control-label">%label%</label>
        <div class="controls">
            <div class="row-fluid">
                <div class="span8">
                    %input%
                </div>
                <div class="span4">
                    <?php echo $this->AppForm->input('sms_group_id', array('empty' => 'Listas Cadastradas', 'options' => $sms_groups, 'class' => 'chosen-select', 'label' => 'Lista de Contatos', 'template' => 'form-input-clean'))?>
                    <div class="alert alert-info">
                        <h4>Um número por linha</h4>
                        <p>
                            Formatos aceitos:<br/>
                            Nome,8488880000<br/>
                            84 8888-0000
                        </p>
                    </div>
                </div>
            </div>
        </div>
</div>