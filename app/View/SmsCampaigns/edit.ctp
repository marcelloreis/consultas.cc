<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/icheck/all'));
$this->end();

echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'), array('defer' => true));
echo $this->Html->script(array('plugins/maskedinput/jquery.maskMoney.min'), array('defer' => true));
echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'), array('defer' => true));

if(!empty($this->request->data['SmsCampaign']['id'])){
    echo $this->element('Edit/SmsCampaigns/actions', array('id' => $this->request->data['SmsCampaign']['id'], 'status' => $this->request->data['SmsCampaign']['status']));
}
?>


<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
                <?php echo $this->Form->hidden('client_id', array('value' => $userLogged['client_id']))?>
                <?php echo $this->Form->hidden('user_id', array('value' => $userLogged['id']))?>
                <?php echo $this->Form->hidden('people', array('value' => 0))?>
                <?php echo $this->Form->hidden('female', array('value' => 0))?>
                <?php echo $this->Form->hidden('male', array('value' => 0))?>
                <?php echo $this->Form->hidden('individual', array('value' => 0))?>
                <?php echo $this->Form->hidden('corporation', array('value' => 0))?>
                <?php echo $this->Form->hidden('status', array('value' => 0))?>
            </div> 
            <?php 
                if(!empty($this->request->data['SmsCampaign']['id'])){
                    echo $this->element('Edit/SmsCampaigns/summary');
                }            
                echo $this->AppForm->input('title', array('class' => 'input-block-level'));
                echo $this->AppForm->input('template', array('label' => 'Modelo', 'template' => 'SmsCampaigns/form-input-templates', 'class' => 'input-block-level msk-max', 'maxlenth' => 140, 'class-label' => 'msk-max-label'));
                echo $this->AppForm->input('contact_list', array('label' => 'Lista de Contatos', 'template' => 'SmsCampaigns/form-input-contact_list', 'type' => 'textarea', 'class' => 'input-block-level'));
                echo $this->AppForm->input('areas', array('label' => 'Áreas', 'template' => 'SmsCampaigns/form-input-areas', 'class' => 'input-block-level'));
                echo $this->AppForm->input('gender', array('type' => 'select', 'empty' => 'Feminino e Masculino', 'class' => 'chosen-select', 'label' => 'Sexo'));
                echo $this->AppForm->input('type', array('type' => 'select', 'empty' => 'Física e Jurídica', 'options' => array(TP_CPF => 'Física', TP_CNPJ => 'Jurídica'), 'class' => 'chosen-select', 'label' => 'Pessoa'));
                echo $this->AppForm->input('age', array('label' => 'Faixa Etária', 'template' => 'SmsCampaigns/form-input-age'));
                echo $this->AppForm->input('limit', array('label' => 'Limite de envios'));
                // echo $this->AppForm->input('status', array('type' => 'select', 'class' => 'chosen-select', 'empty' => 'Ativar/Desativar', 'options' => array(1 => 'Ativo', 2 => 'Inativo')));
                // echo $this->AppForm->input('schedule', array('label' => 'Programação', 'template' => 'SmsCampaigns/form-input-schedule', 'type' => 'textarea', 'class' => 'input-block-level'));
            ?>

            <?php echo $this->AppForm->btn('Salvar Alterações');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
