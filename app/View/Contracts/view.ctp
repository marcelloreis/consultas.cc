<?php 
/**
* Oculta o sidebar
*/
$this->assign('sidebar-class-hidden', 'nav-hidden');

$responsible_partner_cpf = $this->AppUtils->cpf($contract['Contract']['responsible_partner_cpf']);
$responsible_partner_cpf2 = $this->AppUtils->cpf($contract['Contract']['responsible_partner_cpf2']);

echo $this->Html->link('Gerar PDF', array('action'=>'download', 'ext'=>'pdf', 1, 'nome-do-contrato'));
?>

Contrato nº.:<?php echo $contract['Contract']['id']?>

INSTRUMENTO PARTICULAR DE CONTRATO
DE CESSÃO DE USO E EXPLORAÇÃO DE
DADOS CADASTRAIS,
Que fazem entre si:

De um lado como CEDENTE: <?php echo COMPANY_CORPORATE_NAME?> utiliza como nome fantasia a denominação de <?php echo COMPANY_FANCY_NAME?>, sediada na <?php echo COMPANY_ADDRESS?>, inscrita no CNPJ sob o n.º <?php echo COMPANY_CNPJ?>, inscrição municipal nº <?php echo COMPANY_INSCRIPTION?>, neste ato representado por seus sócios-gerentes <?php echo COMPANY_PARTNER_1?> e <?php echo COMPANY_PARTNER_2?>, portadores das carteiras de identidades nºs <?php echo COMPANY_PARTNER_1_ID?> e <?php echo COMPANY_PARTNER_2_ID?>, expedidos pelo DETRAN em 10/05/2004 e pelo IFP em 25/11/1994, inscritos no CPF sob os nºs <?php echo COMPANY_PARTNER_1_CPF?> e <?php echo COMPANY_PARTNER_2_CPF?>, respectivamente, 
e de outro lado, como
CESSIONÁRIO, 
<?php echo $contract['Client']['corporate_name']?>, 
sediado na Rua/Avenida <?php echo $contract['Client']['street']?>, n.º <?php echo $contract['Client']['number']?>, <?php echo $contract['Client']['City']['name']?>/<?php echo $contract['Client']['State']['uf']?> - CEP: <?php echo $contract['Client']['zipcode']?>, 
Inscrição Municipal n.º <?php echo $contract['Contract']['municipal_inscription']?>, 
Inscrição Estadual n.º  <?php echo $contract['Contract']['state_inscription']?>, 
neste ato representado por seus sócios, procurador ou pelo Diretor-Presidente, consoante Estatuto Social ou procuração ora apresentado e anexado à presente, 
Sr.(as). <?php echo $contract['Contract']['responsible_partner']?> 
<?php echo (!empty($contract['Contract']['responsible_partner2']))?" e {$contract['Contract']['responsible_partner2']}":'';?>, 
portador(es) da(s) carteira(s) de identidade(s) nº.(s)  <?php echo $contract['Contract']['responsible_partner_id']?> 
<?php echo (!empty($contract['Contract']['responsible_partner_id2']))?" e {$contract['Contract']['responsible_partner_id2']}":'';?>, 
expedida(s) pela(o)  <?php echo $contract['Contract']['responsible_partner_id_issued']?>, em <?php echo $contract['Contract']['responsible_partner_id_issued_date']?> 
<?php echo (!empty($contract['Contract']['responsible_partner_id_issued2']))?" e {$contract['Contract']['responsible_partner_id_issued2']}, em {$contract['Contract']['responsible_partner_id_issued_date2']}":'';?>,
inscrito(s) no CPF sob os nº.(s) <?php echo $responsible_partner_cpf?>
<?php echo (!empty($responsible_partner_cpf2))?" e {$responsible_partner_cpf2}":'';?>, 
têm entre si justo e acordado o presente CONTRATO DE CESSÃO DE USO E EXPLORAÇÃO DE DADOS CADASTRAIS,  mediante as cláusulas e condições seguintes:

CLÁUSULA PRIMEIRA: Dos Serviços
Parágrafo Primeiro: - Ao CESSIONÁRIO estarão disponíveis todos os serviços prestados pela CEDENTE, observando-se as cláusulas e condições abaixo, assim como as respectivas tabelas de preço sempre anexadas ao Contrato.
Parágrafo Segundo: - O Banco de Dados e os Serviços da CEDENTE são de abrangência Nacional.

