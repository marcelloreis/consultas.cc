<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
	<tr>
		<td align="center">
			<center>
				<table border="0" width="600" cellpadding="0" cellspacing="0">
					<tr>
						<td style="color:#333333 !important; font-size:20px; font-family: Arial, Verdana, sans-serif; padding-left:10px;" height="40">
							<h3 style="font-weight:normal; margin: 20px 0;">Lembrete de Senha</h3>
							<p style="font-size:12px; line-height:18px;">
								Prezado(a) <?php echo $user['User']['given_name']?>,
							</p>

							<p style="font-size:12px; line-height:18px;">
								Conforme sua solicitação, para alterar
								sua senha clique no botão abaixo.
							</p>
							<?php echo $this->Html->link('Alterar Minha Senha', array('controller' => 'users', 'action' => 'change_pass', 'change_pass_token' => $user['User']['change_pass_token'], 'full_base' => true))?>
						</td>
					</tr>
				</table>
			</center>
		</td>
	</tr>
</table>