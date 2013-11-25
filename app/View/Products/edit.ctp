<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
$this->end();

$this->append('scrips-on-demand');
echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'));
echo $this->Html->script(array('plugins/maskedinput/jquery.maskMoney.min'));
$this->end();
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
            </div>            
            <?php echo $this->AppForm->input('aco_id', array('type' => 'select', 'empty' => __('Select'), 'options' => $acoPros, 'class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('name')?>
            <?php echo $this->AppForm->input('description')?>
            <?php echo $this->AppForm->input('price')?>
            <?php echo $this->AppForm->btn('Save changes');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
