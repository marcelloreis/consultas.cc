<?php
/**
 * Import content controller.
 *
 * Este arquivo ira renderizar as visões contidas em views/LandlinesImport/
 *
 * PHP 5
 *
 * @copyright     Copyright 2013-2013, Nasza Produtora
 * @link          http://www.nasza.com.br/ Nasza(tm) Project
 * @package       app.Controller
 */

App::uses('AppController', 'Controller');

/**
 * Import content controller
 *
 * Este controlador contem regras de negócio aplicadas ao model State
 *
 * @package       app.Controller
 * @link http://.framework.nasza.com.br/2.0/controller/LandlinesImport.html
 */
class StatisticsController extends AppController {
	public $uses = array(
		"Import", 
		"Settings",
		"Statistic",
		"Timing",
		);

	public $components = array('AppImport');

	/**
	* Atributos da classe
	*/
	private $db;
	private $uf;
	private $telefones_uf;
	private $pessoa_uf;
	private $endereco_uf;
	private $statistics;


	/**
	* Método beforeFilter
	* Esta função é executada antes de todas ações do controlador. 
	* E no caso da framework, esta sendo usado para checar uma sessão ativa e inspecionar permissões.
	*
	* @override Metodo AppController.beforeFilter
	* @return void
	*/
	public function beforeFilter() {
		//@override
		parent::beforeFilter();

		/**
		* Carrega os numeros dos contadores da importacao
		*/
		foreach ($this->Statistic->findAllByActive(true) as $k => $v) {
			$this->statistics[$v['Statistic']['table']] = $v['Statistic'];
		}		
	}	

	/**
	* Método reload
	* Este método recarrega a importacao, migrando o que ja foi importado e zerando as tabelas de importacao
	*
	* @return void
	*/
	public function reload_binary($controller, $uf){
		shell_exec("setsid sh " . ROOT . "/_db/settings/{$controller}_reload-binary.sh {$uf} > " . ROOT . "/_db/settings/log-sh 2>/dev/null &");

		$this->redirect($this->referer());
	}

	/**
	* Método reload
	* Este método recarrega a importacao, migrando o que ja foi importado e zerando as tabelas de importacao
	*
	* @return void
	*/
	public function reload_text($controller){
		shell_exec("setsid sh " . ROOT . "/_db/settings/{$controller}_reload-text.sh > " . ROOT . "/_db/settings/log-sh 2>/dev/null &");

		$this->redirect($this->referer());
	}

	/**
	* Método lock
	* Este método trava/libera a importacao
	*
	* @return void
	*/
	public function lock($switch){
		file_put_contents(ROOT . "/_db/settings/on_off", $switch);

		$this->redirect($this->referer());
	}

	/**
	* Método index
	* Este método carrega as estatisticas da importacao vigente
	*
	* @return void
	*/
	public function panel($uf=null){
		if((!empty($this->statistics['entities']['success']) || !empty($this->statistics['entities']['fails']))){
			/**
			* Carrega a quantidade de registros a serem importados
			*/
			$imports['records_to_process'] = $this->statistics['entities']['extracted'];

			/**
			* Carrega a quantidade de registros ja processados
			*/
			$imports['records_processed'] = ($this->statistics['entities']['success'] + $this->statistics['entities']['fails']);
			$imports['records_processed'] = $imports['records_processed'] > $this->statistics['entities']['extracted']?$this->statistics['entities']['extracted']:$imports['records_processed'];

			/**
			* Calcula o progresso da importacao
			*/
			$imports['progress'] = floor(($imports['records_processed'] / $imports['records_to_process']) * 100);

			/**
			* Calcula o tempo percorrido da importacao
			*/
			$imports['elapsed'] = $this->getElapsed();

			/**
			* Calcula o tempo percorrido da importacao
			*/
			$imports['remaining'] = $this->getRemaining();

			/**
			* Carrega a quantidade de processos por tempo
			*/
			$this->processPerTime();

			/**
			* Carrega o timing da importacao sem o NEXT
			*/
			$imports['timing'] = $this->Timing->find('list', array('fields' => array('Timing.time', 'Timing.description'), 'conditions' => array('Timing.time NOT' => null, 'Timing.id NOT' => TUNING_LOAD_NEXT_REGISTER)));

			/**
			* Carrega o timing do NEXT da importacao
			*/
			$imports['timing_next'] = $this->Timing->find('first', array('fields' => array('Timing.time', 'Timing.description'), 'conditions' => array('Timing.time NOT' => null, 'Timing.id' => TUNING_LOAD_NEXT_REGISTER)));

			/**
			* Carrega as tableas que estao sendo alimentadas
			*/
			$imports['statistics'] = $this->statistics;

		}

		if ($this->RequestHandler->isAjax()) {
			echo json_encode($imports);
		}

		/**
		* Carrega as variaveis de ambiente
		*/
		$this->set(compact('imports', 'uf'));
	}	

