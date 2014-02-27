<?php 
/**
* Oculta o sidebar
*/
$this->assign('sidebar-class-hidden', 'nav-hidden');

/**
* Altera o titulo da view
*/
$this->start('title-view');
echo $this->element('Components/Clients/title-contract');
$this->end();

$body_width = ($this->params['action'] == 'contract')?'span8 offset2':'span12';
?>
<style type="text/css">
#navigation #brand {
	background: url("<?php echo PROJECT_LINK?>img/logo.png") no-repeat scroll 0 0 transparent !important;
    color: #FFFFFF !important;
    float: left !important;
    font-size: 15px !important;
    margin-top: 9px !important;
    padding-bottom: 2px !important;
    padding-left: 35px !important;
    padding-right: 69px !important;	
}
</style>

<div id="navigation">
	<div class="container-fluid">
		<span id="brand"><?php echo TITLE_APP?></span>
		<span class="toggle-nav">&nbsp;</span>
	</div>
</div>

<h5 class="text-right">Contrato nº.:<?php echo $this->AppUtils->num2qt($client['Client']['id'])?></h5>

<div class="row-fluid">
	<div class="<?php echo $body_width?>">
		<span class="page-first">
			<ol>
				<li>
					<blockquote>
						<p>INSTRUMENTO PARTICULAR DE CONTRATO</p>
						<p>DE CESSÃO DE USO E EXPLORAÇÃO DE</p>
						<p>DADOS CADASTRAIS,</p>
						<p>Que fazem entre si:</p>
					</blockquote>
					<div class="row-fluid">
						<div class="span10 offset2">
							<blockquote style="text-align:justify;" class="pull-right">
								<p style="text-align:justify;">
									De um lado como <strong>CONTRATADA</strong>: <strong><?php echo COMPANY_CORPORATE_NAME?></strong> utiliza como nome fantasia a denominação de <strong><?php echo COMPANY_FANCY_NAME?></strong>, sediada na <?php echo COMPANY_ADDRESS?>, 
									inscrita no CNPJ sob o n.º <?php echo COMPANY_CNPJ?>, inscrição municipal nº <?php echo COMPANY_INSCRIPTION?>. <br>
									E de outro lado, como <strong>ASSOCIADA</strong>, 
									<strong><?php echo $client['Client']['corporate_name']?></strong>, 
									sediado na Rua/Avenida <?php echo $client['Client']['street']?>, n.º <?php echo $client['Client']['number']?>, 
									<?php echo !empty($client['City']['name'])?$client['City']['name']:'';?>/<?php echo !empty($client['State']['uf'])?$client['State']['uf']:'';?> - CEP: <?php echo $this->AppUtils->zipcode($client['Client']['zipcode'])?>, 
									Inscrição Municipal n.º <?php echo $client['Client']['municipal_inscription']?>, 
									Inscrição Estadual n.º <?php echo $client['Client']['state_inscription']?>, 
									neste ato representado por seu sócio responsável, procurador ou pelo Diretor-Presidente, consoante Estatuto Social ou procuração ora apresentado e anexado à presente, 
									Sr.(a). <strong><?php echo $client['Client']['responsible_partner']?></strong>, inscrito no CPF sob o nº. <?php echo $this->AppUtils->cpf($client['Client']['responsible_partner_cpf'])?>,
									têm entre si justo e acordado o presente CONTRATO DE CESSÃO DE USO E EXPLORAÇÃO DE DADOS CADASTRAIS,  mediante as cláusulas e condições seguintes:
								</p>
							</blockquote>
						</div>
					</div>
				</li>
				<li>
					<blockquote>
						<p>Consultas e Serviços Cadastrados</p>
					</blockquote>
						<div class="row-fluid">
							<div class="span10 offset2">
								<blockquote class="pull-right">
									<div class="box">
										<div class="box-content nopadding">
											<table class="table table-hover table-nomargin table-condensed table-bordered">
												<thead>
													<tr>
														<th>Produtos/Serviços</th>
														<th>Franquia Mensal de Consultas</th>
														<th>Consulta Excedente</th>
														<th colspan="3" class="hidden-350">Valores do Contrato</th>
													</tr>
												</thead>
												<tbody>
													<?php $i=0?>
													<?php foreach($products as $k => $v):?>
														<tr>
															<td><?php echo $v?></td>

														<?php if(!$i):?>
															<td style="text-align:center;" rowspan="<?php echo count($products)?>"><?php echo $client['Package']['franchise']?></td>
														<?php endif?>

															<td><?php echo $prices[$k]?></td>
															
															<?php if(($i % 2) == 0 && $i < 6):?>
																<?php 
																switch ($i) {
																	case 0:
																		?>
																		<td rowspan="2" colspan="3" class="hidden-350">Valor Fixo Mensal: <strong>R$ <?php echo $client['Package']['price']?></strong></td>
																		<?php
																		break;
																	case 2:
																		?>
																		<td rowspan="2" colspan="3" class="hidden-350">Taxa Adesão: <strong>R$ <?php echo $client['Package']['signature']?></strong></td>
																		<?php
																		break;
																	case 4:
																		?>
																		<td rowspan="2" colspan="3" class="hidden-350">Vcto 1º Fatura: <strong><?php echo $client['Client']['first_invoice']?></strong></td>
																		<?php
																		break;
																}
																?>
															<?php endif?>
															
															<?php if($i == 6):?>
																<td rowspan="<?php echo count($products)-6?>" colspan="3" class="hidden-350">&nbsp;</td>
															<?php endif?>
															
														</tr>


														<?php $i++;?>
													<?php endforeach?>

												</tbody>
											</table>
										</div>
									</div>
								</blockquote>
							</div>
						</div>
				</li>
				<?php if(!empty($client['Client']['contractual_mode_specifies'])):?>
					<li>
						<blockquote>
							<p>Modalidade Contratual Específica</p>
						</blockquote>
							<div class="row-fluid">
								<div class="span10 offset2">
									<blockquote style="text-align:rigth;" class="pull-right">
										<p style="text-align:rigth;">
											<?php echo $client['Client']['contractual_mode_specifies']?>
										</p>
									</blockquote>
								</div>
							</div>
					</li>
				<?php endif?>
			</ol>
		</span>
		
		<span class="page">
			<dl>
				<dt>CLÁUSULA PRIMEIRA: DA LEGALIDADE E UTILIZACÃO</dt>
				<p>
					A <strong>CONTRATADA</strong>, reconhecida pelo Código de Defesa do Consumidor como entidade de caráter público, Lei 8078/90, sendo possuidora de um banco de dados destinado a identificação, localização, confirmação de dados de pessoas físicas e jurídicas e concessão de crédito a nível nacional, e demais outras informações inerentes ao pleno exercício das atividades de inteligência. As informações ora negociadas são de caráter confidencial para uso exclusivo da <strong>ASSOCIADA</strong>, não sendo permitida sua comercialização com terceiros.
				</p>
				<p>
					1.1 - As consultas identificadas, assinaladas e confirmadas conforme item 2 - CONSULTAS E SERVIÇOS CONTRATADOS (pag. 01) serão tarifadas de acordo com o estipulado na mesma clausula.
				</p>
				<dd>
					<strong>Parágrafo Primeiro:</strong><br>
					No caso de contratação de franquias diferenciadas, as mesmas serão determinadas e tarifadas conforme item 03 - MODALIDADE CONTRATUAL ESPECÍFICA.
				</dd>
				<dd>
					<strong>Parágrafo Segundo:</strong><br>
					A franquia de serviços contratados não é acumulativa aos meses posteriores.
				</dd>
				<dd>
					<strong>Parágrafo Terceiro:</strong><br>
					A <strong>ASSOCIADA</strong> responsabiliza-se, integralmente e com exclusividade, perante suas operações com terceiros, quanto à utilização das informações disponibilizadas pela <strong>CONTRATADA</strong>. Ficando ainda a <strong>ASSOCIADA</strong>, proibido à reproduzir, divulgar, vender ou divulgar através de qualquer meio as informações obtidas através do presente instrumento.
				</dd>
			</dl>

			<dl>
				<dt>CLÁUSULA SEGUNDA: DO ACESSO AOS SERVIÇOS</dt>
				<p>
					A <strong>ASSOCIADA</strong> terá acesso aos serviços da <strong>CONTRATADA</strong>, mediante código e senha exclusivos, no qual responsabiliza-se pelo resguardo das mesmas, podendo ser alterada quantas vezes necessário for, diretamente pela <strong>ASSOCIADA</strong> sem a interveniência do CONTRATADO, podendo ainda, determinar o IP (fixo), dias e horarios de acesso.
				</p>
			</dl>

			<dl>
				<dt>CLÁUSULA TERCEIRA: DO FATURAMENTO DOS SERVIÇOS</dt>
				<p>
					Mensalmente, a <strong>CONTRATADA</strong> apresentará no endereço da <strong>ASSOCIADA</strong> fatura de acordo com o contrato, cujo pagamento deverá ser efetuado até a data do vencimento. O atraso no pagamento acarretará juros de mora legais e acréscimo de <?php echo BANK_TAX?>% a título de multa contratual sobre o valor da fatura. As mensalidades serão reajustas automaticamente e anualmente - sempre no mês de dezembro - pelo índice IGP-M da FGV.
				</p>
			</dl>

			<dl>
				<dt>CLÁUSULA QUARTA: DO INADIMPLEMENTO</dt>
				<p>
					O Não pagamento das mensalidades e das obrigações contratuais acarretará no bloqueio automático do acesso ao banco de dados, poedendo ser restabelecido em até 72 horas após o pagamento. O fiador declara renunciar ao beneficio de ordem previsto em nosso ordenamento jurídico (art.827, parágrafo único do CC).
				</p>
			</dl>
		</span>

		<span class="page">
			<dl>
				<dt>CLÁUSULA QUINTA: DA TAXA DE IMPLANTACÃO</dt>
				<p>
					A <strong>ASSOCIADA</strong> pagará no ato da assinatura do contrato, uma importância previamente combinada que poderá variar de acordo com a região geográfica, porte ou ramo de atividade da <strong>ASSOCIADA</strong>, referente à implantação do sistema e despesas administrativas (esta importância não se refere ao pagamento de mensalidade), e não poderá a <strong>ASSOCIADA</strong> postular a devolução de referida importância após a afetiva contratação.
				</p>
			</dl>

			<dl>
				<dt>CLÁUSULA SEXTA: DA DISPONIBILIDADE</dt>
				<p>
					Os serviços ora contratados ficarão disponíveis 24 horas por dia incluindo sábados, domingos e feriados. A não utilização do sistema ou o não recebimento do título bancário antes do vencimento para pagamento, não isenta a <strong>ASSOCIADA</strong> da responsabilidade dos pagamentos das mensalidades.
				</p>
			</dl>

			<dl>
				<dt>CLÁUSULA SETIMA: DO PRAZO DO CONTRATO OU RESCISÃO</dt>
				<p>
					O presente contrato é realizado por prazo indeterminado, podendo ser rescindido por qualquer das partes contratantes a qualquer tempo, com aviso prévio de 30 (trinta) dias fora o mês do pedido, mediante simples correspondência registrada com aviso de recebimento de AR.
				</p>

				<p>
					7.1 - O presente contrato não poderá ser cancelado com mensalidades pendentes e não realizamos cancelamentos verbais ou através de e-mail ou fax. Nossos representantes não estão autorizados a efetuar cancelamentos, receber ou dispensar mensalidades.
				</p>

				<!-- <p>
					Na opção pelo cancelamento, todos os registros do cadastro POSITIVO, serão automaticamente cancelados em definitivo
				</p> -->
			</dl>

			<dl>
				<dt>CLÁUSULA OITAVA: DOS PAGAMENTOS</dt>
				<p>
					Todo e qualquer pagamento somente será reconhecido pela <strong>CONTRATADA</strong>, se for realizado diretamente a mesma. Por intermédio de BOLETO BANCÁRIO ou DEPÓSITO na conta corrente da <strong><?php echo COMPANY_CORPORATE_NAME?></strong>.
				</p>

				<!-- <p>
					E, por estarem justas e contratadas, firmam o presente contrato em duas vias de igual teor e forma, elegendo o foro da cidade de Vitória/ES, como competente para dirimir quaisquer dúvidas oriundas do presente contrato, com expressa renúncia de qualquer outro, por mais privilegiado que seja.
				</p> -->
			</dl>
		</span>

		<span class="page">
			<dl>
				<dt>DISPOSIÇÕES GERAIS:</dt>
				<dd>
					A <strong>ASSOCIADA</strong> fica obrigada a manter seu cadastro sempre atualizado junto a <strong>CONTRATADA</strong>, no que tange a empresas participantes do Grupo, alteração de razão social, substituição de sócio gerente, etc. sempre no intuito de manter a segurança e sigilo dos serviços prestados pela <strong>CONTRATADA</strong>.
					Os futuros serviços disponibilizados pela <strong>CONTRATADA</strong>, poderão ser utilizados imediatamente pelo <strong>ASSOCIADA</strong>, sendo certo que sempre haverá uma tabela de valores para cada serviço que deverá ser assinada e anexada ao contrato.
					Os casos omissos serão solucionados pelo consenso das partes contratantes e na conformidade da legislação que for aplicável.
					Fica eleito o foro da Comarca da Capital do Estado do(e) <?php echo COMPANY_STATE?> para a solução de todas as questões oriundas do presente contrato, com renúncia a qualquer outro.
					E por assim se acharem justos e acordados, assinam o presente em 02 (duas) vias, de igual teor e forma, na presença de 02 (duas) testemunhas devidamente identificadas, que também o subscrevem, com o reconhecimento em cartório de todas essas assinaturas, para que produza o seu legal e devido efeito.
					<br>
					<br>
					<h5><?php echo COMPANY_STATE?>, <?php echo $client['Client']['contract_ini_day']?> de <?php echo $client['Client']['contract_ini_month']?> de <?php echo $client['Client']['contract_ini_year']?>.</h5>
				</dd>
			</dl>

			<div style="margin-top: 100px;" class="row-fluid">
				<div class="span8 offset2 text-center">
					<hr style="color:#ccc;background-color:#000;height:1px;">
					<?php echo COMPANY_FANCY_NAME?> 
					<?php echo COMPANY_CORPORATE_NAME?> 
				</div>
			</div>

			<div style="margin-top: 50px;" class="row-fluid">
				<div class="span8 offset2 text-center">
					<hr style="color:#ccc;background-color:#000;height:1px;">
					<?php echo $client['Client']['corporate_name']?>
				</div>
			</div>

			<div style="margin-top: 50px;" class="row-fluid">
				<div class="span8 offset2 text-center">
					<hr style="color:#ccc;background-color:#000;height:1px;">
					Nome: <?php echo COMPANY_PARTNER_2?><br>
					CPF: <?php echo COMPANY_PARTNER_2_CPF?><br>
				</div>
			</div>

			<div style="margin-top: 50px;" class="row-fluid">
				<div class="span8 offset2 text-center">
					<hr style="color:#ccc;background-color:#000;height:1px;">
					Nome: <?php echo $client['Client']['responsible_partner']?><br>
					CPF: <?php echo $this->AppUtils->cpf($client['Client']['responsible_partner_cpf'])?><br>
				</div>
			</div>
		</span>
	</div>
</div>