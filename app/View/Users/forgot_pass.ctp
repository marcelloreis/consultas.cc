<div class="wrapper">
  <div class="login-body">
    <h2>Esqueci minha senha</h2>
    <?php echo $this->AppForm->create('User', array('class' => 'panel-body')) ?>
    <?php echo $this->Session->flash(FLASH_SESSION_LOGIN)?>
      <?php echo $this->AppForm->input('email', array('template' => 'form-input-login', 'div_class' => 'email', 'class' => 'input-block-level', 'placeholder' => 'E-mail', 'tabindex' => "1", 'data-rule-required' => 'true', 'data-rule-email' => 'true')) ?>

      <div class="submit">
        <?php echo $this->AppForm->btn('Continuar', array('template' => 'Users/form-input-btn-forgot_pass'))?>
      </div>
    <?php echo $this->AppForm->end(); ?>
  </div>
</div>