<?php
App::uses('AppModelClean', 'Model');
/**
 * Imobile Model
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
 * Imobile Model
 *
 * @property Country $Country
 * @property City $City
 */
class Imobile extends AppModelClean {
    public $useTable = 'i_mobiles';
	public $recursive = -1;
    public $layout;
    public $lote;
    public $map_pos;
    public $delimiter;
    public $folder;
	public $source_year;

	public function findImport($type, $params){
		$hasLandline = $this->find($type, $params);				

		if(!count($hasLandline)){
			$this->setSource('mobiles');
			$hasLandline = $this->find($type, $params);
			$this->setSource('i_mobiles');
		}	

		return $hasLandline;		
	}	    

    public function read_sources() {
        /**
        * Limpa o arquivo de log antes de carregar um novo arquivo
        */
        file_put_contents(dirname(dirname(dirname(__FILE__))) . '/_db/settings/logs', '');

        /**
        * Abre o diretorio indicado
        */
        $dir = opendir($this->folder);

        /**
        * Percorre por todos os arquivos contidos no diretorio indicado
        */
        while ($file = readdir($dir)) {

            /**
            * Verifica se o elemento seleciona é mesmo um arquivo
            */
            if ($file != "." && $file != "..") {

                /**
                * Carrega o caminha completo do arquivo encontrado
                */
                $path = $this->folder . "/" . $file;

                /**
                * Verifica se o arquivo encontrado contem a extensao .txt
                */
                if (!is_dir($path) && strtolower(substr($file, -3)) == 'txt') {
                    
                    /**
                    * Gera um array com os dados contidos do arquivo encontrado
                    */
                    $map[] = $path;

                } else if(is_dir($path)) {

                    /**
                    * Caso o item encontrado seja uma subpasta, percorre pelos arquivos dela tambem
                    */
                    $this->next_text($path);
                }
            }

        }

        /**
        * Fecha o diretorio indicado
        */
        closedir($dir);

        /**
        * Ordena a lista de arquivos encontrada
        */
        sort($map);

        return $map;
    } 

    public function txt2array($ln){
        /**
        * Carrega a linha com os dados
        */
        $v = $ln;

        /**
        * Executa a higienizacao de acordo com o delimitador dos dados
        */
        switch ($this->delimiter) {
            case '";"':
                /**
                * Higieniza a string
                */
                $v = preg_replace('/[^0-9a-zA-Z\'"; ]/si', '', $v);
                $v = substr(rtrim($v, "\r\n"), 1, -1);

                /**
                * Gera um array a partir das informacoes contidas na linha
                */
                $v = preg_replace('/;;;;;;/si', ';"";"";"";"";"";', $v);
                $v = preg_replace('/;;;;;/si', ';"";"";"";"";', $v);
                $v = preg_replace('/;;;;/si', ';"";"";"";', $v);
                $v = preg_replace('/;;;/si', ';"";"";', $v);
                $v = preg_replace('/;;/si', ';"";', $v);           
                $v = preg_replace('/^;/si', '"";', $v);           
                $v = preg_split('/(\'|")?;(\'|")/si', $v);
                break;
            case '"#"':
                /**
                * Higieniza a string
                */
                $v = preg_replace('/[^0-9a-zA-Z\'"# ]/si', '', $v);
                $v = substr(rtrim($v, "\r\n"), 1, -1);

                /**
                * Gera um array a partir das informacoes contidas na linha
                */
                $v = preg_replace('/######/si', '#""#""#""#""#""#', $v);
                $v = preg_replace('/#####/si', '#""#""#""#""#', $v);
                $v = preg_replace('/####/si', '#""#""#""#', $v);
                $v = preg_replace('/###/si', '#""#""#', $v);
                $v = preg_replace('/##/si', '#""#', $v);           
                $v = preg_replace('/^#/si', '""#', $v);           
                $v = preg_split('/(\'|")?#(\'|")/si', $v);
                break;
            case ';':
                /**
                * Higieniza a string
                */
                $v = preg_replace('/[^0-9a-zA-Z; ]/si', '', $v);

                /**
                * Gera um array a partir das informacoes contidas na linha
                */
                $v = preg_replace('/;;;;;;/si', ';' . null . ';' . null . ';' . null . ';' . null . ';' . null . ';', $v);
                $v = preg_replace('/;;;;;/si', ';' . null . ';' . null . ';' . null . ';' . null . ';', $v);
                $v = preg_replace('/;;;;/si', ';' . null . ';' . null . ';' . null . ';', $v);
                $v = preg_replace('/;;;/si', ';' . null . ';' . null . ';', $v);
                $v = preg_replace('/;;/si', ';' . null . ';', $v);
                $v = preg_replace('/^;/si', null . ';', $v);           
                $v = preg_split('/;/si', $v);
                break;
        }

        /**
        * Prepra os dados que alimentarao o array
        */
        @$birthday          = !empty($v[$this->map_pos['birthday']])    ?trim($v[$this->map_pos['birthday']])    :null;
        @$city              = !empty($v[$this->map_pos['city']])        ?trim($v[$this->map_pos['city']])        :null;
        @$complement        = !empty($v[$this->map_pos['complement']])  ?trim($v[$this->map_pos['complement']])  :null;
        @$ddd               = !empty($v[$this->map_pos['ddd']])         ?$v[$this->map_pos['ddd']]               :null;
        @$doc               = !empty($v[$this->map_pos['doc']])         ?trim($v[$this->map_pos['doc']])         :null;
        @$gender            = !empty($v[$this->map_pos['gender']])      ?trim($v[$this->map_pos['gender']])      :null;
        @$mother            = !empty($v[$this->map_pos['mother']])      ?trim($v[$this->map_pos['mother']])      :null;
        @$name              = !empty($v[$this->map_pos['name']])        ?trim($v[$this->map_pos['name']])        :null;
        @$neighborhood      = !empty($v[$this->map_pos['neighborhood']])?trim($v[$this->map_pos['neighborhood']]):null;
        @$number            = !empty($v[$this->map_pos['number']])      ?trim($v[$this->map_pos['number']])      :null;
        @$state             = !empty($v[$this->map_pos['state']])       ?trim($v[$this->map_pos['state']])       :null;
        @$street            = !empty($v[$this->map_pos['street']])      ?trim($v[$this->map_pos['street']])      :null;
        @$telefone          = !empty($v[$this->map_pos['tel']])         ?$v[$this->map_pos['tel']]               :null;
        @$tel_full          = !empty($v[$this->map_pos['tel_full']])    ?$v[$this->map_pos['tel_full']]          :"{$ddd}{$telefone}";
        @$type_address      = !empty($v[$this->map_pos['type_address']])?trim($v[$this->map_pos['type_address']]):null;
        @$zipcode           = !empty($v[$this->map_pos['zipcode']])     ?trim($v[$this->map_pos['zipcode']])     :null;

        /**
        * Prepara o array para o formato aceito no controller
        */
        $map = array(
                'BIRTHDAY' => $birthday,
                'CITY' => $city,
                'COMPLEMENT' => $complement,  
                'DOC' => $doc,
                'GENDER' => $gender,
                'MOTHER' => $mother,
                'NAME' => $name,
                'NEIGHBORHOOD' => $neighborhood,
                'NUMBER' => $number,
                'STATE' => $state,
                'STREET' => $street,
                'TEL_FULL' => $tel_full,
                'TYPE_ADDRESS' => $type_address,
                'ZIPCODE' => $zipcode,
            );   

        return $map;
    }
}