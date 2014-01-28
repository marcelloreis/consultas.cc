<div class="control-group">
    <label for="%id%" class="control-label">%label%</label>
        <div class="controls">
            <div class="row-fluid">
                <div class="span8">
                    %input%
                </div>
                <div class="span4">
                    <?php echo $this->AppForm->input('sms_group_id', array('empty' => 'Grupos Cadastrados', 'options' => $sms_groups, 'class' => 'chosen-select', 'label' => 'Programação', 'template' => 'form-input-clean'))?>
                    <div class="alert alert-info">
                        <h4>Informação</h4>
                        <p>Insira os numeros dos remetentes semparados por ; (ponto e virgura). Ex.: 11999998888;21988889999;...</p>
                    </div>
                </div>
            </div>
        </div>
</div>