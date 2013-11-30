<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/datepicker/datepicker'));
$this->end();

echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'), array('defer' => true));
echo $this->Html->script(array('plugins/datepicker/bootstrap-datepicker'), array('defer' => true));
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <?php echo $this->Form->input('id')?>
            <?php echo $this->Form->hidden('user_id', array('value' => $this->Session->read('Auth.User.id')))?>

            <?php echo $this->AppForm->input('client_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('municipal_inscription')?>
            <?php echo $this->AppForm->input('state_inscription')?>
            <?php echo $this->AppForm->input('responsible_partner')?>
            <?php echo $this->AppForm->input('responsible_partner_cpf', array('class' => 'msk-cpf'))?>
            <?php echo $this->AppForm->input('responsible_partner_id', array('type' => 'text'))?>
            <?php echo $this->AppForm->input('responsible_partner_id_issued')?>
            <?php echo $this->AppForm->input('responsible_partner_id_issued_date', array('type' => 'text', 'class' => 'datepick'))?>
            <?php echo $this->AppForm->input('responsible_partner2')?>
            <?php echo $this->AppForm->input('responsible_partner_cpf2', array('class' => 'msk-cpf'))?>
            <?php echo $this->AppForm->input('responsible_partner_id2', array('type' => 'text'))?>
            <?php echo $this->AppForm->input('responsible_partner_id_issued2')?>
            <?php echo $this->AppForm->input('responsible_partner_id_issued_date2', array('type' => 'text', 'class' => 'datepick'))?>
            <?php echo $this->AppForm->input('contract_ini')?>
            <?php echo $this->AppForm->input('validity', array('label' => 'Validade (meses)', 'type' => 'text', 'class' => 'msk-int'))?>
            
            <?php echo $this->AppForm->btn('Save changes');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>