<!-- header -->
<header id="header" class="navbar">
	<!-- Informacoes do usuario -->
	<?php echo $this->element('user-info')?>

    <?php echo $this->Html->link($this->Html->image('bigw.svg', array('width' => '70')), array('controller' => 'users', 'action' => 'dashboard', 'plugin' => false), array('class' => 'navbar-brand', 'id' => 'logo', 'escape' => false))?>

    <button type="button" class="btn btn-link pull-left nav-toggle visible-xs" data-toggle="class:slide-nav slide-nav-left" data-target="body">
        <i class="icon-reorder icon-xlarge text-default"></i>
    </button>

    <ul class="nav navbar-nav hidden-xs">
        <!-- Notificarions -->
        <li>
            <?php echo $this->element('Navbar/notifications')?>
        </li>

        <!-- Menu -->
        <li class="dropdown shift" data-toggle="shift:appendTo" data-target=".nav-primary .nav">
            <?php echo $this->element('Navbar/menu')?>
        </li>
    </ul>
    
    <form class="navbar-form pull-left shift" action="" data-toggle="shift:appendTo" data-target=".nav-primary">
        <i class="icon-search text-muted"></i>
        <input type="text" class="input-sm form-control col-lg-4" placeholder="Buscar">
    </form>
</header>
<!-- / header -->

<!--painel oculto:-->
<div class="inside-panel" id="menu-panel">
	<div id="content" class="container">
		<section class="main">
			<div class="row">
				<!--itens menu:-->
				<h3>Escolha itens para seu menu de favoritos</h3>
				<div class="col-lg-12">
				          <section class="toolbar clearfix m-t-large m-b">
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-group"></i>Clientes</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-shopping-cart"></i>Pedidos</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-glass"></i>Produtos</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-envelope-alt"></i>Newsletter</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-building"></i>B2B</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-phone"></i>Ramais</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-group"></i>Clientes</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-shopping-cart"></i>Pedidos</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-glass"></i>Produtos</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-envelope-alt"></i>Newsletter</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-building"></i>B2B</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-phone"></i>Ramais</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-building"></i>B2B</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-phone"></i>Ramais</a>
				            <a class="btn btn-inverse btn-circle" href="#" data-toggle="button"><i class="icon-building"></i>B2B</a>
				            
				          </section>
				</div>
				<!--:itens menu-->
			</div>
		</section>
	</div>
</div>
<!--:painel oculto-->

<!-- Top Content -->
<?php echo $this->element('Layouts/top-content')?>

<!-- Top view -->
<?php echo $this->element('Layouts/top-view')?>
