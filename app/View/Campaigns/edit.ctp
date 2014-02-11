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

$neighbors_disabled = '';
$neighbors_value = !empty($this->request->data['Campaigns']['neighbors'])?$this->request->data['Campaigns']['neighbors']:'';
if(empty($this->request->data['Campaigns']['neighbors']) && empty($this->request->data['Campaigns']['city_id'])){
    $neighbors_disabled = 'disabled';
    $neighbors_value = 'Selecione a cidade, depois informe os bairros.';
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
                <?php echo $this->Form->hidden('ignore_age_null', array('value' => 0))?>
            </div> 
            <?php 
                if(!empty($this->request->data['Campaigns']['id'])){
                    echo $this->element('Edit/Campaigns/summary');
                }            
                echo $this->AppForm->input('title', array('class' => 'input-block-level'));
                echo $this->AppForm->input('template', array('label' => 'Modelo', 'template' => 'Campaigns/form-input-templates', 'class' => 'input-block-level msk-max', 'maxlenth' => 140, 'class-label' => 'msk-max-label'));
                echo $this->AppForm->input('contacts', array('label' => 'Lista de Contatos', 'template' => 'Campaigns/form-input-contacts', 'type' => 'textarea', 'class' => 'input-block-level'));
                echo $this->AppForm->input('neighbors', array('label' => 'Localização', 'disabled' => $neighbors_disabled, 'placeholder' => 'Bairros', 'value' => $neighbors_value, 'template' => 'Campaigns/form-input-location', 'class' => 'input-block-level'));
                echo $this->AppForm->input('zipcodes', array('label' => 'CEPs', 'template' => 'Campaigns/form-input-zipcodes', 'class' => 'input-block-level'));
                echo $this->AppForm->input('gender', array('type' => 'select', 'empty' => 'Feminino e Masculino', 'class' => 'chosen-select', 'label' => 'Sexo'));
                echo $this->AppForm->input('type', array('type' => 'select', 'empty' => 'Física e Jurídica', 'options' => array(TP_CPF => 'Física', TP_CNPJ => 'Jurídica'), 'class' => 'chosen-select', 'label' => 'Pessoa'));
                echo $this->AppForm->input('age', array('label' => 'Faixa Etária', 'template' => 'Campaigns/form-input-age'));
                echo $this->AppForm->input('limit', array('label' => 'Limite de registros', 'template' => 'Campaigns/form-input-limit'));
            ?>

            <?php echo $this->AppForm->btn('Salvar Alterações');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
