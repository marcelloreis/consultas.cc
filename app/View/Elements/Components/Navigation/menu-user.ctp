<div class="user">
	<ul class="icon-nav">
		<?php  //echo $this->element('Components/Navigation/messages')?>
		<?php  //echo $this->element('Components/Navigation/settings')?>
		<?php //echo $this->element('Components/Navigation/colors')?>
	</ul>
	<div class="dropdown">
    	<?php 
    	$avatar = is_file(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/webroot' . $userLogged['avatar_27'])?$userLogged['avatar_27']:'avatar-27x27.png';
    	?>

		<a href="#" class='dropdown-toggle' data-toggle="dropdown"><?php echo $userLogged['given_name'];?><?php echo $this->Html->image($avatar, array('width' => 27, 'heigth' => 27))?></a>
		<ul class="dropdown-menu pull-right">
			<li>
				<?php 
				echo $this->Html->link('Editar Perfil', array('controller' => 'users', 'action' => 'edit_profile', $userLogged['id'], 'plugin' => false));
				?>
			</li>
			<!-- <li>
				<a href="#">Account settings</a>
			</li> -->
			<li>
				<?php echo $this->Html->link('Sair', array('controller' => 'users', 'action' => 'logout', 'plugin' => false))?>
			</li>
		</ul>
	</div>
</div>