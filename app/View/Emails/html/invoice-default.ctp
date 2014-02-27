<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
	<tr>
		<td align="center">
			<center>
				<table border="0" width="600" cellpadding="0" cellspacing="0">
					<tr>
						<td style="color:#333333 !important; font-size:20px; font-family: Arial, Verdana, sans-serif; padding-left:10px;" height="40">
							<h3 style="font-weight:normal; margin: 20px 0;">Instruções para pagamento do boleto.</h3>
							<p style="font-size:12px; line-height:18px;">
								Prezado(a) <?php echo $client['Client']['contact_name']?>,
							</p>

							<p style="font-size:12px; line-height:18px;">
								Para imprimir o boleto acesse o endereço abaixo:<br>
								<?php echo $link?><br>
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