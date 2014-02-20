<?php 
/**
* Insere o sidebar especifico de usuarios
*/
$this->start('sidebar');
echo $this->element('Components/Campaigns/sidebar-mailing');
$this->end();

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
echo $this->Html->script(array('plugins/fileupload/bootstrap-fileupload.min'), array('defer' => true));
echo $this->Html->script(array('plugins/mockjax/jquery.mockjax'), array('defer' => true));

/**
* Carrega o estado do processamento do mailing
*/
$process_state = !empty($this->data['Campaign']['process_state'])?$this->data['Campaign']['process_state']:CAMPAIGN_NOT_PROCESSED;

/**
* Habilita/Desabilita o campo de bairros
*/
$neighbors_disabled = '';
$neighbors_value = !empty($this->request->data['Campaign']['neighbors'])?$this->request->data['Campaign']['neighbors']:'';
if(empty($this->request->data['Campaign']['neighbors']) && empty($this->request->data['Campaign']['city_id'])){
    $neighbors_disabled = 'disabled';
    $neighbors_value = 'Selecione a cidade, depois informe os bairros.';
}

/**
* Carrega o preco de cada registro do mailing de a cordo com o pacote do cliente
*/
$package_id = $this->Session->read('Client.package_id');
$price = $this->Session->read("Billing.prices_val.{$package_id}." . PRODUCT_MAILING);

/**
* Carrega o elemento com as mensagens do estado do processamento da campanha
*/
echo $this->element('Edit/Campaigns/process_state', array('process_state' => $process_state));
?>



<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped', 'type' => 'file'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
                <?php echo $this->Form->hidden('ignore_age_null', array('value' => 0))?>
                <?php echo $this->Form->hidden('limit_max', array('value' => floor($balance/$this->AppUtils->num2db($price))))?>
            </div> 
            <?php 
                if(!empty($this->request->data['Campaigns']['id'])){
                    echo $this->element('Edit/Campaigns/summary');
                }            
                echo $this->AppForm->input('title', array('class' => 'input-block-level'));
                echo $this->AppForm->input('limit', array('label' => 'Limite de registros', 'template' => 'Campaigns/form-input-limit'));
                echo $this->AppForm->input('source', array('type' => 'file', 'template' => 'Campaigns/form-input-source'));
                echo $this->AppForm->input('tel_type', array('type' => 'select', 'options' => $tel_type, 'class' => 'chosen-select', 'label' => 'Telefones'));
                echo $this->AppForm->input('layout', array('label' => 'Campos', 'template' => 'Campaigns/form-input-layout'));
            ?>

            <?php echo $this->AppForm->btn('Salvar Alterações');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>




























