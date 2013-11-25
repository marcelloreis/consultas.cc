<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/datepicker/datepicker'));
echo $this->Html->css(array('plugins/icheck/all'));
$this->end();

$this->append('scrips-on-demand');
echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'));
echo $this->Html->script(array('plugins/datepicker/bootstrap-datepicker'));
echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'));
$this->end();
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
            </div>            
            <?php echo $this->AppForm->input('package_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('state_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('city_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('cnpj', array('class' => 'msk-cnpj'))?>
            <?php echo $this->AppForm->input('fancy_name')?>
            <?php echo $this->AppForm->input('corporate_name')?>
            <?php echo $this->AppForm->input('contact_name')?>
            <?php echo $this->AppForm->input('email')?>
            <?php echo $this->AppForm->input('tel1', array('class' => 'msk-phone-ddd'))?>
            <?php echo $this->AppForm->input('tel2', array('class' => 'msk-phone-ddd'))?>
            <?php echo $this->AppForm->input('tel3', array('class' => 'msk-phone-ddd'))?>
            <?php echo $this->AppForm->input('address')?>
            <?php echo $this->AppForm->input('complement')?>
            <?php echo $this->AppForm->input('number')?>
            <?php echo $this->AppForm->input('neighborhood')?>
            <?php echo $this->AppForm->input('maturity_day')?>
            <?php echo $this->AppForm->input('limit_exceeded', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'))?>
            <?php echo $this->AppForm->input('repeat_limit_exceeded', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'))?>
            
            <?php echo $this->AppForm->btn('Save changes');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
