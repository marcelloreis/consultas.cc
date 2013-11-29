<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/datepicker/datepicker'));
echo $this->Html->css(array('plugins/icheck/all'));
$this->end();

echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'), array('defer' => true));
echo $this->Html->script(array('plugins/maskedinput/jquery.maskMoney.min'), array('defer' => true));
echo $this->Html->script(array('plugins/datepicker/bootstrap-datepicker'), array('defer' => true));
echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'), array('defer' => true));
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
            </div>            
            <?php echo $this->AppForm->input('client_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('value')?>
            <?php echo $this->AppForm->input('value_exceeded')?>
            <?php echo $this->AppForm->input('qt_exceeded')?>
            <?php echo $this->AppForm->input('bank_accounts')?>
            <?php echo $this->AppForm->input('tax')?>
            <?php echo $this->AppForm->input('payment')?>
            <?php echo $this->AppForm->input('maturity')?>
            <?php echo $this->AppForm->input('competence')?>
            <?php echo $this->AppForm->input('is_paid', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'))?>
            <?php echo $this->AppForm->input('is_separete', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'))?>
            <?php echo $this->AppForm->btn('Save changes');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
