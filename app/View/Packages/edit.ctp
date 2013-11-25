<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/multiselect/multi-select'));
$this->end();

$this->append('scrips-on-demand');
echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'));
echo $this->Html->script(array('plugins/maskedinput/jquery.maskMoney.min'));
echo $this->Html->script(array('plugins/multiselect/jquery.multi-select'));
$this->end();
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
       
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
                <?php echo $this->Form->input('Product.Product.0', array('value' => null))?>
            </div>            
            <?php echo $this->AppForm->input('group_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('name')?>
            <?php echo $this->AppForm->input('postage', array('class' => 'msk-int'))?>
            <?php echo $this->AppForm->input('value', array('disabled' => 'disabled'))?>
            <?php echo $this->AppForm->input('value_per_exceeded', array('disabled' => 'disabled'))?>
            <?php echo $this->AppForm->input('validity')?>
            <?php echo $this->AppForm->input('Product', array('type' => 'select', 'options' => $products, 'selected' => $products_active, 'multiple' => 'multiple', 'class' => 'multiselect', 'data-selectionheader' => __('Added Products'), 'data-selectableheader' => __('Products Available')))?>

            <?php echo $this->AppForm->btn('Save changes');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
