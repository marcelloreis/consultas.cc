<?php
/**
 * Application level Component
 *
 * Este componente é responsavel por todas as funcoes de importacao do banco de dados
 *
 * @link          https://developers.google.com/google-apps/calendar/instantiate
 * @package       app.Controller.Component
 */
App::uses('Component', 'Controller');

/**
 * Application Component
 *
 */
class AppImportComponent extends Component {
	private $Log;
	private $City;
	private $Iaddress;
	private $ModelCounter;
	private $Timing;
	private $timing_avg;
	private $time_start;
	private $time_end;
	private $time_id;
	private $time_desc;
	public $sizeReload;
	public $counter;

	public function __construct() {
	    $this->Log = ClassRegistry::init('LogsImport');
	    $this->City = ClassRegistry::init('City');
	    $this->Iaddress = ClassRegistry::init('Iaddress');
	    $this->ModelCounter = ClassRegistry::init('Counter');
	    $this->Timing = ClassRegistry::init('Timing');
	}	

	/**
	* Gera o hash do nome passado por parametro
	*/
	public function getHash($name, $part=null, $rm_siglas=true){
		/**
		* Inicializa a variavel $hash que guardara todos os hashs do nome
		*/
		$hash = array();

		/**
		* Verifica se o nome passado passado por parametro é valido, caso contrario retorna 0
		*/
		if(is_null($name) || empty($name)){
			return 0;
		}

		/**
		* Remove todos os acentos do nome
		*/
		$name = trim($this->removeAcentos($name));

		/**
		* Padroniza o hash por letras minusculas
		*/
		$name = strtolower($name);

		if($rm_siglas){
			/**
			* Remove as siglas LTDA e suas derivadas
			*/
			$name = preg_replace('/ltda|ltdame|meltda|ltda-me|ltda_me|ltda\/me/si', '', $name);

			/**
			* Remove os nomes irrelevantes como: junior, filho, neto
			*/
			$name = preg_replace('/junior$|filho$|neto$|sobrinho$/si', '', $name);

			/**
			* Remove todas as palavras com menos de 4 letrase que estejam no meio do nome do nome
			* onde todas as preposicoes serao excluidas  de | do | da | dos | das 
			*/
			$name = preg_replace('/ [a-z]{1,3} /si', ' ', $name);

			/**
			* Remove todas as palavas com menos de 4 letras que possam estar no final da frase
			*/
			$name = preg_replace('/ [a-z]{1,3}$/si', '', $name);
		}

		/**
		* explode o nome para gerar os hashes por parte
		*/
		$map_name = explode(' ', $name);
		foreach ($map_name as $k => $v) {
			$hash["h" . ($k+1)] = $this->__hash($v);
		}

		/**
		* Completa o array com a quantidade de hash maxima permitida no sistema (5 partes)
		*/
		for ($i=(count($hash) + 1); $i <= LIMIT_HASH; $i++) { 
			$hash["h{$i}"] = 0;
		}

		/**
		* Carrega o ultimo hash do nome
		*/
		$name_last = isset($map_name[(count($map_name) - 1)])?$map_name[(count($map_name) - 1)]:null;
		$h_last = $this->__hash($name_last);

		/**
		* Carrega o penultimo hash do nome
		*/
		$name_last_pre = isset($map_name[(count($map_name) - 2)])?$map_name[(count($map_name) - 2)]:null;
		$h_last_pre = $this->__hash($name_last_pre);

		/**
		* Gera o hash do nome completo (sem espaço entre os nomes)
		*/
		$hash['h_all'] = $this->__hash(str_replace(' ', '', $name));

		/**
		* Gera o hash do primeiro e do ultimo nome juntos (sem espaço entre os nomes)
		*/
		if(isset($map_name[0]) && $name_last && ($map_name[0] != $name_last)){
			$hash['h_first_last'] = $this->__hash("{$map_name[0]}{$name_last}");
		}else if(isset($map_name[0])){
			$hash['h_first_last'] = $this->__hash("{$map_name[0]}");
		}else{
			$hash['h_first_last'] = 0;
		}

		/**
		* Gera o hash do ultimo nome
		*/
		if($h_last){
			$hash['h_last'] = $h_last;
		}else if(isset($map_name[0])){
			$hash['h_last'] = $this->__hash("{$map_name[0]}");
		}else{
			$hash['h_last'] = 0;
		}

		/**
		* Gera o hash do primeiro e segundo nomes (sem espaço entre os nomes)
		*/
		if(isset($map_name[0]) && isset($map_name[1]) && ($map_name[0] != $map_name[1])){
			$hash['h_first1_first2'] = $this->__hash("{$map_name[0]}{$map_name[1]}");
		}else if(isset($map_name[0])){
			$hash['h_first1_first2'] = $this->__hash("{$map_name[0]}");
		}else{
			$hash['h_first1_first2'] = 0;
		}

		/**
		* Gera o hash dos dois ultimos nomes
		*/
		if($name_last_pre && $name_last){
			$hash['h_last1_last2'] = $this->__hash("{$name_last_pre}{$name_last}");
		}else if($name_last_pre){
			$hash['h_last1_last2'] = $this->__hash("{$name_last_pre}");
		}else if(isset($map_name[0])){
			$hash['h_last1_last2'] = $this->__hash("{$map_name[0]}");
		}

		/**
		* Retorna uma parte em especial do hash
		*/
		if($part && array_key_exists($part, $hash)){
			return $hash[$part];
		}

		return $hash;
	}

	/**
	* Retorna o tipo do documento passado por parametro
	*/
	/**
	* Retorna o tipo do documento passado por parametro
	*/
	public function getTypeDoc($doc, $name=null, $mother_name=null, $birthday=null){
		/**
		* Inicializa a variavel $type como tipo invalido
		*/
		$type = TP_INVALID;

		/**
		* Verifica se o documento passado consiste como CPF
		*/
		if($this->validateCpf($doc)){
			$type = TP_CPF;
		}
		/**
		* Verifica se o documento passado consiste como CNPJ
		*/
		if($this->validateCnpj($doc)){
			/**
			* Verifica se o documento passado é ambiguo
			*/
			if($type == TP_CPF){
				$type = TP_AMBIGUO;

				/**
				* Tenta descobrir se o documento é CPF verificando se existe o nome da mae
				*/
				if(($mother_name && $mother_name != '') || ($birthday && $birthday != '')){
					$type = TP_CPF;
				}

			}else{
				$type = TP_CNPJ;
			}
		}
		/**
		* Tenta descobrir se o documento é CNPJ atraves do nome
		*/
		if(($type == TP_INVALID || $type == TP_AMBIGUO) && $name){
			if(preg_match('/[ _-]ltda$|[ _-]me$|[ _-]sa$|[ _-]exp$|[ _-]s\/a$|^ed |^ass /si', strtolower($this->clearName($name)))){
				$type = TP_CNPJ;
			}

			$compani_names = array(
				'advogado',
				'advogados',
				'agropecuaria',
				'artigos',
				'artigos',
				'assembleia',
				'associacao',
				'associados',
				'auto',
				'banco',
				'bcodo',
				'brasil',
				'casa',
				'centro',
				'centro',
				'cia',
				'clinica',
				'comercial',
				'comercio',
				'companhia',
				'comunicacao',
				'condominio',
				'conselho',
				'construtora',
				'coop',
				'copiadora',
				'departamento',
				'distribuidora',
				'drogaria',
				'edificacoes',
				'empresa',
				'engenharia',
				'fabrica',
				'farmacia',
				'federacao',
				'fundacao',
				'hospital',
				'igreja',
				'industria',
				'inspetoria',
				'instituto',
				'irmaos',
				'justica',
				'laboratorio',
				'loja',
				'lojas',
				'mecanica',
				'ministerio',
				'oficina',
				'organizacao',
				'organizacoes',
				'otica',
				'padaria',
				'policia',
				'poder',
				'judiciario',
				'prefeitura',
				'restaurante',
				'secretaria',
				'servico',
				'sind',
				'supermercados',
				'tecnologia',
				'tribunal',
				'universidade',
				'viacao',
				'vidracaria',
			);
			$first_name = $this->removeAcentos(strtolower(substr($name, 0, strpos("{$name} ", ' '))));
			if(in_array(strtolower($this->clearname($first_name)), $compani_names)){
				$type = TP_CNPJ;
			}
		}		

		return $type;
	}

