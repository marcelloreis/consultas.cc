<div class="user">
	<ul class="icon-nav">
		<?php // echo $this->element('Components/Navigation/messages')?>
		<?php // echo $this->element('Components/Navigation/settings')?>
		<?php echo $this->element('Components/Navigation/colors')?>
	</ul>
	<div class="dropdown">
		<?php $avatar = isset($userLogged['picture']) && !empty($userLogged['picture'])?$userLogged['picture']:'avatar.jpg';?>
		<a href="#" class='dropdown-toggle' data-toggle="dropdown"><?php echo !empty($userLogged['given_name'])?$userLogged['given_name']:$userLogged['name'];?><?php echo $this->Html->image($avatar, array('width' => 27, 'heigth' => 27))?></a>
		<ul class="dropdown-menu pull-right">
			<li>
				<?php echo $this->Html->link(__('Edit profile'), array('controller' => 'users', 'action' => 'edit', $userLogged['id'], 'plugin' => false))?>
			</li>
			<!-- <li>
				<a href="#">Account settings</a>
			</li> -->
			<li>
				<?php echo $this->Html->link(__('Sign out'), array('controller' => 'users', 'action' => 'logout', 'plugin' => false))?>
			</li>
		</ul>
	</div>
</div>