CLÁUSULA SEGUNDA: Da Senha de Acesso
Parágrafo Primeiro: - O CESSIONÁRIO receberá da CEDENTE uma senha principal, secreta, pessoal, intransferível e única, que dará direito ao gerenciamento dos usuários para utilização dos serviços, não se responsabilizando a CEDENTE pelo mau uso ou pela divulgação da mesma pelo CESSIONÁRIO.
Parágrafo Segundo: - A Senha principal de acesso será enviada diretamente para o responsável da Empresa CESSIONÁRIA ou a quem este expressamente determinar, mediante recibo ou confirmação de recebimento, fato que gerará o desbloqueio. O responsável da Empresa CESSIONÁRIA terá acesso a um sistema de administração para criação de senhas de usuários, selecionando os serviços e quantidades de acordo com cada perfil para a utilização dos serviços aqui contratados.
Parágrafo Terceiro: - Se ocorrer qualquer fato com a senha de administração que possa comprometer o uso dos serviços ou utilização deles por terceiros, deve o CESSIONÁRIO comunicar imediatamente a CEDENTE, a fim de que possa bloqueá-la, emitindo-se nova senha de acesso.
Parágrafo Quarto: - Se ocorrer qualquer fato com a senha de usuário que possa comprometer o resultado de bilhetagem dos serviços utilizados, o CESSIONÁRIO (administrador) deve INIBIR imediatamente e comunicar a CEDENTE. Caso informação seja superior a 48 horas, o CESSIONÁRIO assume todas as bilhetagens executadas. 
Parágrafo Quinto: - Caso a informação seja inferior a 48 horas o CEDENTE disponibilizará relatórios do usuário detalhados para que o CESSIONÁRIO possa cobrar, comprovar e tomar todas as medidas cabíveis na lei.

CLÁUSULA TERCEIRA: Da Disponibilidade dos Registros
Parágrafo Primeiro: - Cabe a CEDENTE atualizar com a maior freqüência possível, os dados e informações a que se refere este contrato, de modo a tornar sempre interessante o seu uso e sua exploração comercial.
Parágrafo Segundo: - Não são considerados como desatualização, os telefones desinstalados. Pois consta o último registro pertencente ao telefone até que o mesmo número seja utilizado para outro registro. O relatório de resultado tem o objetivo de apoiar decisões de crédito e negócios. A decisão de conceder ou não crédito é de inteira responsabilidade do contratante do serviço.
Parágrafo Terceiro: - Casos de divergências devem ser preenchidos o formulário de atualização e junto da xérox da última conta telefônica e enviada para o fax da CEDENTE para que o mesmo seja confirmado e atualizado.
Parágrafo Quarto: - Para os efeitos do disposto, quer na cláusula anterior, quer no que for cabível, em outra qualquer deste contrato, o termo “registro” é entendido como o conjunto de dados pertinente a uma determinada pessoa física ou a uma determinada pessoa jurídica.
<!-- Parágrafo Quinto: - Nas Consultas de Cheque, deve ser informado o CMC7, pois do contrário a consulta será restrita somente ao Cadastro de Emitentes de Cheques sem Fundos (CCF) do Banco Central e localização do CPF/CNPJ. -->

CLÁUSULA QUARTA: Da vigência do Contrato
Parágrafo Primeiro: - O prazo de vigência do presente contrato é de <?php echo $contract['Contract']['validity']?>, iniciando-se no dia <?php echo $contract['Contract']['contract_ini_day']?> de <?php echo $contract['Contract']['contract_ini_month']?> de <?php echo $contract['Contract']['contract_ini_year']?> e encerrando-se no dia <?php echo $contract['Contract']['contract_end_day']?> de <?php echo $contract['Contract']['contract_end_month']?> de <?php echo $contract['Contract']['contract_end_year']?>.
Parágrafo Segundo: - Após o prazo previsto no parágrafo anterior, passará o contrato a vigorar por tempo indeterminado, podendo qualquer das partes, imotivadamente, denunciar o pacto, mediante prévio e expresso aviso de 30 (trinta) dias.
Parágrafo Terceiro: - Antes do prazo previsto no Parágrafo Primeiro, poderá o CESSIONÁRIO, mediante aviso prévio de 30 (trinta) dias, rescindir o presente, arcando com o pagamento, conforme Cláusula Quinta, Parágrafo Segundo e Terceiro.

