<?php 
/**
* Insere o sidebar especifico de usuarios
*/
$this->start('sidebar');
echo $this->element('Components/Clients/sidebar');
$this->end();

/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
$this->end();

echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'), array('defer' => true));
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <?php echo $this->Form->input('id')?>
            <?php 
            if($isProspect){
                echo $this->Form->hidden('prospect_pkg_id', array('value' => $this->params['named']['prospect_pkg_id']));
            }
            ?>

            <?php echo $this->AppForm->input('contract_id', array('class' => 'chosen-select'))?>
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
            <?php echo $this->AppForm->input('zipcode', array('class' => 'msk-zipcode'))?>
            <?php echo $this->AppForm->input('street')?>
            <?php echo $this->AppForm->input('complement')?>
            <?php echo $this->AppForm->input('number')?>
            <?php echo $this->AppForm->input('neighborhood')?>
            
            <?php echo $this->AppForm->btn('Salvar Alterações');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
