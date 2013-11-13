<?php
App::uses('AppModelClean', 'Model');
/**
 * Settings Model
 *
 * Esta classe é responsável ​​pela gestão de quase tudo o que acontece a respeito do(a) Estado, 
 * é responsável também pela validação dos seus dados.
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Model
 *
 * Settings Model
 *
 * @property Country $Country
 * @property City $City
 */
class Settings extends AppModelClean {
	public $useTable = '_settings';


	public function active($module){
		$isActive = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/_db/settings/on_off');
		$isActive = preg_replace('/[^0-1]/si', '', $isActive);

		if($isActive == '0'){
			$content = "\n\n\n\n";
			$content .= "###################################################################\n";
			$content .= "Time: " . date('Y-m-d H:i:s') . "\n";
			$content .= "===================================================================\n";
			$content .= "Importacao Pausada.\n";
			$content .= "===================================================================\n";

			echo $content;		
		}

		return $isActive;
	}
}
