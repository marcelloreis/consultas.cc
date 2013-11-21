<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/icheck/all'));
$this->end();

$this->append('scrips-on-demand');
echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'));
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
            <?php echo $this->AppForm->input('user_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('product_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('package_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('invoice_id', array('class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('tp_search', array('type' => 'select', 'options' => $tp_search, 'class' => 'chosen-select'))?>
            <?php echo $this->AppForm->input('found', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'))?>
            <?php echo $this->AppForm->input('value_searched')?>
            <?php echo $this->AppForm->btn('Save changes');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
