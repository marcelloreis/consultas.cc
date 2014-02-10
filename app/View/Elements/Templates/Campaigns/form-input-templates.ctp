<div class="control-group">
    <label for="%id%" class="control-label">%label%</label>
        <div class="controls">
            <div class="row-fluid">
                <div class="span8">
                    <h4><span class="msk-max-label">140</span> Caracteres restantes</h4>
                    %input%
                </div>
                <div class="span4">
                    <?php echo $this->AppForm->input('sms_template_id', array('empty' => 'Modelos Cadastrados', 'options' => $sms_templates, 'class' => 'chosen-select', 'label' => 'Modelo', 'template' => 'form-input-clean'))?>
                    <div class="alert alert-info">
                        <h4>Códigos para a personalização da mensagem.</h4>
                        <p>
                            <br/>
                            %nome% = Nome do destinatário<br/>
                            %nome_com% = Nome Completo do destinatário<br/>
                            %sexo% = Sexo do destinatário (Masculino/Feminino)<br/>
                            %idade% = Idade do destinatário<br/>
                            %aniversario% = Data de aniversário do destinatário
                        </p>
                    </div>

                </div>
            </div>
        </div>
</div>