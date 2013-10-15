<div class="wrapper">
  <h1><a href="index.html"><?php echo $this->Html->image('logo-big.png', array('class' => 'retina-ready', 'width' => '59', 'height' => '49'))?>NASZA</a></h1>
  <div class="login-body">
    <h2>SIGN IN</h2>
    <?php echo $this->AppForm->create('User', array('class' => 'panel-body')) ?>
    <form action="index.html" method='get' class='form-validate' id="test">
      <?php echo $this->AppForm->input('email', array('template' => 'form-input-login', 'div_class' => 'email', 'class' => 'input-block-level', 'placeholder' => 'E-mail', 'tabindex' => "1", 'data-rule-required' => 'true', 'data-rule-email' => 'true')) ?>
      <?php echo $this->AppForm->input('password', array('template' => 'form-input-login', 'div_class' => 'pw', 'class' => 'input-block-level', 'placeholder' => __('Password'), 'tabindex' => "1", 'data-rule-required' => 'true')) ?>

      <div class="submit">
        <!-- <div class="remember">
          <input type="checkbox" name="remember" class='icheck-me' data-skin="square" data-color="blue" id="remember"> <label for="remember">Remember me</label>
        </div> -->
        <?php echo $this->AppForm->btn('Sign me in')?>
      </div>
    </form>
    <?php echo $this->AppForm->end(); ?>
    <div class="forget">
      <?php echo $this->Html->link('<i class="icon-google-plus"></i>&nbsp;<span>' . __('Sign in with Google?') . '</span>', array('controller' => 'users', 'action' => 'login', 'api' => 'google', 'plugin' => false), array('escape' => false))?>
    </div>
  </div>
</div>