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
class ImportsController extends AppController {
	public $uses = array(
		"Import", 
		"NattFixoTelefone", 
		"NattFixoPessoa", 
		"NattFixoEndereco",
		"Ilandline",
		"Ientity",
		"Izipcode",
		"Iaddress",
		"Iassociation",
		"Settings",
		"Counter"
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
	private $counters;


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
		foreach ($this->Counter->findAllByActive(true) as $k => $v) {
			$this->counters[$v['Counter']['table']] = $v['Counter'];
		}		

	}	

	/**
	* Método run
	* Este método carrega as estatisticas da importacao vigente
	*
	* @return void
	*/
	public function statistics(){
		/**
		* Carrega a quantidade de registros a serem importados
		*/
		$imports['records_to_process'] = $this->counters['entities']['extracted'];

		/**
		* Carrega a quantidade de registros ja processados
		*/
		$imports['records_processed'] = ($this->counters['entities']['success'] + $this->counters['entities']['fails']);

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
		* Carrega as tableas que estao sendo alimentadas
		*/
		$imports['counters'] = $this->counters;

		/**
		* Carrega as variaveis de ambiente
		*/
		$this->set(compact('imports'));
	}	

	private function elapsedTimes(){
		$startTime = $this->counters['entities']['start_time'];
		$now = time();
	    $elapsed = $now - $startTime;
		$day = str_pad(floor($elapsed/86400), 2, '0', STR_PAD_LEFT);
		$hour = str_pad(floor(($elapsed/3600) - ($day*24)), 2, '0', STR_PAD_LEFT);
		$min = str_pad(floor(($elapsed/60) - (($day*1440) + ($hour*60))), 2, '0', STR_PAD_LEFT);
		$sec = str_pad(floor($elapsed - (($day*86400) + ($hour*3600) + ($min*60))), 2, '0', STR_PAD_LEFT);

		$map = array(
			'day' => $day,
			'hour' => $hour,
			'min' => $min,
			'sec' => $sec,
			);

		return $map;
	}

	private function remainingTimes(){
		$done = ($this->counters['entities']['success'] + $this->counters['entities']['fails']);
		$now = time();
	    $rate = ($now - $this->counters['entities']['start_time']) / $done;
	    $left = $this->counters['entities']['extracted'] - $done;
	    $eta = round($rate * $left, 2);

		$day = str_pad(floor($eta/86400), 2, '0', STR_PAD_LEFT);
		$hour = str_pad(floor(($eta/3600) - ($day*24)), 2, '0', STR_PAD_LEFT);
		$min = str_pad(floor(($eta/60) - (($day*1440) + ($hour*60))), 2, '0', STR_PAD_LEFT);
		$sec = str_pad(floor($eta - (($day*86400) + ($hour*3600) + ($min*60))), 2, '0', STR_PAD_LEFT);

		$map = array(
			'day' => $day,
			'hour' => $hour,
			'min' => $min,
			'sec' => $sec,
			);

		return $map;
	}

	private function processPerTime(){
		$startTime = $this->counters['entities']['start_time'];
		$now = time();
	    $elapsed = $now - $startTime;
	    $sec = floor($elapsed);
	    $min = floor($elapsed / 60);
	    $hour = floor($elapsed / 3600);
	    $day = floor($elapsed / 86400);

		foreach ($this->counters as $k => $v) {
			$processed = ($v['success'] + $v['fails']);
			if($sec != '00'){
				$this->counters[$k]['process_per_sec'] = floor($processed / $sec);
				if(!$min){
					$this->counters[$k]['process_per_min'] = $processed;
				}else{
					$this->counters[$k]['process_per_min'] = floor($processed / $min);	
				}

				if(!$hour){
					$this->counters[$k]['process_per_hour'] = $processed;
				}else{
					$this->counters[$k]['process_per_hour'] = floor($processed / $hour);	
				}
				
				if(!$day){
					$this->counters[$k]['process_per_day'] = $processed;
				}else{
					$this->counters[$k]['process_per_day'] = floor($processed / $day);	
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
