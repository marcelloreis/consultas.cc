<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/icheck/all'));
$this->end();

echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'), array('defer' => true));
echo $this->Html->script(array('plugins/datepicker/bootstrap-datepicker'), array('defer' => true));
echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'), array('defer' => true));
echo $this->Html->script(array('plugins/maskedinput/jquery.maskMoney.min'), array('defer' => true));
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xxlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <?php echo $this->Form->input('id')?>
            <?php echo $this->Form->hidden('user_id', array('value' => $this->Session->read('Auth.User.id')))?>

            <?php echo $this->AppForm->input('package_id', array('class' => 'chosen-select', 'label' => 'Pacote'))?>
            <?php echo $this->AppForm->input('natures_legal_id', array('class' => 'chosen-select', 'label' => 'Natureza Jurídica'))?>
            <?php echo $this->AppForm->input('fancy_name')?>
            <?php echo $this->AppForm->input('corporate_name')?>
            <?php echo $this->AppForm->input('contact_name')?>
            <?php echo $this->AppForm->input('activity', array('label' => 'Atividade'))?>
            <?php echo $this->AppForm->input('cnpj', array('class' => 'msk-cnpj'))?>
            <?php echo $this->AppForm->input('email')?>
            <?php echo $this->AppForm->input('tel1', array('class' => 'msk-phone-ddd'))?>
            <?php echo $this->AppForm->input('tel2', array('class' => 'msk-phone-ddd'))?>
            <?php echo $this->AppForm->input('state_id', array('class' => 'chosen-select city-by-state'))?>
            <?php echo $this->AppForm->input('city_id', array('class' => 'chosen-select city-by-state', 'data-model' => $modelClass))?>
            <?php echo $this->AppForm->input('street')?>
            <?php echo $this->AppForm->input('zipcode', array('class' => 'msk-zipcode'))?>
            <?php echo $this->AppForm->input('neighborhood')?>
            <?php echo $this->AppForm->input('complement')?>
            <?php echo $this->AppForm->input('number')?>
            <?php echo $this->AppForm->input('municipal_inscription')?>
            <?php echo $this->AppForm->input('state_inscription')?>
            <?php echo $this->AppForm->input('responsible_partner')?>
            <?php echo $this->AppForm->input('responsible_partner_cpf', array('class' => 'msk-cpf'))?>
            <?php echo $this->AppForm->input('contract_ini', array('label' => 'Data do Contrato'))?>

            <?php echo $this->AppForm->input('guarantor', array('label' => 'Nome do fiador'))?>
            <?php echo $this->AppForm->input('guarantor_role', array('label' => 'Cargo do fiador'))?>
            <?php echo $this->AppForm->input('guarantor_tel1', array('label' => 'Telefone residencial do fiador', 'class' => 'msk-phone-ddd'))?>
            <?php echo $this->AppForm->input('guarantor_tel2', array('label' => 'Telefone móvel do fiador', 'class' => 'msk-phone-ddd'))?>
            <?php echo $this->AppForm->input('contractual_mode_specifies', array('label' => 'Modalidade Contratual Específica'))?>
            <?php echo $this->AppForm->input('maturity_day', array('label' => 'Melhor dia para pagamento'))?>
            <?php echo $this->AppForm->input('first_invoice', array('label' => 'Vencimento da 1º fatura'))?>
            <?php echo $this->AppForm->input('fixed_monthly', array('label' => 'Valor fixo por mês', 'class' => 'msk-money'))?>

            <?php echo $this->AppForm->input('limit_exceeded', array('label' => 'Exceder limite este mês', 'type' => 'checkbox', 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'))?>

            
            <?php echo $this->AppForm->btn('Salvar Alterações');?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
