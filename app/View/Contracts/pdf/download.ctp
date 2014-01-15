<?php 
$responsible_partner_cpf = $this->AppUtils->cpf($contract['Contract']['responsible_partner_cpf']);
$responsible_partner_cpf2 = $this->AppUtils->cpf($contract['Contract']['responsible_partner_cpf2']);
?>
<div id="navigation">
	<div class="container-fluid">
		<?php 
		echo $this->Html->link(TITLE_APP, array('controller' => 'users', 'action' => 'dashboard', 'plugin' => false), array('id' => 'brand', 'escape' => false));
			echo $this->Html->link('&nbsp;', '#', array('class' => 'toggle-nav', 'escape' => false));
		?>
	</div>
</div>

<h5 class="text-right">Contrato nº.:<?php echo $this->AppUtils->num2qt($contract['Contract']['id'])?></h5>

<div class="row-fluid">
	<div class="span8 offset2">
		<blockquote>
			<p>INSTRUMENTO PARTICULAR DE CONTRATO</p>
			<p>DE CESSÃO DE USO E EXPLORAÇÃO DE</p>
			<p>DADOS CADASTRAIS,</p>
			<p>Que fazem entre si:</p>
		</blockquote>
		<div class="row-fluid">
			<div class="span2"></div>
			<div class="span10">
				<blockquote style="text-align:justify;" class="pull-right">
					<p style="text-align:justify;">
						De um lado como <strong>CEDENTE</strong>: <strong><?php echo COMPANY_CORPORATE_NAME?></strong> utiliza como nome fantasia a denominação de <strong><?php echo COMPANY_FANCY_NAME?></strong>, sediada na <?php echo COMPANY_ADDRESS?>, 
						inscrita no CNPJ sob o n.º <?php echo COMPANY_CNPJ?>, inscrição municipal nº <?php echo COMPANY_INSCRIPTION?>, 
						neste ato representado por seus sócios-gerentes <strong><?php echo COMPANY_PARTNER_1?></strong> e <strong><?php echo COMPANY_PARTNER_2?></strong>, 
						portadores das carteiras de identidades nºs <?php echo COMPANY_PARTNER_1_ID?> e <?php echo COMPANY_PARTNER_2_ID?>, 
						expedidos pelo <?php echo COMPANY_PARTNER_1_ID_ISSUED?> em <?php echo COMPANY_PARTNER_1_ID_ISSUED_DATE?> e pelo <?php echo COMPANY_PARTNER_2_ID_ISSUED?> em <?php echo COMPANY_PARTNER_2_ID_ISSUED_DATE?>, inscritos no CPF sob os nºs <?php echo COMPANY_PARTNER_1_CPF?> e <?php echo COMPANY_PARTNER_2_CPF?>, 
						respectivamente, 
						e de outro lado, como <strong>CESSIONÁRIO</strong>, 
						<strong><?php echo $contract['Client']['corporate_name']?></strong>, 
						sediado na Rua/Avenida <?php echo $contract['Client']['street']?>, n.º <?php echo $contract['Client']['number']?>, 
						<?php echo $contract['Client']['City']['name']?>/<?php echo $contract['Client']['State']['uf']?> - CEP: <?php echo $this->AppUtils->zipcode($contract['Client']['zipcode'])?>, 
						Inscrição Municipal n.º <?php echo $contract['Contract']['municipal_inscription']?>, 
						Inscrição Estadual n.º <?php echo $contract['Contract']['state_inscription']?>, 
						neste ato representado por seus sócios, procurador ou pelo Diretor-Presidente, consoante Estatuto Social ou procuração ora apresentado e anexado à presente, 
						Sr.(as). <strong><?php echo $contract['Contract']['responsible_partner']?></strong> 
						<?php echo (!empty($contract['Contract']['responsible_partner2']))?" e <strong>{$contract['Contract']['responsible_partner2']}</strong>":'';?>, 
						portador(es) da(s) carteira(s) de identidade(s) nº.(s) <?php echo $contract['Contract']['responsible_partner_id']?> 
						<?php echo (!empty($contract['Contract']['responsible_partner_id2']))?" e {$contract['Contract']['responsible_partner_id2']}":'';?>, 
						expedida(s) pela(o)  <?php echo $contract['Contract']['responsible_partner_id_issued']?>, em <?php echo $contract['Contract']['responsible_partner_id_issued_date']?> 
						<?php echo (!empty($contract['Contract']['responsible_partner_id_issued2']))?" e {$contract['Contract']['responsible_partner_id_issued2']}, em {$contract['Contract']['responsible_partner_id_issued_date2']}":'';?>,
						inscrito(s) no CPF sob os nº.(s) <?php echo $responsible_partner_cpf?>
						<?php echo (!empty($responsible_partner_cpf2))?" e {$responsible_partner_cpf2}":'';?>, 
						têm entre si justo e acordado o presente CONTRATO DE CESSÃO DE USO E EXPLORAÇÃO DE DADOS CADASTRAIS,  mediante as cláusulas e condições seguintes:
					</p>
				</blockquote>
			</div>
		</div>

		<dl>
			<dt>CLÁUSULA PRIMEIRA: Dos Serviços</dt>
			<dd>
				<strong>Parágrafo Primeiro:</strong>
				Ao <strong>CESSIONÁRIO</strong> estarão disponíveis todos os serviços prestados pela <strong>CEDENTE</strong>, observando-se as cláusulas e condições abaixo, assim como as respectivas tabelas de preço sempre anexadas ao Contrato.
			</dd>
			<dd>
				<strong>Parágrafo Segundo:</strong>
				O Banco de Dados e os Serviços da <strong>CEDENTE</strong> são de abrangência Nacional.
			</dd>
		</dl>

		<dl>
			<dt>CLÁUSULA SEGUNDA: Da Senha de Acesso</dt>
			<dd>
				<strong>Parágrafo Primeiro:</strong>
				O <strong>CESSIONÁRIO</strong> receberá da <strong>CEDENTE</strong> uma senha principal, secreta, pessoal, intransferível e única, que dará direito ao gerenciamento dos usuários para utilização dos serviços, não se responsabilizando a <strong>CEDENTE</strong> pelo mau uso ou pela divulgação da mesma pelo <strong>CESSIONÁRIO</strong>.
			</dd>
			<dd>
				<strong>Parágrafo Segundo:</strong>
				A Senha principal de acesso será enviada diretamente para o responsável da Empresa CESSIONÁRIA ou a quem este expressamente determinar, mediante recibo ou confirmação de recebimento, fato que gerará o desbloqueio. O responsável da Empresa CESSIONÁRIA terá acesso a um sistema de administração para criação de senhas de usuários, selecionando os serviços e quantidades de acordo com cada perfil para a utilização dos serviços aqui contratados.
			</dd>
			<dd>
				<strong>Parágrafo Terceiro:</strong>
				Se ocorrer qualquer fato com a senha de administração que possa comprometer o uso dos serviços ou utilização deles por terceiros, deve o <strong>CESSIONÁRIO</strong> comunicar imediatamente a <strong>CEDENTE</strong>, a fim de que possa bloqueá-la, emitindo-se nova senha de acesso.
			</dd>
			<dd>
				<strong>Parágrafo Quarto:</strong>
				Se ocorrer qualquer fato com a senha de usuário que possa comprometer o resultado de bilhetagem dos serviços utilizados, o <strong>CESSIONÁRIO</strong> (administrador) deve INIBIR imediatamente e comunicar a <strong>CEDENTE</strong>. Caso informação seja superior a 48 horas, o <strong>CESSIONÁRIO</strong> assume todas as bilhetagens executadas. 
			</dd>
			<dd>
				<strong>Parágrafo Quinto:</strong>
				Caso a informação seja inferior a 48 horas o <strong>CEDENTE</strong> disponibilizará relatórios do usuário detalhados para que o <strong>CESSIONÁRIO</strong> possa cobrar, comprovar e tomar todas as medidas cabíveis na lei.
			</dd>
		</dl>

		<dl>
			<dt>CLÁUSULA TERCEIRA: Da Disponibilidade dos Registros</dt>
			<dd>
				<strong>Parágrafo Primeiro:</strong>
				Cabe a <strong>CEDENTE</strong> atualizar com a maior freqüência possível, os dados e informações a que se refere este contrato, de modo a tornar sempre interessante o seu uso e sua exploração comercial.
			</dd>
			<dd>
				<strong>Parágrafo Segundo:</strong>
				Não são considerados como desatualização, os telefones desinstalados. Pois consta o último registro pertencente ao telefone até que o mesmo número seja utilizado para outro registro. O relatório de resultado tem o objetivo de apoiar decisões de crédito e negócios. A decisão de conceder ou não crédito é de inteira responsabilidade do contratante do serviço.
			</dd>
			<dd>
				<strong>Parágrafo Terceiro:</strong>
				Casos de divergências devem ser preenchidos o formulário de atualização e junto da xérox da última conta telefônica e enviada para o fax da <strong>CEDENTE</strong> para que o mesmo seja confirmado e atualizado.
			</dd>
			<dd>
				<strong>Parágrafo Quarto:</strong>
				Para os efeitos do disposto, quer na cláusula anterior, quer no que for cabível, em outra qualquer deste contrato, o termo “registro” é entendido como o conjunto de dados pertinente a uma determinada pessoa física ou a uma determinada pessoa jurídica.
			</dd>
			<!-- <dd>
				<strong>Parágrafo Quinto:</strong>
				Nas Consultas de Cheque, deve ser informado o CMC7, pois do contrário a consulta será restrita somente ao Cadastro de Emitentes de Cheques sem Fundos (CCF) do Banco Central e localização do CPF/CNPJ.
			</dd> -->
		</dl>

		<dl>
			<dt>CLÁUSULA QUARTA: Da vigência do Contrato</dt>
			<dd>
				<strong>Parágrafo Primeiro:</strong>
				O prazo de vigência do presente contrato é de <?php echo $contract['Contract']['validity']?>, iniciando-se no dia <?php echo $contract['Contract']['contract_ini_day']?> de <?php echo $contract['Contract']['contract_ini_month']?> de <?php echo $contract['Contract']['contract_ini_year']?> e encerrando-se no dia <?php echo $contract['Contract']['contract_end_day']?> de <?php echo $contract['Contract']['contract_end_month']?> de <?php echo $contract['Contract']['contract_end_year']?>.
			</dd>
			<dd>
				<strong>Parágrafo Segundo:</strong>
				Após o prazo previsto no parágrafo anterior, passará o contrato a vigorar por tempo indeterminado, podendo qualquer das partes, imotivadamente, denunciar o pacto, mediante prévio e expresso aviso de 30 (trinta) dias.
			</dd>
			<dd>
				<strong>Parágrafo Terceiro:</strong>
				Antes do prazo previsto no Parágrafo Primeiro, poderá o <strong>CESSIONÁRIO</strong>, mediante aviso prévio de 30 (trinta) dias, rescindir o presente, arcando com o pagamento, conforme Cláusula Quinta, Parágrafo Segundo e Terceiro.
			</dd>
		</dl>

		<dl>
			<dt>CLÁUSULA QUINTA: Do Pagamento</dt>
			<dd>
				<strong>Parágrafo Primeiro:</strong>
				O uso e/ou a exploração dos dados a que se prende este contrato far-se-á pelo <strong>CESSIONÁRIO</strong>, mediante remuneração mínima pré-estabelecida e através de consultas com a utilização dos meios, máquinas e equipamentos disponibilizados pelo <strong>CESSIONÁRIO</strong>, sem qualquer ônus para a <strong>CEDENTE</strong>.
			</dd>
			<dd>
				<strong>Parágrafo Segundo:</strong>
				Para cada serviço disponível haverá sempre uma tabela específica, contendo valor e quantidade de consultas, de prévio conhecimento do <strong>CESSIONÁRIO</strong> e fornecido neste ato. Em assim sendo, o <strong>CESSIONÁRIO</strong> pagará a <strong>CEDENTE</strong> o equivalente a tais Tabelas, fazendo-se a respectiva apuração do número de consultas no último dia de cada mês.
			</dd>
			<dd>
				<strong>Parágrafo Terceiro:</strong>
				Fica ciente o <strong>CESSIONÁRIO</strong> que o valor mínimo a ser pago mensalmente será aquele constante na Tabela vigente.
			</dd>
			<dd>
				<strong>Parágrafo Quarto:</strong>
				O pagamento será realizado sempre através de Boleta Bancária todo dia 10 do mês imediatamente seguinte, mediante recibo e a competente Nota Fiscal enviada via e-mail do <strong>CESSIONÁRIO</strong>. O não pagamento da fatura na data acordada acarretará a incidência de correção monetária sob pena de multa, juros legais de 1% ao mês e multa de 2% sobre o saldo devedor. Caso não receba a boleta até o dia 05(cinco) do mês o <strong>CESSIONÁRIO</strong> deve entrar em contato com a <strong>CEDENTE</strong>, para não aplicar os parágrafos abaixo.
			</dd>
			<dd>
				<strong>Parágrafo Quinto:</strong>
				Fica ciente o <strong>CESSIONÁRIO</strong> que em caso de qualquer alteração de endereço ou e-mail, deverá o mesmo comunicar por escrito tal mudança ao <strong>CEDENTE</strong> a fim de que o boleto não seja encaminhado ao endereço errado. Caso ocorra o não pagamento do boleto mensal em decorrência da não comunicação destas alterações, ficará o <strong>CESSIONÁRIO</strong> sujeito a aplicação do Parágrafo Sexto.
			</dd>
			<dd>
				<strong>Parágrafo Sexto:</strong>
				Fica facultado a <strong>CEDENTE</strong> à suspensão automática dos serviços após 5 (cinco) dias do vencimento pela falta de pagamento da fatura vencida, ressalvando ainda, o direito de cobrança pelos serviços até então prestados e, a inclusão no cadastro de devedores no SPC do SERASA, CDL e similares, caso a inadimplência atinja o limite autorizativo para tanto.
			</dd>
			<dd>
				<strong>Parágrafo Sétimo:</strong>
				Em caso de necessidade de cobrança do saldo devedor, via judicial, incidirá, ainda, sobre o valor devido multa penal de 10% (dez por cento) sobre o valor, assim como honorários advocatícios na base de 20% (vinte por cento).
			</dd>
			<dd>
				<strong>Parágrafo Oitavo:</strong>
				Tendo em vista a demora do repasse do pagamento pelas casas lotéricas, o pagamento de boletos efetuados nestes estabelecimentos deverá ser informado imediatamente via fax Tel: <?php echo COMPANY_PHONE?> ou e-mail para <?php echo COMPANY_EMAIL_BUSINESSES?>, a fim de evitar a suspensão do serviço pelos motivos descritos no Parágrafo Quinto acima.
			</dd>
			<dd>
				<strong>Parágrafo Nono:</strong>
				As Tabelas de preços serão reajustadas anualmente, no primeiro dia de dezembro, passando a vigorar imediatamente, com a conseqüente revogação das tabelas anteriormente fornecidas, sendo certo que tal reajuste se dará pelo IGPM ou outro índice legal equivalente, a fim de se evitar o desequilíbrio econômico do pacto.
			</dd>
			<dd>
				<strong>Parágrafo Décimo:</strong>
				Tabela vigente: Qualquer atualização que ocorrer na Tabela vigente será sempre enviada, assinada para fazer parte do contrato em substituição a anterior.
			</dd>
		</dl>

		<dl>
			<dt>CLÁUSULA SEXTA: Da Infraestrutura e desenvolvimento dos serviços</dt>
			<dd>
				<strong>Parágrafo Primeiro:</strong>
				Caberá exclusivamente o <strong>CESSIONÁRIO</strong> a implantação da infraestrutura necessária à utilização dos serviços ora contratados. 
			</dd>
			<dd>
				<strong>Parágrafo Segundo:</strong>
				A <strong>CEDENTE</strong> disponibilizará para o <strong>CESSIONÁRIO</strong>, os relatórios de consultas on-line, destacadas por Estado da Federação.  
			</dd>
		</dl>

		<dl>
			<dt>DISPOSIÇÕES GERAIS:</dt>
			<dd>
				O <strong>CESSIONÁRIO</strong> fica obrigado a manter seu cadastro sempre atualizado junto a <strong>CEDENTE</strong>, no que tange a empresas participantes do Grupo, alteração de razão social, substituição de sócio gerente, etc. sempre no intuito de manter a segurança e sigilo dos serviços prestados pela <strong>CEDENTE</strong>.
				Os futuros serviços disponibilizados pela <strong>CEDENTE</strong>, poderão ser utilizados imediatamente pelo <strong>CESSIONÁRIO</strong>, sendo certo que sempre haverá uma tabela de valores para cada serviço que deverá ser assinada e anexada ao contrato.
				Os casos omissos serão solucionados pelo consenso das partes contratantes e na conformidade da legislação que for aplicável.
				Fica eleito o foro da Comarca da Capital do Estado do(e) <?php echo COMPANY_STATE?> para a solução de todas as questões oriundas do presente contrato, com renúncia a qualquer outro.
				E por assim se acharem justos e acordados, assinam o presente em 02 (duas) vias, de igual teor e forma, na presença de 02 (duas) testemunhas devidamente identificadas, que também o subscrevem, com o reconhecimento em cartório de todas essas assinaturas, para que produza o seu legal e devido efeito.
				<br>
				<br>
				<?php echo COMPANY_STATE?>, <?php echo $contract['Contract']['contract_ini_day']?> de <?php echo $contract['Contract']['contract_ini_month']?> de <?php echo $contract['Contract']['contract_ini_year']?>.
			</dd>
		</dl>

		<div style="margin-top: 100px;" class="row-fluid">
			<div class="span6 offset3 text-center">
				<hr>
				<?php echo COMPANY_FANCY_NAME?> 
				<?php echo COMPANY_CORPORATE_NAME?> 
			</div>
		</div>

		<div style="margin-top: 50px;" class="row-fluid">
			<div class="span6 offset3 text-center">
				<hr>
				<?php echo $contract['Client']['corporate_name']?>
			</div>
		</div>

		<div style="margin-top: 50px;" class="row-fluid">
			<div class="span5 text-center">
				<hr>
				Nome: <?php echo COMPANY_PARTNER_2?><br>
				Identidade: <?php echo COMPANY_PARTNER_2_ID?><br>
				CPF: <?php echo COMPANY_PARTNER_2_CPF?><br>
			</div>
			<div class="span5 offset2 text-center">
				<hr>
				Nome: <?php echo $contract['Contract']['responsible_partner']?><br>
				Identidade: <?php echo $contract['Contract']['responsible_partner_id']?><br>
				CPF: <?php echo $responsible_partner_cpf?><br>
			</div>
		</div>
	</div>
</div>