	/**
	* Trata e limpa o nome passado por parametro
	*/
	public function clearName($name, $clearNumber=true){
		/**
		* Verifica se o texto é consistente
		*/
		if(!preg_match('/[a-z]*/si', strtolower($name))){
			$name = null;
		}else{
			/**
			* Remove qualquer caracter do nome que nao seja letras
			*/
			$name = ucwords(strtolower(trim($this->removeAcentos($name))));
			if($clearNumber){
				$name = trim(preg_replace('/[^a-zA-Z ]/si', '', $name));
			}

			/**
			* Altera para minusculas todas as palavras com menos de 4 letrase que estejam no meio do nome
			* todas as preposicoes serao alteradas  de | do | da | dos | das 
			*/
			$name = preg_replace('/( [a-z]{1,3} )/ie', 'strtolower("$1")', $name);			
		}


		return $name;
	}

	/**
	* Retorna o sexo da entidade
	*/
	public function getGender($type_doc, $name){
		/**
		* Retorna null caso o nome nao seja valido
		*/
		if(empty($name)){
			return null;
		}

		/**
		* Inicializa a variavel $gender com null
		*/
		$gender = null;

		/**
		* Verifica se o tipo da entidade é CPF para gerar o seco da entidade
		*/
		if($type_doc != TP_CNPJ){
			/**
			* Aplica regras para tentar descobrir o sexo da entidade apartir do primeiro nome
			*/
			$first_name = $this->clearName(substr($name, 0, strpos("{$name} ", ' ')));

			/**
			* Tenta descobrir o sexo da entidade a partir de varias combinacoes alfabeticas
			*/
			$gender = $this->gender($first_name);
			break;
		}

		return $gender;
	}

