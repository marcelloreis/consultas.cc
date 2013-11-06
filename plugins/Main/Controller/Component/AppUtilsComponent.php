<?php
/**
 * Application level Component
 *
 * Este arquivo contem todas as funcoes relacionadas ao Calendario do Google.
 *
 * @link          https://developers.google.com/google-apps/calendar/v3/reference/
 * @package       app.Controller.Component
 */
App::uses('Component', 'Controller');

/**
 * Application Component
 *
 * O componente "AppCalendar" contem todas as regras de negocio 
 * necessarias para manipular o calendario da conta google associada ao sistema
 */
class AppUtilsComponent extends Component {

	private $controller;

	/**
	* Método startup
	*
	* O método startup é chamado depois do método beforeFilter do controle, 
	* mas antes do controller executar a action corrente.
	*
	* Aqui serao carregados todos os atributos do componente
	*/
	public function startup(Controller $controller){
		/**
		* Carrega o controller
		*/
		$this->controller = $controller;

		parent::startup($controller);
	}

	
	/**
	* Método xml2array
	* Transforma uma string XML em array
	* recebe uma string XML bem formada (well-formed) e retorna uma array. 
	*
	* @param XML $xml
	* @return array
	*/
	public function xml2array($xml){
		return json_decode(json_encode((array)simplexml_load_string($xml)),1);
	}	

	/**
	* Método num2db
	* Retorna o valor passador por parametro no formado de banco
	* Ex.: $valor = $this->AppUtils->num2db('1.000,00');
	* No exemplo acima, a variavel $valor tera o numero formatado como: 1000.00
	*/
	public function num2db($number){
		if(strstr($number, ',')){
			return str_replace(',', '.', str_replace('.', '', $number));
		}
	}

	/**
	* Método num2br
	* Retorna o valor passador por parametro no formado de Real Brasileiro
	* Ex.: $valor = $this->AppUtils->num2br('1000.00');
	* No exemplo acima, a variavel $valor tera o numero formatado como: 1.000,00
	*/
	public function num2br($number){
		return number_format($number, 2, ',', '.');
	}

	/**
	* Método num2qt
	* Retorna o valor passador por parametro no quantitativo
	* Ex.: $valor = $this->AppUtils->num2qt('1000000');
	* No exemplo acima, a variavel $valor tera o numero formatado como: 1.000.000
	*/
	public function num2qt($number){
		return number_format($number, 0, '', '.');
	}
	
	/**
	* Método dt2br
	* Transforma uma data no formato americado para o formato brasileiro
	* Ex.: $data = $this->AppUtils->dt2br('20130130');
	* No exemplo acima, a variavel $data tera o a data formatada como: 30/01/2013
	*
	* @param string $date|eua/db
	* @return string $date|br
	*/
	public function dt2br($date=false, $hours=false){
		$date = ($date)?$date:date('Y-m-d');
		//Formata a data caso ela nao esteja formatada
		if(!preg_match('/[\-\/\.]/si', $date)){
			$data = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
		}

		if($hours){
			$date = date('d/m/Y H:i:s', strtotime($date));
		}else{
			$date = date('d/m/Y', strtotime($date));
		}

		return $date;
	}

	/**
	* Método dt2db
	* Quebra a data para remontar no formato para inserção do banco de dados
	* Ex.: $data = $this->AppUtils->dt2db('31/01/2013');
	* No exemplo acima, a variavel $data tera o a data formatada como: yyyy-mm-dd [hh:ii:ss]
	*
	* @param string $date|br
	* @return string $date|eua/db
	*/
	public function dt2db($date=false, $hours=false){
		if(preg_match('%(0[1-9]|[12][0-9]|3[01])[\./-]?(0[1-9]|1[012])[\./-]?([12][0-9]{3})([ ].*)?([01][0-9]|2[03]:[05][09])?%si', $date, $dt)){
			$date = $dt[3] . '-' . $dt[2] . '-' . $dt[1];
			/**
			 * Verifica se a data contem hh:ii:ss, caso tenha é concatenado a data
			 */
			if (isset($dt[4])){
				$date .= ' ' . $dt[4];
			}
		}

		return $date;
	}

	/**
	* Método formatCpf
	* Formata os numeros passados pelo parametro nos padroes de CPF
	* Ex.: $cpf = $this->AppUtils->formatCPF('123456789');
	* No exemplo acima, a variavel $cpf tera o cpf formatado como: 001.234.567-89
	*
	* @param string $cpf
	* @return string $mask
	*/
	public function formatCPF($cpf){
		// Elimina possivel mascara
		$cpf = preg_replace('[^0-9]', '', $cpf);
		$cpf = str_pad(substr($cpf, -11), 11, '0', STR_PAD_LEFT);
	 
		// Verifica se o numero de digitos informados é igual a 11 
		if (strlen($cpf) != 11) {
		    return false;
		}

		/**
		* Aplica a mascara ao numero
		*/
		$mask = '###.###.###-##'; 
		$index = -1;
		for ($i=0; $i < strlen($mask); $i++){
			if ($mask[$i]=='#'){
				$mask[$i] = $cpf[++$index];
			} 
		}

		return $mask;
	}


	/**
	* Método removeAcentos
	* Remove todos os caracteres com acentos do texto passado pelo parametro
	* Ex.: $desc = $this->AppUtils->removeAcentos('Méto que remóvê acêntòs');
	* No exemplo acima, a variavel $desc tera o a texto formatada como: Metodo que remove acentos
	*
	* @param string $txt|com acentos
	* @return string $txt|sem acentos
	*/
	public function removeAcentos($txt){
		$txt = preg_replace("/á|à|â|ã|ª/s", "a", $txt);
		$txt = preg_replace("/é|è|ê/s", "e", $txt);
		$txt = preg_replace("/ó|ò|ô|õ|º/s", "o", $txt);
		$txt = preg_replace("/ú|ù|û/s", "u", $txt);
		$txt = str_replace("ç","c",$txt);

		$txt = preg_replace("/Á|À|Â|Ã|ª/s", "A", $txt);
		$txt = preg_replace("/É|È|Ê/s", "E", $txt);
		$txt = preg_replace("/Ó|Ò|Ô|Õ|º/s", "O", $txt);
		$txt = preg_replace("/Ú|Ù|Û/s", "U", $txt);
		$txt = str_replace("Ç","C",$txt);		

		return $txt;
	}	
}