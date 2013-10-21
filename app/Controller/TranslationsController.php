<?php
/**
 * Static content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/Locales/
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Controller
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Este controlador contem regras de negócio aplicadas ao model Group
 *
 * @package       app.Controller
 * @link http://.framework.nasza.com.br/2.0/controller/Locales.html
 */
class TranslationsController extends AppController {
	/**
	* Método index
	* Este método contem regras de negocios visualizar todos os registros contidos na entidade do controlador
	*
	* @override Metodo AppController.index
	* @param string $period (Periodo das movimentacoes q serao listadas)
	* @return void
	*/
	public function index($params=array()){
		//@override
		parent::index($params);
	}

	public function translator(){
		$translations = array(
			'yes' => 'sim',
			'no' => 'não',
			'save' => 'salvar',
			'cancel' => 'cancelar',
			'save changes' => 'salvar alterações',
			'controllers' => 'controladores',
			'%s permissions' => 'permissões de(a) %s',
			'close %s' => 'fechar o %s',
			'allow/deny all' => 'permitir/negar todos',
			'allow all' => 'permitir todos',
			'deny all' => 'negar todos',
			'switch main menu' => 'minimizar/maximizar menu principal',
			'this field must be filled in correctly.' => 'este campo deve ser preenchido corretamente.',
			'form saved successfully.' => 'formulário salvo com sucesso.',
			'manage by %s' => 'gerenciar por %s',
			'refresh %s' => 'atualizar %s',
			'refresh %s and %s' => 'atualizar %s e %s',
			'delete %s and %s' => 'excluír %s e %s',
			'delete %s' => 'excluír %s',
			'reset all settings' => 'reiniciar todas as configurações',
			'are you sure you want to reset all settings?' => 'tem certeza que deseja reiniciar toda a configuração?',
			'are you sure you want to delete all %s and %s?' => 'tem certeza que deseja excluir todos(as) os(as) %s e %s?',
			'are you sure you want to delete all %s' => 'tem certeza que deseja excluir todos(as) os(as) %s',
			'select' => 'selecione',
			'settings' => 'configurações',
			'male' => 'masculino',
			'female' => 'feminino',
			'your session has expired. make a new login!' => 'sua sessão expirou. faça um novo login!',
			'you do not have permission to view' => 'você não tem permissão para visualizar',
			'you do not have permission to edit' => 'você não tem permissão para alterar',
			'you are not allowed to view the content of' => 'você não tem permissão para visualizar o conteúdo de',
			'you are not allowed to add' => 'você não tem permissão para adicionar',
			'you are not allowed to trash' => 'você não tem permissão para excluir',
			'you are not allowed to delete' => 'você não tem permissão para excluir permanentemente',
			'you are not allowed to view trashed records' => 'você não tem permissão para vizualizar registros da lixeira',
			'you are not allowed to view deleted records' => 'você não tem permissão para visualizar registros excluídos',
			'it was not possible to view the %s, or it does not exist in the database.' => 'não foi possível visualizar o %s, ou ele não existe na base de dados.',
			'it was not possible to edit the %s [%s], or it does not exist in the database.' => 'não foi possível alterar o %s [%s], ou ele não existe na base de dados.',
			'the %s does not exist in the database.' => 'o %s não existe na base de dados.',
			'%s moved to the trash.' => '% movido para a lixeira.',
			'unable to move the %s to the trash.' => 'não foi possível mover o %s para a lixeira.',
			'%s deleted.' => '%s excluído',
			'unable delete the %s.' => 'não foi possível excluir o %s.',
			'%s restored.' => '%s restaurado.',
			'unable restore the %s.' => 'não foi possível restaurar o %s.',
			'%s restaured to the trash.' => '% restaurado da lixeira.',
			'unable restore the %s to the trash.' => 'não foi possível restaurar o %s da lixeira.',
			'the %s was disassociated %s successfully.' => 'a %s foi desassociada do %s com sucesso.',
			'could not unbind %s and %s.' => 'não foi possível desvincular %s e %s.',
			'edit %s' => 'alterar %s',
			'actions' => 'ações',
			'name' => 'nome',
			'version' => 'versão',
			'unjoin' => 'desvincular',
			'add relationship' => 'adicionar relacionamento',
			'id' => 'código',
			'description' => 'descrição',
			'locales' => 'locais',
			'countries' => 'países',
			'states' => 'estados',
			'security' => 'segurança',
			'groups' => 'grupos',
			'permissions' => 'permissões',
			'logout' => 'sair',
			'view record' => 'vizualizar registro',
			'are you sure you want to remove this association?' => 'tem certeza de que deseja remover esta associação?',
			'add record' => 'adicionar registro',
			'add/delete record' => 'adicionar/excluir registro',
			'edit record' => 'alterar registro',
			'restore' => 'restaurar',
			'are you sure you want to restore this record from the trash?' => 'tem certeza de que deseja restaurar o registro da lixeira?',
			'trash' => 'lixeira',
			'are you sure you want to move this record to the trash?' => 'tem certeza de que deseja mover esse registro para a lixeira?',
			'enter the data for the new record.' => 'insira os dados do novo regristro.',
			'last change of this record.' => 'última alteração deste registro.',
			'new' => 'novo(a)',
			'list' => 'listar',
			'list %s' => 'listar %s',
			'first' => 'início',
			'last' => 'último',
			'next' => 'próximo',
			'prev' => 'anterior',
			'what are you looking for' => 'o que esta procurando',
			'bulk actions' => 'ações em massa',
			'move to trash' => 'mover para a lixeira',
			'delete permanently' => 'excluír permanentemente',
			'apply' => 'aplicar',
			'apply the action' => 'aplicar a ação',
			'show all' => 'tudo',
			'show all %s' => 'exibir todos(as) %s',
			'show all %s trashed' => 'exibir todos(as) %s da lixeira',
			'add a %s' => 'adicionar um(a) %s',
			'save associated records' => 'salvar registros associados',
			'personal information' => 'informações pessoais',
			'click the magnifying glass to search' => 'clique na lupa para buscar',
			'record(s) found' => 'registro(s) encontrados',
			'common actions' => 'ações comuns',
			'specific actions' => 'ações específicas',
			'index' => 'índice',
			'edit' => 'alterar',
			'add' => 'adicionar',
			'view' => 'visualizar',
			'delete' => 'excluir',
			'back' => 'voltar',
			'options' => 'opções',
			'document data' => 'dados do documento',
			'searches' => 'buscas',
			'search' => 'buscar',
			'by document' => 'por documento',
			'by name' => 'por nome',
			'by address' => 'por endereço',
			'by landline' => 'por telefone fixo',
			'company' => 'empresa',
			'mother' => 'mãe',
			'document' => 'documento',
			'updated' => 'atualizado',
			'age' => 'idade',
			'gender' => 'sexo',
			'person' => 'pessoa',
			'type name' => 'digite o nome',
			'type address' => 'digite o endereço',
			'type landline' => 'digite o telefone',
			'type document' => 'digite o documento',
			'landline data' => 'dados do telefone',
			'warning' => 'atenção',
			'%s updated in %s' => '%s atualizado em %s',
			'no address for this landline' => 'nenhum endereço para este telefone',
			'address data' => 'endereço do localizador',
			'products' => 'produtos',
			'sister' => 'irmã',
			'brother' => 'irmão',
			'same floor' => 'mesmo andar',
			'same street' => 'mesma rua',
			'same neighborhood' => 'mesmo bairro',
			'same city' => 'mesma cidade',
			'same state' => 'mesmo estado',
			'family' => 'família',
			'shareholding' => 'participação societária',
			'entities' => 'pessoas',
			'mobile' => 'telefone móvel',
			'death' => 'obito',
			'address' => 'endereço',
			'landline' => 'telefone fixo',
			'event' => 'evento',
			'city' => 'cidade',
			'country' => 'país',
			'grid' => 'grade',
			'group' => 'grupo',
			'inscription' => 'inscrição',
			'marketing' => 'email marketing',
			'responsible' => 'responsável',
			'social' => 'perfil social',
			'speaker' => 'palestrante',
			'sponsor' => 'patrocinador',
			'state' => 'estado',
			'student' => 'estudante',
			'user' => 'usuário',
			'workshop' => 'oficina',
			'users' => 'usuários',
			'cities' => 'cidades',
			'events' => 'eventos',
			'grids' => 'grades',
			'students' => 'estudantes',
			'inscriptions' => 'inscrições',
			'marketings' => 'email marketings',
			'workshops' => 'oficinas',
			'sponsors' => 'patrocinadores',
			'responsibles' => 'responsáveis',
			'speakers' => 'palestrantes',
			'street' => 'rua',
			'event_id' => 'evento',
			'city_id' => 'cidade',
			'country_id' => 'país',
			'grid_id' => 'grade',
			'group_id' => 'grupo',
			'inscription_id' => 'inscrição',
			'marketing_id' => 'emailmarketing',
			'responsible_id' => 'responsável',
			'social_id' => 'perfil social',
			'speaker_id' => 'palestrante',
			'sponsor_id' => 'patrocinador',
			'state_id' => 'estado',
			'student_id' => 'estudante',
			'user_id' => 'usuário',
			'workshop_id' => 'oficina',
			'people' => 'pessoas',
			'about' => 'sobre',
			'neighborhood' => 'bairro',
			'facebook_link' => 'facebook',
			'twitter_link' => 'twitter',
			'google_link' => 'google',
			'password' => 'senha',
			'doc' => 'documento',
			'birthday' => 'aniversário',
			'telephone' => 'telefone',
			'telephone1' => 'telefone',
			'telephone2' => 'telefone',
			'sex' => 'sexo',
			'shirt_size' => 'tamanho camisa',
			'study_level' => 'formação',
			'complement' => 'complemento',
			'is_paid' => 'pago',
			'payment_type' => 'tipo pagamento',
			'print_invoice' => 'dt impressão fatura',
			'matriculation' => 'matrícula',
			'status' => 'ativo',
			'type' => 'tipo',
			'printable_name' => 'nome de exibição',
			'numcode' => 'código',
			'uf' => 'uf',
			'ddd' => 'ddd',
			'given_name' => 'apelido',
			'subject' => 'assunto',
			'content' => 'conteúdo',
			'date_sent' => 'data de envio',
			'date_ini' => 'início',
			'date_end' => 'fim',
			'attached' => 'anexo',
			'send' => 'enviar',
			'zipcode' => 'cep',
			'instituition' => 'instituição',
			'course' => 'curso',
			'course_ini' => 'início do curso',
			'course_end' => 'final do curso',
			'course_period' => 'período do curso',
			'number' => 'número',
			'vacancies' => 'vagas',
			'available' => 'disponível',
			'interval' => 'intervalo',
			'hour ini' => 'hora ini',
			'hour end' => 'hora fim',
			'date_ini_time' => 'hora ini',
			'date_end_time' => 'hora fim',
			'newsletter' => 'receber notícias',
			'picture' => 'avatar',
		);

		// debug($translations);

		foreach ($translations as $k => $v) {
			$data = array('Translation' => array('msgid' => $k, 'msgstr' => $v));
			$this->Translation->create();
			$this->Translation->save($data);
			$msg_id = $this->Translation->id;

			$msgid_ucfirst = ucfirst($k);
			$msgstr_ucfirst = ucfirst($v);
			$data = array('Translation' => array('msgid' => $msgid_ucfirst, 'msgstr' => $msgstr_ucfirst, 'translation_id' => $msg_id));
			$this->Translation->create();
			$this->Translation->save($data);

			if(preg_match('/[ ]/si', trim($v))){
				$msgid_ucwords = ucwords($k);
				$msgstr_ucwords = ucwords($v);
				$data = array('Translation' => array('msgid' => $msgid_ucwords, 'msgstr' => $msgstr_ucwords, 'translation_id' => $msg_id));
				$this->Translation->create();
				$this->Translation->save($data);
			}
		}

		debug($this->Translation->find('all'));

		die('aqui');
	}	

}