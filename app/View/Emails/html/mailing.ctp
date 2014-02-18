<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
	<tr>
		<td align="center">
			<center>
				<table border="0" width="600" cellpadding="0" cellspacing="0">
					<tr>
						<td style="color:#333333 !important; font-size:20px; font-family: Arial, Verdana, sans-serif; padding-left:10px;" height="40">
							<h3 style="font-weight:normal; margin: 20px 0;">A campanha <?php echo $campaign['Campaign']['title']?> está disponível para download.</h3>
							<p style="font-size:12px; line-height:18px;">
								Prezado(a) <?php echo $user['User']['given_name']?>,
							</p>

							<p style="font-size:12px; line-height:18px;">
								A campanha que você solicitou em <?php echo $campaign['Campaign']['created']?>, com o título 
								<strong><?php echo $campaign['Campaign']['title']?></strong>, já está pronta e disponível para download. 
							</p>

							<p style="font-size:12px; line-height:18px;">
								Para obter os arquivos gerados para esta campanha, clique aqui: 
								http://help.securepaynet.net/article/3678?prog_id=itechdev&isc=wwbb1781 
							</p>
							<p style="font-size:12px; line-height:18px;">
								Uma vez que o download estiver concluído, descompacte o arquivo com o WinRar (R) 
								ou qualquer outro utilitário que suporta a compressão ZIP. 
							</p>
							
							<p style="font-size:12px; line-height:18px;">
								Se você tiver alguma dúvida, não hesite em contactar o nosso 
								pessoal de apoio pelo telefone <?php echo COMPANY_PHONE?> ou pelo email <?php echo COMPANY_EMAIL_BUSINESSES?>
							</p>

							<h6>Sucesso em sua campanha!</h6>
							<br>
							<small>Atenciosamente,</small>
							<h4>Check List</h4>
						</td>
					</tr>
				</table>
			</center>
		</td>
	</tr>
</table>