<div class="wrapper">
  <div class="login-body">
    <h2>Defina sua nova senha</h2>
    <?php if(isset($validToken) && $validToken):?>
      <?php echo $this->AppForm->create('User', array('class' => 'panel-body')) ?>
        <?php echo $this->Session->flash(FLASH_SESSION_LOGIN)?>
        <?php echo $this->Form->hidden('change_pass_token', array('value' => $this->params['named']['change_pass_token'])) ?>

        <?php echo $this->AppForm->input('password', array('template' => 'form-input-login', 'div_class' => 'pw', 'class' => 'input-block-level', 'placeholder' => 'Digite a nova senha', 'tabindex' => "1", 'data-rule-required' => 'true')) ?>
        <?php echo $this->AppForm->input('password_confirm', array('type' => 'password', 'template' => 'form-input-login', 'div_class' => 'pw', 'class' => 'input-block-level', 'placeholder' => 'Confirme a nova senha', 'tabindex' => "1", 'data-rule-required' => 'true')) ?>

        <div class="submit">
          <?php echo $this->AppForm->btn('Salvar', array('template' => 'Users/form-input-btn-forgot_pass'))?>
        </div>
      <?php echo $this->AppForm->end(); ?>
      <?php else:?>
        <?php echo $this->AppForm->create('User', array('url' => '/users/forgot_pass', 'class' => 'panel-body')) ?>
          <h4>A sua solicitação já foi expirada, ou o token não é valido. Favor solicitar novamente através do link abaixo.</h4>
          <div class="submit">
            <?php echo $this->AppForm->btn('Clique aqui para redefinir sua senha.', array('template' => 'Users/form-input-btn-forgot_pass'))?>
          </div>
        <?php echo $this->AppForm->end(); ?>
    <?php endif?>
  </div>
</div>