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
		if(!empty($this->params['named']['search'])){
			$params = array(
				'combine' => false,
				'conditions' => array(
					'OR' => array(
						'Translation.msgid LIKE' => "%{$this->params['named']['search']}%",
						'Translation.msgstr LIKE' => "%{$this->params['named']['search']}%",
						)
				));
		}

		//@override
		parent::index($params);
	}

	/**
	* Método edit
	* Este método contem regras de negocios para adicionar e editar registros na base de dados
	*
	* @override Metodo AppController.edit
	* @param string $id
	* @return void
	*/
	public function edit($id=null){
		//@override
		parent::edit($id);
	}	

	/**
	* Método loadLocale
	* Este método insere todas as traducoes salvas na tabela transactions no arquivo app/Locale/por/LC_MESSAGES/default.po
	*
	* @return void
	*/
	public function loadLocale(){
		/**
		* Carrega os diretorios
		*/
		$dir_locale = APP . 'Locale/por/LC_MESSAGES/default.po';
		$dir_header = APP . 'Locale/por/LC_MESSAGES/header.po';
		/**
		* Carrega o arquivo de traducoes
		*/
		$header = file_get_contents($dir_header);
		$locale = file_get_contents($dir_locale);

		/**
		* Adiciona o cabeçalho no arquivo
		*/
		file_put_contents($dir_locale, $header);

		/**
		* Carrega as traducoes do banco
		*/
		$translations = $this->Translation->find('list', array('fields' => array('Translation.msgid', 'Translation.msgstr'), 'order' => 'msgid'));

		/**
		* Percorre por todas as trucoes escrevendo cada uma em default.po
		*/
		foreach ($translations as $k => $v) {
			$msg = "msgid \"{$k}\"\nmsgstr \"{$v}\"\n\n";
			file_put_contents($dir_locale, $msg, FILE_APPEND | LOCK_EX);
		}

		$this->Session->setFlash('Todas as mensagens foram traduzidas.', FLASH_TEMPLATE, array('class' => FLASH_CLASS_SUCCESS), FLASH_SESSION_FORM);
		$this->redirect($this->referer());
	}
}