	/**
	* Retorna se o nome passado pelo parametro é masculino ou feminino a partir de varias combinacoes alfabeticas
	*/
	private function gender($n){    
		$out = MALE;

	    if (preg_match('/a$/si', $n)) {
	        $out = FEMALE;
	        if (
	        		preg_match('/wilba$|rba$|vica$|milca$|meida$|randa$/si', $n)
	               || preg_match('/uda$|rrea$|afa$|^ha$|cha$|oha$|apha$/si', $n)
	               || preg_match('/natha$|^elia$|rdelia$|remia$|aja$/si', $n)
	               || preg_match('/rja$|aka$|kka$|^ala$|gla$|tila$|vila$/si', $n)
	               || preg_match('/cola$|orla$|nama$|yama$|inima$|jalma$/si', $n)
	               || preg_match('/nma$|urma$|zuma$|gna$|tanna$|pna$/si', $n)
	               || preg_match('/moa$|jara$|tara$|guara$|beira$|veira$/si', $n)
	               || preg_match('/kira$|uira$|pra$|jura$|mura$|tura$/si', $n)
	               || preg_match('/asa$|assa$|ussa$|^iata$|onata$|irata$/si', $n)
	               || preg_match('/leta$|preta$|jota$|ista$|aua$|dua$/si', $n)
	               || preg_match('/hua$|qua$|ava$|dva$|^iva$|silva$|ova$/si', $n)
	               || preg_match('/rva$|wa$|naya$|ouza$/si', $n)
               ){
	        	$out = MALE;
               }
	    } else if (preg_match('/b$/si', $n)) {
	        $out = MALE;
	        if (preg_match('/inadab$/si', $n)){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/c$/si', $n)) {
	        $out = MALE;
	        if (preg_match('/lic$|tic$/si', $n)){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/d$/si', $n)) {
	        $out = MALE;
	        if (preg_match('/edad$|rid$/si', $n)){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/e$/si', $n)) {
	        $out = FEMALE;
	        if(
	        	preg_match('/dae$|jae$|kae$|oabe$|ube$|lace$|dece$/si', $n)
                || preg_match('/felice$|urice$|nce$|bruce$|dade$|bede$/si', $n)
                || preg_match('/^ide$|^aide$|taide$|cide$|alide$|vide$/si', $n)
                || preg_match('/alde$|hilde$|asenilde$|nde$|ode$|lee$/si', $n)
                || preg_match('/^ge$|ege$|oge$|rge$|uge$|phe$|bie$/si', $n)
                || preg_match('/elie$|llie$|nie$|je$|eke$|ike$|olke$/si', $n)
                || preg_match('/nke$|oke$|ske$|uke$|tale$|uale$|vale$/si', $n)
                || preg_match('/cle$|rdele$|gele$|tiele$|nele$|ssele$/si', $n)
                || preg_match('/uele$|hle$|tabile$|lile$|rile$|delle$/si', $n)
                || preg_match('/ole$|yle$|ame$|aeme$|deme$|ime$|lme$/si', $n)
                || preg_match('/rme$|sme$|ume$|yme$|phane$|nane$|ivane$/si', $n)
                || preg_match('/alvane$|elvane$|gilvane$|ovane$|dene$/si', $n)
                || preg_match('/ociene$|tiene$|gilene$|uslene$|^rene$/si', $n)
                || preg_match('/vaine$|waine$|aldine$|udine$|mine$/si', $n)
                || preg_match('/nine$|oine$|rtine$|vanne$|renne$|hnne$/si', $n)
                || preg_match('/ionne$|cone$|done$|eone$|fone$|ecione$/si', $n)
                || preg_match('/alcione$|edione$|hione$|jone$|rone$/si', $n)
                || preg_match('/tone$|rne$|une$|ioe$|noe$|epe$|ipe$/si', $n)
                || preg_match('/ope$|ppe$|ype$|sare$|bre$|dre$|bere$/si', $n)
                || preg_match('/dere$|fre$|aire$|hire$|ore$|rre$|tre$/si', $n)
                || preg_match('/dse$|ese$|geise$|wilse$|jose$|rse$/si', $n)
                || preg_match('/esse$|usse$|use$|aete$|waldete$|iodete$/si', $n)
                || preg_match('/sdete$|aiete$|nisete$|ezete$|nizete$/si', $n)
                || preg_match('/dedite$|uite$|lte$|ante$|ente$|arte$/si', $n)
                || preg_match('/laerte$|herte$|ierte$|reste$|aue$/si', $n)
                || preg_match('/gue$|oue$|aque$|eque$|aique$|inique$/si', $n)
                || preg_match('/rique$|lque$|oque$|rque$|esue$|osue$/si', $n)
                || preg_match('/ozue$|tave$|ive$|ove$|we$|ye$|^ze$/si', $n)
                || preg_match('/aze$|eze$|uze$/si', $n)
        	){
				$out = MALE;
	        } 
	    } else if (preg_match('/f$/si', $n)) {
	        $out = MALE;
	    } else if (preg_match('/g$/si', $n)) {
	        $out = MALE;
	        if(preg_match('/eig$|heng$|mping$|bong$|jung$/si', $n)){
	        	$out = FEMALE;
	        }
	        
	    } else if (preg_match('/h$/si', $n)) {
	        $out = MALE;
	        if(preg_match('/kah$|nah$|rah$|sh$|beth$|reth$|seth$/si', $n) || preg_match('/lizeth$|rizeth$|^edith$|udith$|ruth$/si', $n)){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/i$/si', $n)) {
	        $out = MALE;
	        if(
	        	preg_match('/elai$|anai$|onai$|abi$|djaci$|glaci$/si', $n)
               || preg_match('/maraci$|^iraci$|diraci$|loraci$|ildeci$/si', $n) 
               || preg_match('/^neci$|aici$|arici$|^elci$|nci$|oci$/si', $n) 
               || preg_match('/uci$|kadi$|leidi$|ridi$|hudi$|hirlei$/si', $n) 
               || preg_match('/sirlei$|^mei$|rinei$|ahi$|^ji$|iki$/si', $n) 
               || preg_match('/isuki$|^yuki$|gali$|rali$|ngeli$|ieli$/si', $n) 
               || preg_match('/keli$|leli$|neli$|seli$|ueli$|veli$/si', $n) 
               || preg_match('/zeli$|ili$|helli$|kelli$|arli$|wanderli$/si', $n) 
               || preg_match('/hami$|iemi$|oemi$|romi$|tmi$|ssumi$/si', $n) 
               || preg_match('/yumi$|zumi$|bani$|iani$|irani$|sani$/si', $n) 
               || preg_match('/tani$|luani$|^vani$|^ivani$|ilvani$/si', $n) 
               || preg_match('/yani$|^eni$|ceni$|geni$|leni$|ureni$/si', $n) 
               || preg_match('/^oseni$|veni$|zeni$|cini$|eini$|lini$/si', $n) 
               || preg_match('/jenni$|moni$|uni$|mari$|veri$|hri$/si', $n) 
               || preg_match('/aori$|ayuri$|lsi$|rsi$|gessi$|roti$/si', $n) 
               || preg_match('/sti$|retti$|uetti$|aui$|iavi$|^zi$/si', $n) 
               || preg_match('/zazi$|suzi$/si', $n)
        	){
				$out = FEMALE;
	        }
	    } else if (preg_match('/j$/si', $n)) {
	        $out = MALE;
	    } else if (preg_match('/k$/si', $n)) {
	        $out = MALE;
	        if(preg_match('/nak$|lk$/si', $n)){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/l$/si', $n)) {
	        $out = MALE;
	        if(
	        	preg_match('/mal$|^bel$|mabel$|rabel$|sabel$|zabel$/si', $n)
               || preg_match('/achel$|thel$|quel$|gail$|lenil$|mell$/si', $n) 
               || preg_match('/ol$/si', $n)
        	){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/m$/si', $n)) {
	        $out = MALE;
	        if(preg_match('/liliam$|riam$|viam$|miram$|eem$|uelem$/si', $n) || preg_match('/mem$|rem$/si', $n)){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/n$/si', $n)) {
	        $out = MALE;
	        if(
	        	preg_match('/lilian$|lillian$|marian$|irian$|yrian$/si', $n)
               || preg_match('/ivian$|elan$|rilan$|usan$|nivan$|arivan$/si', $n) 
               || preg_match('/iryan$|uzan$|ohen$|cken$|elen$|llen$/si', $n) 
               || preg_match('/men$|aren$|sten$|rlein$|kelin$|velin$/si', $n) 
               || preg_match('/smin$|rin$|istin$|rstin$|^ann$|ynn$/si', $n) 
               || preg_match('/haron$|kun$|sun$|yn$/si', $n)

        	){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/o$/si', $n)) {
	        $out = MALE;
	        if(
	        	preg_match('/eicao$|eco$|mico$|tico$|^do$|^ho$/si', $n)
               || preg_match('/ocio$|ako$|eko$|keiko$|seiko$|chiko$/si', $n)
               || preg_match('/shiko$|akiko$|ukiko$|miko$|riko$|tiko$/si', $n)
               || preg_match('/oko$|ruko$|suko$|yuko$|izuko$|uelo$/si', $n)
               || preg_match('/stano$|maurino$|orro$|jeto$|mento$/si', $n)
               || preg_match('/luo$/si', $n)
        	){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/p$/si', $n)) {
	        $out = MALE;
	        if(preg_match('/yip$/si', $n)){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/r$/si', $n)) {
	        $out = MALE;
	        if(
	        	preg_match('/lar$|lamar$|zamar$|ycimar$|idimar$/si', $n)
               || preg_match('/eudimar$|olimar$|lsimar$|lzimar$|erismar$/si', $n)
               || preg_match('/edinar$|iffer$|ifer$|ather$|sther$/si', $n)
               || preg_match('/esper$|^ester$|madair$|eclair$|olair$/si', $n)
               || preg_match('/^nair$|glacir$|^nadir$|ledir$|^vanir$/si', $n)
               || preg_match('/^evanir$|^cenir$|elenir$|zenir$|ionir$/si', $n)
               || preg_match('/fior$|eonor$|racyr$/si', $n)
        	){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/s$/si', $n)) {
	        $out = MALE;
	        if(
	        	preg_match('/unidas$|katias$|rces$|cedes$|oides$/', $n)
               || preg_match('/aildes$|derdes$|urdes$|leudes$|iudes$/si', $n) 
               || preg_match('/irges$|lkes$|geles$|elenes$|gnes$/si', $n) 
               || preg_match('/^ines$|aines$|^dines$|rines$|pes$/si', $n) 
               || preg_match('/deres$|^mires$|amires$|ores$|neves$/si', $n) 
               || preg_match('/hais$|lais$|tais$|adis$|alis$|^elis$/si', $n) 
               || preg_match('/ilis$|llis$|ylis$|ldenis$|annis$|ois$/si', $n) 
               || preg_match('/aris$|^cris$|^iris$|miris$|siris$/si', $n) 
               || preg_match('/doris$|yris$|isis$|rtis$|zis$|heiros$/si', $n) 
               || preg_match('/dys$|inys$|rys$/si', $n)
        	){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/t$/si', $n)) {
	        $out = MALE;
	        if(preg_match('/bet$|ret$|^edit$|git$|est$|nett$|itt$/si', $n)){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/u$/si', $n)) {
	        $out = MALE;
	        if(preg_match('/^du$|alu$|^miharu$|^su$/si', $n)){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/v$/si', $n)) {
	        $out = MALE;
	    } else if (preg_match('/w$/si', $n)) {
	        $out = MALE;
	    } else if (preg_match('/x$/si', $n)) {
	        $out = MALE;
	    } else if (preg_match('/y$/si', $n)) {
	        $out = MALE;
	        if(
	        	preg_match('/may$|anay$|ionay$|lacy$|^aracy$|^iracy$/', $n)
               || preg_match('/doracy$|vacy$|aricy$|oalcy$|ncy$|nercy$/si', $n) 
               || preg_match('/ucy$|lady$|hedy$|hirley$|raney$|gy$/si', $n) 
               || preg_match('/ahy$|rothy$|taly$|aely$|ucely$|gely$/si', $n) 
               || preg_match('/kely$|nely$|sely$|uely$|vely$|zely$/si', $n) 
               || preg_match('/aily$|rily$|elly$|marly$|mony$|tamy$|iany$/si', $n) 
               || preg_match('/irany$|sany$|uany$|lvany$|wany$|geny$/si', $n) 
               || preg_match('/leny$|ueny$|anny$|mary$|imery$|smery$/si', $n) 
               || preg_match('/iry$|rory$|isy$|osy$|usy$|ty$/si', $n)
        	){
	        	$out = FEMALE;
	        }
	    } else if (preg_match('/z$/si', $n)) {
	        $out = MALE;
	        if(preg_match('/^inez$|rinez$|derez$|liz$|riz$|uz$/si', $n)){
	        	$out = FEMALE;
	        }
	    }

	    return $out;
	}

	/**
	* Trata a data de aniversario passada por parametro
	*/
	public function getBirthday($date){
		/**
		* Verifica se a data é consistente
		*/
		if(!preg_match('/[12][0-9]{3}-[01][0-9]-[0-3][0-9]$/si', $date)){
			$date = null;
		}

		return $date;
	}

	/**
	* Trata a data de atualizacao passada por parametro e retorna somente o ANO da atualizacao
	*/
	public function getUpdated($date){
		$updated = substr($date, 0, 4);

		/**
		* Verifica se a data é consistente
		*/
		if(!preg_match('/^[12][0-9]{3}$/si', $updated)){
			$updated = null;
		}


		return $updated;
	}

	/**
	* Trata o CEP passado por parametro
	*/
	public function getZipcode($zipcode, $zipcode_aux=false){
		/**
		* Remove tudo que nao for numeros do CEP
		*/
		$zipcode = preg_replace('/[^0-9]/si', '', $zipcode);

		/**
		* Completa com zeros a esquerda ate completar a quantidade de numeros do CEP
		*/
		$zipcode = str_pad(substr($zipcode, -8), 8, '0', STR_PAD_LEFT);

		/**
		* Verifica se nenhuma das sequências invalidas abaixo 
		* foi digitada.
		*/
		if(preg_match('/0{8}|1{8}|2{8}|3{8}|4{8}|5{8}|6{8}|7{8}|8{8}|9{8}/si', $zipcode)){
			$zipcode = -1;
		}

		/**
		* Verifica se o CEP é inconsistente
		*/
		if(!preg_match('/^[0-9]{8}$/si', $zipcode)){
			$zipcode = -1;
		}

		/**
		* Caso o CEP seja invaldo, verifica se foi passado um CEP alternativo e executa as verificacoes em cima dele
		*/
		if(!$zipcode && $zipcode_aux){
			$zipcode = $this->getZipcode($zipcode_aux);
		}

		return $zipcode;
	}

	/**
	* Carrega o ID do estado apartir da string do estado passada por parametro
	*/
	public function getState($state, $state_aux=false){
		/**
		* Carrega todos os estados do pais em uma array com o sigla => cod
		*/
		$map_states = $this->loadStates();

		$state_id = !empty($map_states[strtoupper($state)])?$map_states[strtoupper($state)]:false;

		/**
		* Verifica se o estado informado é invalido
		*/
		if(!is_numeric($state_id)){
			$state_id = null;
		}

		/**
		* Caso o Estado seja invaldo, verifica se foi passado um estado alternativo e executa as verificacoes em cima dele
		*/
		if(!$state_id && $state_aux){
			$state_id = $this->getState($state_aux);
		}

		return $state_id;
	}

	/**
	* Carrega o ID da cidade apartir do nome da cidade e o codigo do estado passados pelo parametro
	*/
	public function getCityId($city, $state_id, $zipcode_id=null){
		/**
		* Inicializa a variavel $city_id com -1
		*/
		$city_id = -1;

		/**
		* Carrega todos os estados do pais em uma array com o sigla => cod
		*/
		$map_states = $this->loadStates();

		/**
		* Verifica se o estado informado é valido
		*/
		if(in_array($state_id, $map_states)){
			/**
			* Remove tudo que nao for letas do nome da cidade
			*/
			$city = preg_replace('/[^a-z ]/si', ' ', strtolower($city));

			/**
			* Remove abreviacoes
			*/
			$city = $this->removeAbreviacoes($city);

			/**
			* Remove todos os caracteres especiais da cidade
			*/
			$city = $this->removeAcentos($city);

			/**
			* Verifica se algum endereço ja foi cadastrado com o mesmo CEP e clona a cidade do endereço
			*/
			if($zipcode_id){
				$hasCity = $this->Iaddress->find('first', array(
					'recursive' => '-1',
					'conditions' => array(
						'zipcode_id' => $zipcode_id,
						'city_id NOT' => null
						)
					));
				if(count($hasCity)){
					$city_id = $hasCity['Iaddress']['city_id'];
				}
			}

			if($city_id <= 0){
				/**
				* Busca pela cidade atravez do nome completo e do estado
				*/
				$hasCity = $this->City->find('first', array(
					'recursive' => '-1',
					'conditions' => array(
						'City.name' => $city,
						'City.state_id' => $state_id
						)
					));
				if(count($hasCity)){
					$city_id = $hasCity['City']['id'];
				}				

				/**
				* Busca pela cidade atravez de partes do nome e do estado
				*/
				if($city_id <= 0){
					$hasCity = $this->City->find('first', array(
						'recursive' => '-1',
						'conditions' => array(
							'City.name like' => "%" . str_replace(' ', '%', preg_replace('/ [a-z]{1,2} /si', ' ', $city)) . "%",
							'City.state_id' => $state_id
							)
						));

					if(count($hasCity)){
						$city_id = $hasCity['City']['id'];
					}
				}
			}
		}

		return $city_id;
	}

	/**
	* Trata o nome da cidade
	*/
	public function getCity($city){
		/**
		* Remove abreviacoes
		*/
		$city = $this->removeAbreviacoes($city);

		/**
		* Altera todas as iniciais para maisuculo
		*/
		$city = $this->clearName($city);

		return $city;
	}

	/**
	* Trata o nome dos bairros
	*/
	public function getNeighborhood($neighborhood){
		/**
		* Remove tudo que nao for letas e numeros do nome do bairro
		*/
		$neighborhood = preg_replace('/[^a-z0-9 ]/si', '', strtolower($neighborhood));

		/**
		* Remove abreviacoes
		*/
		$neighborhood = $this->removeAbreviacoes($neighborhood);

		/**
		* Formata o nome com as primeiras letras em maiusculo
		*/
		$neighborhood = $this->clearName($neighborhood);


		return $neighborhood;
	}

	/**
	* Trata o complemento do endereço
	*/
	public function getComplement($complement, $street=null){
		/**
		* Remove tudo que nao for letas e numeros do nome do bairro
		*/
		$complement = preg_replace('/[^a-z0-9 ]/si', '', strtolower($complement));

		/**
		* Caso o numero nao tenha sido carregado ainda, tenta carrega-lo a partir do nome da rua
		*/
		if(!$complement && $street){
			if(preg_match('/(bl ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(bl. ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(bloco ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(cs ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(cs. ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(ed ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(edificio ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(edf ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(q ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(qu ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(quadra ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(qd ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(lote ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(trav. ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(trav ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(travessia ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(casa ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(andar ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(cx ?.*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(sn)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(ap ?[0-9]*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(apartamento ?[0-9]*)/si', $street, $vet)){
				$complement = $vet[1];
			} else if(preg_match('/(apto ?[0-9]*)/si', $street, $vet)){
				$complement = $vet[1];
			}else{
				if(preg_match('/^([a-z ]*)?(.*)/si', $street, $vet)){
					$street_number = $vet[2];
				}
			}
		}		

		/**
		* Remove abreviacoes
		*/
		$complement = $this->removeAbreviacoes($complement);

		/**
		* Formata o nome com as primeiras letras em maiusculo
		*/
		$complement = $this->clearName($complement, false);

		/**
		* Seta o complemento como null caso nao tenho nenhuma infomracao
		*/
		if(empty($complement)){
			$complement = null;
		}

		return $complement;
	}	

	/**
	* Carrega o Logradouro apartir do nome da rua passado pelo parametro
	*/
	public function getTypeAddress($type_address, $street=false){
		/**
		* Inicializa a variavel $type com null
		*/
		$type = null;

		/**
		* Aplica regras para tentar extrair o logrdoura
		*/
		if(preg_match('/^al\.?.*|alameda .*/si', strtolower($type_address))){
			$type = 'Alameda';
		}
		
		if(preg_match('/^av\.?.*|avenida .*/si', strtolower($type_address))){
			$type = 'Avenida';
		}
		
		if(preg_match('/^b[c]?\.?.*|beco .*/si', strtolower($type_address))){
			$type = 'Beco';
		}
		
		if(preg_match('/^cal[cç]?\.?.*|cal[cç]ada .*/si', strtolower($type_address))){
			$type = 'Calçada';
		}
		
		if(preg_match('/^con[d]?\.?.*|condom[ií]nio .*/si', strtolower($type_address))){
			$type = 'Condomínio';
		}
		
		if(preg_match('/^cj\.?.*|conj\.?.*|conju\.?.*|conjunto .*/si', strtolower($type_address))){
			$type = 'Conjunto';
		}
		
		if(preg_match('/^esc\.?.*|esd\.?.*|escad\.?.*|escadaria .*/si', strtolower($type_address))){
			$type = 'Escadaria';
		}
		
		if(preg_match('/^es[t]?\.?.*|estrada .*/si', strtolower($type_address))){
			$type = 'Estrada';
		}
		
		if(preg_match('/^ga[l]?\.?.*|galeria .*/si', strtolower($type_address))){
			$type = 'Galeria';
		}
		
		if(preg_match('/^jd\.?.*|jardim .*/si', strtolower($type_address))){
			$type = 'Jardim';
		}
		
		if(preg_match('/^l[g]?\.?.*|largo .*/si', strtolower($type_address))){
			$type = 'Largo';
		}
		
		if(preg_match('/^p[cç]?[a]??\.?.*|pra[cç]a .*/si', strtolower($type_address))){
			$type = 'Praca';
		}
		
		if(preg_match('/^r\.?.*|rua .*/si', strtolower($type_address))){
			$type = 'Rua';
		}
		
		if(preg_match('/^rod\.?.*|rodovia .*/si', strtolower($type_address))){
			$type = 'Rodovia';
		}
		
		if(preg_match('/^tv\.?.*|travessa .*/si', strtolower($type_address))){
			$type = 'Travessa';
		}
		
		if(preg_match('/^trv\.?.*|trevo .*/si', strtolower($type_address))){
			$type = 'Trevo';
		}
		
		if(preg_match('/^vl\.?.*|vila .*/si', strtolower($type_address))){
			$type = 'Vila';
		}

		if(preg_match('/^vd\.?.*|viaduto .*/si', strtolower($type_address))){
			$type = 'Viaduto';
		}

		/**
		* Caso o logradouro nao atenda a nenhum criterio acima, tenta extrair o logradouro do nome da rua
		*/
		if(!$type && $street){
			$type = $this->getTypeAddress($street);
		}else if (!$type && !$street){
			/**
			* Insere Rua como padrao de logradouro
			*/
			$type = 'Rua';
		}		

		return $this->clearName($type);
	}

	/**
	* Trata o nome da rua passado por parametro
	*/
	public function getStreet($street){
		/**
		* Remove tudo que nao for letas e numeros do nome da rua
		*/
		$street = preg_replace('/[^a-z0-9\. ]/si', '', strtolower($street));

		/**
		* Remove qualquer combinacao de logradouro que encontrar no endereço
		*/
		$street = preg_replace('/    .*/si', '', $street);
		$street = preg_replace('/^bairro /si', '', $street);
		$street = preg_replace('/^al\.?|alameda /si', '', $street);
		$street = preg_replace('/^av\.?|avenida /si', '', $street);
		$street = preg_replace('/^b[c]?\.?|beco /si', '', $street);
		$street = preg_replace('/^cal[cç]?\.?|cal[cç]ada /si', '', $street);
		$street = preg_replace('/^con[d]?\.?|condom[ií]nio /si', '', $street);
		$street = preg_replace('/^cj\.?|conj\.?|conju\.?|conjunto /si', '', $street);
		$street = preg_replace('/^esc\.?|esd\.?|escad\.?|escadaria /si', '', $street);
		$street = preg_replace('/^es[t]?\.?|estrada /si', '', $street);
		$street = preg_replace('/^ga[l]?\.?|galeria /si', '', $street);
		$street = preg_replace('/^jd\.?|jardim /si', '', $street);
		$street = preg_replace('/^l[g]?\.?|largo /si', '', $street);
		$street = preg_replace('/^p[cç]?[a]?\.?|pra[cç]a /si', '', $street);
		$street = preg_replace('/^r[\. ]/si', '', $street);
		$street = preg_replace('/^rua /si', '', $street);
		$street = preg_replace('/^rod\.?|rodovia /si', '', $street);
		$street = preg_replace('/^tv\.?|travessa /si', '', $street);
		$street = preg_replace('/^trv\.?|trevo /si', '', $street);
		$street = preg_replace('/^vl\.?|vila /si', '', $street);
		$street = preg_replace('/^vd\.?|viaduto /si', '', $street);
		$street = preg_replace('/comtipo/si', '', $street);
		$street = preg_replace('/cento e um/si', '101', $street);

		/**
		* Remove qualquer numero residencial que esteja no meio do endereço
		*/
		$street = preg_replace('/^([a-z ]*)?([0-9]*)?.*/si', "$1", $street);

		/**
		* Remove as abreviacoes
		*/
		$street = $this->removeAbreviacoes($street);

		/**
		* Formata o nome com as primeiras letras em maiusculo
		*/
		$street = $this->clearName($street, false);

		/**
		* Seta o nome da rua como null caso nao tenho nenhuma infomracao
		*/
		if(is_null($street) || empty($street) || trim($street) == ''){
			$street = null;
		}

		return $street;
	}

	/**
	* Tenta carregar o numero da rua a partir do parametro $number, caso nao consiga, tenta carregar a partir do parametro $street
	*/
	public function getStreetNumber($number, $street=false){		
		/**
		* Inicializa a variavel $street_number com null
		*/
		$street_number = null;

		/**
		* Verifica se o numero passado por parametro é valido, caso nao seja, tenta carregar o numero a partir do nome da rua
		*/
		if(is_numeric($number) && $number > '0'){
			$street_number = $number;
		}

		/**
		* Caso o numero nao tenha sido carregado ainda, tenta carrega-lo a partir do nome da rua
		*/
		if(!$street_number && $street){
			if(!preg_match('/^br /si', $street) && preg_match('/^([a-z ]*)?([0-9]*)?.*/si', $street, $vet)){	
				$street_number = $vet[2];
			}
		}

		/**
		* Remove tudo que nao for numero
		*/
		$street_number = preg_replace('/[^0-9]/', '', $street_number);

		/**
		* Seta o numero como null caso nao tenho nenhuma infomracao
		*/
		if(is_null($street_number) || $street_number === 0 || empty($street_number) || trim($street_number) == ''){
			$street_number = null;
		}


		return $street_number;
	}

	/**
	* Explode o telefone fixo separando o ddd do telefone
	*/
	private function explodeMobileNatt($tel, $item){
		/**
		* Analisa a situacao do telefone a partir da quantidade de numeros encontrados
		*/
		$qt_numbers = strlen($tel);

		/**
		* Trata o numero de acordo com os zeros iniciais
		*/
		switch ($qt_numbers) {
			/**
			* 10 Zero: Indica que o numero contem 8 digitos e esta acompanhado do DDD
			*/
			case 10:
				$ddd = substr($tel, 0, 2);
				$tel = '9' . substr($tel, 2);
				break;

			/**
			* 8 Zeros: Indica que o numero contem 8 digitos e nao esta acompanhado do DDD
			*/
			case 8:
				$ddd = null;
				$tel = "9{$tel}";
				break;
			
			default:
				/**
				* Caso nao atenda a nenhuma das opcoes acima, o telefone sera considerado como nulo
				*/
				$ddd = null;
				$tel = null;
				break;
		}

		$map = array(
			'ddd' => $ddd,
			'tel' => $tel
			);

		return $map[$item];	}
	
	/**
	* Explode o telefone separando o ddd do telefone
	*/
	private function explodeTelNatt($tel, $item){
		/**
		* Analisa a situacao do telefone a partir da quantidade de zeros iniciais
		*/
		preg_match('/^(0*)/si', $tel, $vet);
		$qt_zeros = strlen($vet[1]);

		/**
		* Trata o numero de acordo com os zeros iniciais
		*/
		switch ($qt_zeros) {
			/**
			* 1 Zero: Indica que o numero contem 8 digitos e esta acompanhado do DDD
			*/
			case 1:
				$ddd = substr($tel, 1, 2);
				$tel = substr($tel, -8);
				break;

			/**
			* 2 Zeros: Indica que o numero contem 7 digitos e esta acompanhado do DDD
			*/
			case 2:
				$ddd = substr($tel, 2, 2);
				$tel = substr($tel, -7);
				/**
				* Adiciona o numero 3 na frente do telefone
				*/
				$tel = "3{$tel}";
				break;
			
			/**
			* 3 Zeros: Indica que o telefone tem 8 digitos e nao esta acompanhado do DDD
			*/
			case 3:
				$ddd = null;
				$tel = substr($tel, -8);
				break;
			
			/**
			* 4 Zeros: Indica que o telefone tem 7 digitos e nao esta acompanhado do DDD
			*/
			case 4:
				$ddd = null;
				$tel = substr($tel, -7);
				/**
				* Adiciona o numero 3 na frente do telefone
				*/
				$tel = "3{$tel}";
				break;
			
			default:
				/**
				* Caso nao atenda a nenhuma das opcoes acima, o telefone sera considerado como nulo
				*/
				$ddd = null;
				$tel = null;
				break;
		}

		$map = array(
			'ddd' => $ddd,
			'tel' => $tel
			);

		return $map[$item];
	}
	
	/**
	* Extrai o DDD do telefone passado por parametro
	*/
	public function getDDD($tel){
		return $this->explodeTelNatt($tel, 'ddd');
	}	

	/**
	* Extrai o Telefone separado do DDD
	*/
	public function getTelefone($tel){
		return $this->explodeTelNatt($tel, 'tel');
	}	

	/**
	* Extrai o DDD do telefone passado por parametro
	*/
	public function getDDDMobile($tel){
		return $this->explodeMobileNatt($tel, 'ddd');
	}	

	/**
	* Extrai o Telefone separado do DDD
	*/
	public function getMobile($tel){
		return $this->explodeMobileNatt($tel, 'tel');
	}	

	/**
	* Verifica se o documento passado é valido, seja como CPF ou como CNPJ
	*/
	public function validateDoc($doc){
		$validate = $this->validateCpf($doc);
		$validate = $validate?$validate:$this->validateCnpj($doc);

		return $validate;
	}

	/**
	* Verifica se o cpf passado é valido
	*/
	public function validateCpf($cpf){
		// Verifica se um número foi informado
		if(empty($cpf)) {
		    return false;
		}

		// Elimina possivel mascara
		$cpf = ereg_replace('[^0-9]', '', $cpf);
		$cpf = str_pad(substr($cpf, -11), 11, '0', STR_PAD_LEFT);
	 
		// Verifica se o numero de digitos informados é igual a 11 
		if (strlen($cpf) != 11) {
		    return false;
		}

		// Verifica se nenhuma das sequências invalidas abaixo 
		// foi digitada. Caso afirmativo, retorna falso
		else if (preg_match('/0{11}|1{11}|2{11}|3{11}|4{11}|5{11}|6{11}|7{11}|8{11}|9{11}/si', $cpf)) {
		    return false;
		 // Calcula os digitos verificadores para verificar se o
		 // CPF é válido
		 } else {   
		     
		    for ($t = 9; $t < 11; $t++) {
		         
		        for ($d = 0, $c = 0; $c < $t; $c++) {
		            $d += $cpf{$c} * (($t + 1) - $c);
		        }
		        $d = ((10 * $d) % 11) % 10;
		        if ($cpf{$c} != $d) {
		            return false;
		        }
		    }

		    return true;
		}		
	}

	/**
	* Verifica se o cnpj passado é valido
	*/
	public function validateCnpj($cnpj){
		// Verifica se um número foi informado
		if(empty($cnpj)) {
		    return false;
		}

		// Elimina possivel mascara
		$cnpj = ereg_replace('[^0-9]', '', $cnpj);
		$cnpj = str_pad(substr($cnpj, -14), 14, '0', STR_PAD_LEFT);
		 
		// Verifica se o numero de digitos informados é igual a 11 
		if (strlen($cnpj) != 14) {
		    return false;
		}
		// Verifica se nenhuma das sequências invalidas abaixo 
		// foi digitada. Caso afirmativo, retorna falso
		else if (preg_match('/0{14}|1{14}|2{14}|3{14}|4{14}|5{14}|6{14}|7{14}|8{14}|9{14}/si', $cnpj)) {
		    return false;
		 // Calcula os digitos verificadores para verificar se o
		 // CPF é válido
		 }


        $calcular = 0;
        $calcularDois = 0;
        for ($i = 0, $x = 5; $i <= 11; $i++, $x--) {
            $x = ($x < 2) ? 9 : $x;
            $number = substr($cnpj, $i, 1);
            $calcular += $number * $x;
        }
        for ($i = 0, $x = 6; $i <= 12; $i++, $x--) {
            $x = ($x < 2) ? 9 : $x;
            $numberDois = substr($cnpj, $i, 1);
            $calcularDois += $numberDois * $x;
        }
 
        $digitoUm = (($calcular % 11) < 2) ? 0 : 11 - ($calcular % 11);
        $digitoDois = (($calcularDois % 11) < 2) ? 0 : 11 - ($calcularDois % 11);
 
        if ($digitoUm <> substr($cnpj, 12, 1) || $digitoDois <> substr($cnpj, 13, 1)) {
            return false;
        }


        return true;		
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
		$txt = preg_replace("/í|ì|î/s", "i", $txt);
		$txt = preg_replace("/ó|ò|ô|õ|º/s", "o", $txt);
		$txt = preg_replace("/ú|ù|û/s", "u", $txt);
		$txt = str_replace("ç","c",$txt);

		$txt = preg_replace("/Á|À|Â|Ã|ª/s", "A", $txt);
		$txt = preg_replace("/É|È|Ê/s", "E", $txt);
		$txt = preg_replace("/Í|Ì|Î/s", "I", $txt);
		$txt = preg_replace("/Ó|Ò|Ô|Õ|º/s", "O", $txt);
		$txt = preg_replace("/Ú|Ù|Û/s", "U", $txt);
		$txt = str_replace("Ç","C",$txt);		

		return $txt;
	}	

	/**
	* Remove abreviacoes
	*/
	private function removeAbreviacoes($txt){
		$txt = trim($txt);
		$txt = preg_replace('/ res /si', ' Residencial ', strtolower($txt));
		$txt = preg_replace('/^res /si', 'Residencial ', strtolower($txt));
		$txt = preg_replace('/^n s /si', 'Nossa Senhora ', strtolower($txt));
		$txt = preg_replace('/^n sra /si', 'Nossa Senhora ', strtolower($txt));
		$txt = preg_replace('/^al\.? |alameda /si', 'Alameda', $txt);
		$txt = preg_replace('/^av\.? |avenida /si', 'Avenida', $txt);
		$txt = preg_replace('/^b[c]?\.? |beco /si', 'Beco', $txt);
		$txt = preg_replace('/^cal[cç]?\.? |cal[cç]ada /si', 'Calçada ', $txt);
		$txt = preg_replace('/^cj\.? |conj\.? |conju\.? |conjunto /si', 'Conjunto ', $txt);
		$txt = preg_replace('/^con[d]?\.? |condom[ií]nio /si', 'Condomínio ', $txt);
		$txt = preg_replace('/^es[t]?\.? |estrada /si', 'Estrada ', $txt);
		$txt = preg_replace('/^esc\.? |esd\.? |escad\.? |escadaria /si', 'Escadaria ', $txt);
		$txt = preg_replace('/^ga[l]?\.? |galeria /si', 'Galeria ', $txt);
		$txt = preg_replace('/^j /si', 'Jardim ', $txt);
		$txt = preg_replace('/^jd\.? |jardim /si', 'Jardim ', $txt);
		$txt = preg_replace('/^l[g]?\.? |largo /si', 'Largo ', $txt);
		$txt = preg_replace('/^n rosa penha /si', 'nova rosa da penha ', strtolower($txt));
		$txt = preg_replace('/^p[cç]?\.? |pra[cç]a /si', 'Praça ', $txt);
		$txt = preg_replace('/^pq /si', 'Parque ', strtolower($txt));
		$txt = preg_replace('/^pr\.? |praia /si', 'Praia ', $txt);
		$txt = preg_replace('/^r\.? |rua /si', 'Rua ', $txt);
		$txt = preg_replace('/^rod\.? |rodovia /si', 'Rodovia ', $txt);
		$txt = preg_replace('/^s /si', 'Sao ', strtolower($txt));
		$txt = preg_replace('/^sta /si', 'Santa ', strtolower($txt));
		$txt = preg_replace('/^sto /si', 'Santo ', strtolower($txt));
		$txt = preg_replace('/^trv\.? |trevo /si', 'Trevo ', $txt);
		$txt = preg_replace('/^tv\.? |travessa /si', 'Travessa ', $txt);
		$txt = preg_replace('/^vd\.? |viaduto /si', 'Viaduto ', $txt);
		$txt = preg_replace('/^vl\.? |vila /si', 'Vila ', $txt);

		return $txt;
	}

	/**
	* Carrega um array com os codigos e siglas de todos os estados
	*/	
	public function loadStates($flip=false){
		$states = array(
					'AC' => '1',
					'AL' => '2',
					'AM' => '3',
					'AP' => '4',
					'BA' => '5',
					'CE' => '6',
					'DF' => '7',
					'ES' => '8',
					'GO' => '9',
					'MA' => '10',
					'MG' => '11',
					'MS' => '12',
					'MT' => '13',
					'PA' => '14',
					'PB' => '15',
					'PE' => '16',
					'PI' => '17',
					'PR' => '18',
					'RJ' => '19',
					'RN' => '20',
					'RO' => '21',
					'RR' => '22',
					'RS' => '23',
					'SC' => '24',
					'SE' => '25',
					'SP' => '26',
					'TO' => '27'
				);

		if($flip){
			$states = array_flip($states);
		}

		return $states;	
	}

	/**
	* Retorna o valor da funcao CRC32 sempre positivo, nunca negativo
	*/
	private function __hash($str){
		$hash = crc32($str);

		/**
		* Converte o sinal do hash caso ele seja negativo
		*/
		return ($hash < 0)?($hash*(-1)):$hash;
	}

	/**
	* Método __log
	* Este método alimenta o __log da operacao
	*
	* @override Metodo AppController.__log
	* @param string $content
	* @return void
	*/
	public function __log($log, $type, $uf, $status=true, $table=null, $pk=null, $data=null, $mysql_error=null){	
		// $Log['LogsImport'] = array(
		// 	'log' => $log,
		// 	'type' => $type,
		// 	'mysql_error' => $mysql_error,
		// 	'uf' => $uf,
		// 	'table' => $table,
		// 	'pk' => $pk,
		// 	'data' => $data,
		// 	'status' => $status,
		// 	);
		// $this->Log->create();
		// $this->Log->save($Log);
	}

	/**
	* Método __counter
	* Este método alimenta o __counter da operacao
	*
	* @override Metodo AppController.__counter
	* @param string $content
	* @return void
	*/
	public function __counter($table){
		$values = array();
		if(isset($this->counter[$table]['success'])){
			$values['success'] = $this->counter[$table]['success'];
		}
		
		if(isset($this->counter[$table]['fails'])){
			$values['fails'] = $this->counter[$table]['fails'];
		}
		if(count($values)){
			$this->ModelCounter->updateAll($values, array('table' => $table, 'active' => '1'));
		}
	}

	/**
	* Contabiliza uma insercao finalizada com sucesso
	*/
	public function success($table){
		if(!isset($this->counter[$table]['success'])){
			$this->counter[$table]['success'] = 1;
		}else{
			$this->counter[$table]['success']++;
		}
	}

	/**
	* Contabiliza uma falha de insercao
	*/
	public function fail($table){
		if(!isset($this->counter[$table]['fails'])){
			$this->counter[$table]['fails'] = 1;
		}else{
			$this->counter[$table]['fails']++;
		}
	}

	/**
	 * show a status bar in the console
	 * 
	 * <code>
	 * for($x=1;$x<=100;$x++){
	 * 
	 *     progressBar($x, 100);
	 * 
	 *     usleep(100000);
	 *                           
	 * }
	 * </code>
	 *
	 * @param   int     $done   how many items are completed
	 * @param   int     $total  how many items are to be done total
	 * @param   int     $size   optional size of the status bar
	 * @return  void
	 *
	 */
	public function progressBar($done, $total, $uf, $size=50) {
	    // if we go over our bound, just ignore it
	    static $startTime;
	    static $date_begin;
    	static $interval;

	    if(!isset($interval)){
	    	$interval = PROGRESSBAR_INTERVAL;
	    }

	    if($done == 1 || ($done > 0 && ($done/$interval) === 1)){
	    	$this->__flush();

	    	$interval+= PROGRESSBAR_INTERVAL;


		    $status_bar="\n[";

		    if($done > $total){
		    	$status_bar="\n########## IMPORTACAO ENCERRADA ##########";
		    	$status_bar.="\n[";
		    } 

		    if(empty($startTime)){
		    	$startTime=time();
		    } 

		    if(empty($date_begin)){
		    	$date_begin=date('d/m/Y H:i:s');
		    } 

		    $now = time();
		    $perc=(double)($done/$total);
		    $bar=floor($perc*$size);

		    $status_bar.=str_repeat("=", $bar);

		    if($bar<$size){
		        $status_bar.=">";
		        $status_bar.=str_repeat(" ", $size-$bar);
		    } else {
		        $status_bar.="=";
		    }

		    $disp=number_format($perc*100, 0);

		    $status_bar.="] $disp%  " . number_format($done, 0, '', '.') . "/" . number_format($total, 0, '', '.');

		    $rate = ($now-$startTime)/$done;
		    $left = $total - $done;
		    $eta = round($rate * $left, 2);

		    $elapsed = $now - $startTime;
		    $elapsed_minuts = $elapsed / 60;
		    $elapsed_day = floor($elapsed / 86400);

		    /**
		    * Tempo percorrido
		    */
			$day = str_pad(floor($elapsed/86400), 2, '0', STR_PAD_LEFT);
			$hour = str_pad(floor(($elapsed/3600) - ($day*24)), 2, '0', STR_PAD_LEFT);
			$min = str_pad(floor(($elapsed/60) - (($day*1440) + ($hour*60))), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor($elapsed - (($day*86400) + ($hour*3600) + ($min*60))), 2, '0', STR_PAD_LEFT);
			$elapsed = "{$hour}:{$min}:{$sec}";
			if($day != '00'){
				$elapsed = "{$day}d {$hour}:{$min}:{$sec}";
			}

		    /**
		    * Tempo Restante
		    */
			$day = str_pad(floor($eta/86400), 2, '0', STR_PAD_LEFT);
			$hour = str_pad(floor(($eta/3600) - ($day*24)), 2, '0', STR_PAD_LEFT);
			$min = str_pad(floor(($eta/60) - (($day*1440) + ($hour*60))), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor($eta - (($day*86400) + ($hour*3600) + ($min*60))), 2, '0', STR_PAD_LEFT);
			$eta = "{$hour}:{$min}:{$sec}";
			if($day != '00'){
				$eta = "{$day}d {$hour}:{$min}:{$sec}";
			}


			$map = $this->Log->query('select @@unique_checks');
			$unique_checks = $map[0][0]['@@unique_checks'];

			$map = $this->Log->query('select @@query_cache_type');
			$query_cache_type = $map[0][0]['@@query_cache_type'];

			$map = $this->Log->query('select @@query_cache_size');
			$query_cache_size = ($map[0][0]['@@query_cache_size']/1024)/1024;

		    $status_bar .= "\n";
			$status_bar .= "###################################################################\n";
			$status_bar .= "Start: {$date_begin}\n";
		    $status_bar .= "Tempo Restante:\t\t{$eta}\n";
		    $status_bar .= "Tempo percorrido:\t{$elapsed}\n";
			$status_bar .= "===================================================================\n";
			$status_bar .= "Estado processado: {$uf}\n";
			$status_bar .= "===================================================================\n";
		    $status_bar .= "Status do processo de importacao\n";
			$status_bar .= "___________________________________________________________________\n";
		    $status_bar .= "Min\t\t\tImport\t\tTable\n";
			$status_bar .= "___________________________________________________________________\n";
		    if(isset($this->counter) && count($this->counter)){
			    foreach ($this->counter as $k => $v) {
			    	if(isset($v['success'])){
				    	$per_minuts = ($v['success'] == 0 || $elapsed_minuts == 0)?0:round($v['success'] / $elapsed_minuts);
			    		$status_bar .= number_format($per_minuts, 0, '', '.') . "\t\t\t" . number_format($v['success'], 0, '', '.') . "\t\t{$k}\n";
			    	}
			    }
		    }
			$status_bar .= "\n";
			$status_bar .= "===================================================================\n";
		    $status_bar .= "Configuracao do banco\n";
			$status_bar .= "___________________________________________________________________\n";
			$status_bar .= "unique\tquery_cache\tcache_size\n";
			$status_bar .= "___________________________________________________________________\n";
			$status_bar .= "{$unique_checks}\t\t{$query_cache_type}\t\t{$query_cache_size}M\n";

		    echo "{$status_bar}  ";
	    }
	    // when done, send a newline
	    if($done == $total) {
	        echo "\n";
	    }
	}

	/**
	* Limpa a tela do console linux
	*/
	public function __flush(){
		echo shell_exec('clear');
	}

	/**
	* Mensura o tempo gasto nas consultas
	*/
	public function timing_ini($time_id){
		$this->time_start = microtime(true);
		$this->time_id = $time_id;
	}

	/**
	* Mensura o tempo gasto nas consultas
	*/
	public function timing_end(){
		$this->time_end = microtime(true);
		$time = $this->time_end - $this->time_start;
		if(!isset($this->timing_avg[$this->time_id])){
			$this->timing_avg[$this->time_id][] = $time;
		}else{
			array_unshift($this->timing_avg[$this->time_id], $time);
		}
		$this->timing_avg[$this->time_id] = array_slice($this->timing_avg[$this->time_id], 0, LIMIT_BUILD_SOURCE);

		$avg = array_sum($this->timing_avg[$this->time_id])/count($this->timing_avg[$this->time_id]);
		$this->Timing->updateAll(array('Timing.time' => $avg), array('Timing.id' => $this->time_id));
	}
}