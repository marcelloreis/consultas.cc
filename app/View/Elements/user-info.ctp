<?php if(isset($userLogged)):?>
	<?php $avatar = (isset($userLogged['picture']) && !empty($userLogged['picture']))?$userLogged['picture']:'avatar.jpg'?>
	<?php $url_profile = isset($userLogged['Social']['link'])?$userLogged['Social']['link']:'#';?>
    <ul class="nav navbar-nav navbar-avatar pull-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="hidden-xs-only"><?php echo $userLogged['given_name']?></span>
                <span class="thumb-small avatar inline">
                	<?php echo $this->Html->image($avatar, array('alt' => 'nome do wineano', 'class' => 'img-circle'))?>
                </span>
                <b class="caret hidden-xs-only"></b>
            </a>
            <ul class="dropdown-menu">
                <li>
                	<?php echo $this->Html->link('Perfil', $url_profile, array('target' => '_blank'))?>
                </li>
                <li>
                    <a href="#">Configurações</a>
                </li>
                <li>
                    <a href="#">
                        <span class="badge bg-danger pull-right">3</span>Notificações</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="docs.html">Ajuda</a>
                </li>
                <li>
                	<?php echo $this->Html->link('Sair', array('controller' => 'users', 'action' => 'logout', 'plugin' => false))?>
                </li>
            </ul>
        </li>
    </ul>
<?php endif?>