	private function elapsedTimes(){
		$startTime = $this->statistics['entities']['start_time'];
		$now = time();
	    $elapsed = $now - $startTime;

	    $hour_day = 24;
	    $minuts_day = 1440;

		$day = str_pad(floor($elapsed/DAY), 2, '0', STR_PAD_LEFT);
		$hour = str_pad(floor(($elapsed/HOUR) - ($day*$hour_day)), 2, '0', STR_PAD_LEFT);
		$min = str_pad(floor(($elapsed/MINUTE) - (($day*$minuts_day) + ($hour*MINUTE))), 2, '0', STR_PAD_LEFT);
		$sec = str_pad(floor($elapsed - (($day*DAY) + ($hour*HOUR) + ($min*MINUTE))), 2, '0', STR_PAD_LEFT);

		$map = array(
			'day' => $day,
			'hour' => $hour,
			'min' => $min,
			'sec' => $sec,
			);

		return $map;
	}

	private function remainingTimes(){
		$done = ($this->statistics['entities']['success'] + $this->statistics['entities']['fails']);
		$now = time();
	    $rate = ($now - $this->statistics['entities']['start_time']) / $done;
	    $left = $this->statistics['entities']['extracted'] - $done;
	    $eta = round($rate * $left, 2);

	    $hour_day = 24;
	    $minuts_day = 1440;

		$day = str_pad(floor($eta/DAY), 2, '0', STR_PAD_LEFT);
		$hour = str_pad(floor(($eta/HOUR) - ($day*$hour_day)), 2, '0', STR_PAD_LEFT);
		$min = str_pad(floor(($eta/MINUTE) - (($day*$minuts_day) + ($hour*MINUTE))), 2, '0', STR_PAD_LEFT);
		$sec = str_pad(floor($eta - (($day*DAY) + ($hour*HOUR) + ($min*MINUTE))), 2, '0', STR_PAD_LEFT);

		$map = array(
			'day' => $day,
			'hour' => $hour,
			'min' => $min,
			'sec' => $sec,
			);

		return $map;
	}

	private function processPerTime(){
		$startTime = $this->statistics['entities']['start_time'];
		$now = time();
	    $elapsed = $now - $startTime;
	    $sec = floor($elapsed);
	    $min = ($elapsed / MINUTE);
	    $hour = ($elapsed / HOUR);
	    $day = ($elapsed / DAY);

		foreach ($this->statistics as $k => $v) {
			$processed = ($v['success'] + $v['fails']);
			if($sec){
				$this->statistics[$k]['process_per_sec'] = round($processed / $sec);
				if(!$min){
					$this->statistics[$k]['process_per_min'] = $processed;
				}else{
					$this->statistics[$k]['process_per_min'] = round($processed / $min);	
				}

				if(!$hour){
					$this->statistics[$k]['process_per_hour'] = $processed;
				}else{
					$this->statistics[$k]['process_per_hour'] = round($processed / $hour);	
				}
				
				if(!$day){
					$this->statistics[$k]['process_per_day'] = $processed;
				}else{
					$this->statistics[$k]['process_per_day'] = round($processed / $day);	
				}
				
			}
		}
	}

	private function getElapsed(){
		$map = $this->elapsedTimes();
		$elapsed = "{$map['hour']}:{$map['min']}:{$map['sec']}";
		if($map['day'] != '00'){
			$elapsed = "{$map['day']}d {$map['hour']}:{$map['min']}:{$map['sec']}";
		}	

		return $elapsed;	
	}

	private function getRemaining(){
		$map = $this->remainingTimes();
		$elapsed = "{$map['hour']}:{$map['min']}:{$map['sec']}";
		if($map['day'] != '00'){
			$elapsed = "{$map['day']}d {$map['hour']}:{$map['min']}:{$map['sec']}";
		}	

		return $elapsed;	
	}
}
