
<!DOCTYPE html><html lang="pt-br">
    <head>
        <title>Check List</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link href="/img/favicon.png" type="image/x-icon" rel="icon" /><link href="/img/favicon.png" type="image/x-icon" rel="shortcut icon" /><meta name="base_url" content="http://loc-consultas.cc/" /><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><meta name="apple-mobile-web-app-capable" content="yes" /><meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" /><meta name="description" content="" /><meta name="keywords" content="" /><meta name="author" content="" /><meta name="robots" content="index,follow" /><meta name="content-language" content="pt-br" />
	<link rel="stylesheet" type="text/css" href="/css/all.min.css" />

	<script type="text/javascript" src="/js/all.min.js" defer="defer"></script>
<!--[if lte IE 9]>
    <script type="text/javascript" src="/js/plugins/placeholder/jquery.placeholder.min.js"></script>    <script>
        $(document).ready(function() {
            $('input, textarea').placeholder();
        });
    </script>
<![endif]-->
    </head>
    <body class='login theme-satblue'><div class="wrapper">
  <div class="login-body">
    <h2>Entrar</h2>
    <form action="/users/login" class="panel-body" id="UserLoginForm" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>          <div class="control-group">
	<div class="email controls">
		<input name="data[User][email]" template="form-input-login" div_class="email" class="input-block-level" placeholder="E-mail" tabindex="1" data-rule-required="true" data-rule-email="true" x-moz-errormessage="Este campo deve ser preenchido corretamente." title="Este campo deve ser preenchido corretamente." maxlength="255" type="email" id="UserEmail" required="required"/>
	</div>
</div>      <div class="control-group">
	<div class="pw controls">
		<input name="data[User][password]" template="form-input-login" div_class="pw" class="input-block-level" placeholder="Senha" tabindex="1" data-rule-required="true" type="password" id="UserPassword"/>
	</div>
</div>
      <div class="submit">
        <div class="form-actions">
    <button type="submit" class="btn btn-primary">Logar</button>
	<a href="/users/forgot_pass" class="btn">Esqueci Minha Senha</a></div>      </div>
    <input  type="hidden" value="Submit"/></form>  </div>
</div>	</body>
	</html>