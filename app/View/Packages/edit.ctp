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
echo $this->Html->script(array('plugins/maskedinput/jquery.maskMoney.min'));
echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'));
$this->end();
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
       
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <?php echo $this->Form->input('id')?>
            <?php echo $this->AppForm->input('name')?>
            <?php echo $this->AppForm->input('price')?>
            <?php echo $this->AppForm->input('color')?>
            <?php echo $this->AppForm->input('validity_days')?>
            <?php echo $this->AppForm->input('popular', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'))?>

            <div class="control-group">
                <label class="control-label" style="padding:10px 0 10px 30px; width:auto;"><i class="icon-money"></i>&nbsp;<?php echo __('Prices')?></label>
            </div>

            <?php 
            if(isset($this->data['Product'])){
                foreach ($this->data['Product'] as $k => $v) {
                    echo $this->AppForm->input("Price.{$v['Price']['id']}.price", array('type' => 'text', 'label' => $v['name'], 'value' => $v['Price']['price']));
                    echo $this->Form->hidden("Price.{$v['Price']['id']}.product_id", array('value' => $v['id']));
                }
            }else{
                foreach ($products as $k => $v) {
                    echo $this->AppForm->input("Price.{$k}.price", array('type' => 'text', 'label' => $v));
                    echo $this->Form->hidden("Price.{$k}.product_id", array('value' => $k));
                }
            }

            ?>

            <?php echo $this->AppForm->btn('Save changes');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
