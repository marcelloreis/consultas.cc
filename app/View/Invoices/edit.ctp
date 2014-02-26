<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/icheck/all'));
echo $this->Html->css(array('plugins/datepicker/datepicker'));
$this->end();

echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'), array('defer' => true));
echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'), array('defer' => true));
echo $this->Html->script(array('plugins/datepicker/bootstrap-datepicker'), array('defer' => true));
echo $this->Html->script(array('plugins/maskedinput/jquery.maskMoney.min'), array('defer' => true));
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
                <?php echo $this->Form->hidden('token')?>
            </div>            

            <?php 
            echo $this->AppForm->input('client_id', array('class' => 'chosen-select'));
            echo $this->AppForm->input('package_id', array('class' => 'chosen-select'));
            echo $this->AppForm->input('maturity', array('label' => 'Vencimento'));
            echo $this->AppForm->input('value', array('class' => 'msk-money', 'label' => 'Valor do boleto'));
            if(!empty($this->data['Invoice']['id'])){
              echo $this->AppForm->input('value_exceeded', array('class' => 'msk-money', 'label' => 'Valor Excedido'));
              echo $this->AppForm->input('is_paid', array('label' => 'Pago', 'disabled' => 'disabled', 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'));
              echo $this->AppForm->input('is_separete', array('label' => 'Avulso', 'disabled' => 'disabled', 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'));
              echo $this->AppForm->input('is_signature', array('label' => 'Assinatura', 'disabled' => 'disabled', 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'));
              ?>
              <div class="control-group">
                  <label class="control-label" for="%id%">Nosso Número</label>
                  <div class="controls">
                      <?php echo $this->data['Invoice']['token']?>
                  </div>
              </div>
              <?php
            }
            echo $this->AppForm->btn('Salvar Alterações');
            ?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
