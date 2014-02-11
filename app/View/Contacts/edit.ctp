<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
$this->end();

echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'), array('defer' => true));
echo $this->Html->script(array('plugins/maskedinput/jquery.maskMoney.min'), array('defer' => true));
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
                <?php echo $this->Form->hidden('client_id', array('value' => $userLogged['client_id']))?>
                <?php echo $this->Form->hidden('user_id', array('value' => $userLogged['id']))?>
            </div>            
            <?php echo $this->AppForm->input('title', array('class' => 'input-block-level'))?>
            <?php echo $this->AppForm->input('list', array('label' => 'Modelo', 'class' => 'input-block-level msk-max', 'maxlenth' => 165, 'class-label' => 'msk-max-label', 'template' => 'Contacts/form-input-contacts'))?>

            <?php echo $this->AppForm->btn('Salvar Alterações');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