CLÁUSULA QUINTA: Do Pagamento
Parágrafo Primeiro: - O uso e/ou a exploração dos dados a que se prende este contrato far-se-á pelo CESSIONÁRIO, mediante remuneração mínima pré-estabelecida e através de consultas com a utilização dos meios, máquinas e equipamentos disponibilizados pelo CESSIONÁRIO, sem qualquer ônus para a CEDENTE.
Parágrafo Segundo: - Para cada serviço disponível haverá sempre uma tabela específica, contendo valor e quantidade de consultas, de prévio conhecimento do CESSIONÁRIO e fornecido neste ato. Em assim sendo, o CESSIONÁRIO pagará a CEDENTE o equivalente a tais Tabelas, fazendo-se a respectiva apuração do número de consultas no último dia de cada mês.
Parágrafo Terceiro: Fica ciente o CESSIONÁRIO que o valor mínimo a ser pago mensalmente será aquele constante na Tabela vigente.
Parágrafo Quarto: O pagamento será realizado sempre através de Boleta Bancária todo dia 10 do mês imediatamente seguinte, mediante recibo e a competente Nota Fiscal enviada via e-mail do CESSIONÁRIO. O não pagamento da fatura na data acordada acarretará a incidência de correção monetária sob pena de multa, juros legais de 1% ao mês e multa de 2% sobre o saldo devedor. Caso não receba a boleta até o dia 05(cinco) do mês o CESSIONÁRIO deve entrar em contato com a CEDENTE, para não aplicar os parágrafos abaixo.
Parágrafo Quinto: Fica ciente o CESSIONÁRIO que em caso de qualquer alteração de endereço ou e-mail, deverá o mesmo comunicar por escrito tal mudança ao CEDENTE a fim de que o boleto não seja encaminhado ao endereço errado. Caso ocorra o não pagamento do boleto mensal em decorrência da não comunicação destas alterações, ficará o CESSIONÁRIO sujeito a aplicação do Parágrafo Sexto.
Parágrafo Sexto: Fica facultado a CEDENTE à suspensão automática dos serviços após 5 (cinco) dias do vencimento pela falta de pagamento da fatura vencida, ressalvando ainda, o direito de cobrança pelos serviços até então prestados e, a inclusão no cadastro de devedores no SPC do SERASA, CDL e similares, caso a inadimplência atinja o limite autorizativo para tanto.
Parágrafo Sétimo: Em caso de necessidade de cobrança do saldo devedor, via judicial, incidirá, ainda, sobre o valor devido multa penal de 10% (dez por cento) sobre o valor, assim como honorários advocatícios na base de 20% (vinte por cento).
Parágrafo Único: - Tendo em vista a demora do repasse do pagamento pelas casas lotéricas, o pagamento de boletos efetuados nestes estabelecimentos deverá ser informado imediatamente via fax Tel: <?php echo COMPANY_PHONE?> ou e-mail para <?php echo COMPANY_EMAIL_BUSINESSES?>, a fim de evitar a suspensão do serviço pelos motivos descritos no Parágrafo Quinto acima.
Parágrafo Oitavo: - As Tabelas de preços serão reajustadas anualmente, no primeiro dia de dezembro, passando a vigorar imediatamente, com a conseqüente revogação das tabelas anteriormente fornecidas, sendo certo que tal reajuste se dará pelo IGPM ou outro índice legal equivalente, a fim de se evitar o desequilíbrio econômico do pacto.
Parágrafo Novo: - Tabela vigente: Qualquer atualização que ocorrer na Tabela vigente será sempre enviada, assinada para fazer parte do contrato em substituição a anterior.

CLÁUSULA SEXTA: Da Infraestrutura e desenvolvimento dos serviços
Parágrafo Primeiro: - Caberá exclusivamente o CESSIONÁRIO a implantação da infraestrutura necessária à utilização dos serviços ora contratados. 
Parágrafo Segundo: - A CEDENTE disponibilizará para o CESSIONÁRIO, os relatórios de consultas on-line, destacadas por Estado da Federação.  

DISPOSIÇÕES GERAIS:
O CESSIONÁRIO fica obrigado a manter seu cadastro sempre atualizado junto a CEDENTE, no que tange a empresas participantes do Grupo, alteração de razão social, substituição de sócio gerente, etc. sempre no intuito de manter a segurança e sigilo dos serviços prestados pela CEDENTE.
Os futuros serviços disponibilizados pela CEDENTE, poderão ser utilizados imediatamente pelo CESSIONÁRIO, sendo certo que sempre haverá uma tabela de valores para cada serviço que deverá ser assinada e anexada ao contrato.
Os casos omissos serão solucionados pelo consenso das partes contratantes e na conformidade da legislação que for aplicável.
Fica eleito o foro da Comarca da Capital do Estado do(e) <?php echo COMPANY_STATE?> para a solução de todas as questões oriundas do presente contrato, com renúncia a qualquer outro.
E por assim se acharem justos e acordados, assinam o presente em 02 (duas) vias, de igual teor e forma, na presença de 02 (duas) testemunhas devidamente identificadas, que também o subscrevem, com o reconhecimento em cartório de todas essas assinaturas, para que produza o seu legal e devido efeito.

Rio de Janeiro, <?php echo $contract['Contract']['contract_ini_day']?> de <?php echo $contract['Contract']['contract_ini_month']?> de <?php echo $contract['Contract']['contract_ini_year']?>.


____________________________________________________________________
<?php echo COMPANY_FANCY_NAME?> 
<?php echo COMPANY_CORPORATE_NAME?> 


___________________________________________________________________
<?php echo $contract['Client']['corporate_name']?>, 
     


TESTEMUNHA:________________________        TESTEMUNHA: _________________________
Nome: <?php echo COMPANY_PARTNER_2?>.              	Nome: <?php echo $contract['Contract']['responsible_partner']?>
Identidade: <?php echo COMPANY_PARTNER_2_ID?>		Identidade: <?php echo $contract['Contract']['responsible_partner_id']?>
CPF: <?php echo COMPANY_PARTNER_2_CPF?>				CPF: <?php echo $responsible_partner_cpf?>

