<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/icheck/all'));
$this->end();

echo $this->Html->script(array('plugins/maskedinput/jquery.maskMoney.min'), array('defer' => true));
echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'), array('defer' => true));
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
            <?php echo $this->AppForm->input('btn_payment')?>
            <?php echo $this->AppForm->input('popular', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'))?>

            <div class="control-group">
                <label class="control-label" style="padding:10px 0 10px 30px; width:auto;"><i class="icon-money"></i> Preços</label>
            </div>

            <?php 
            foreach ($products as $k => $v) {
                $label = isset($v['name'])?$v['name']:$v;
                $price = isset($v['price'])?$this->AppUtils->num2br($v['price']):'';
                echo $this->AppForm->input("Price.{$k}.price", array('type' => 'text', 'label' => $label, 'value' => $price));
                echo $this->Form->hidden("Price.{$k}.product_id", array('value' => $k));
            }
            ?>

            <?php echo $this->AppForm->btn('Salvar Alterações');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
