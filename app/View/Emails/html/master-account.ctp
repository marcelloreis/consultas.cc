<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
	<tr>
		<td align="center">
			<center>
				<table border="0" width="600" cellpadding="0" cellspacing="0">
					<tr>
						<td style="color:#333333 !important; font-size:20px; font-family: Arial, Verdana, sans-serif; padding-left:10px;" height="40">
							<h3 style="font-weight:normal; margin: 20px 0;">Parabéns, <?php echo $user['User']['given_name']?></h3>
							<p style="font-size:12px; line-height:18px;">
								Bem vindo ao <?php echo TITLE_APP?>, <br>
								falar sobre o sistema e seus produtos aqui.
							</p>

							<p style="font-size:15px; line-height:18px;">
								<strong>Seu login: <?php echo $user['User']['email']?></strong> <br>
								<strong>Sua Senha: <?php echo $user['User']['password']?></strong>
							</p>

							<p style="font-size:12px; line-height:18px;">
								Se você tiver alguma dúvida, não hesite em contactar o nosso 
								pessoal de apoio <br>
								pelo telefone <?php echo COMPANY_PHONE?> <br>
								ou pelo email <?php echo COMPANY_EMAIL_BUSINESSES?>
							</p>

							<h6>Sucesso em suas consultas!</h6>
							<br>
							<small>Atenciosamente,</small>
							<h4><?php echo TITLE_APP?></h4>
						</td>
					</tr>
				</table>
			</center>
		</td>
	</tr>
</table>