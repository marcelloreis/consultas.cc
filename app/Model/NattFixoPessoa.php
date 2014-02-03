<?php
App::uses('AppModelClean', 'Model');
/**
 * NattFixoPessoa Model
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
 * NattFixoPessoa Model
 *
 * @property Country $Country
 * @property City $City
 */
class NattFixoPessoa extends AppModelClean {
    public $useTable = false;
	public $recursive = -1;
	public $useDbConfig = 'natt';
	public $primaryKey = 'CPF_CNPJ';
	public $displayField = 'NOME_RAZAO';
    public $order = 'NattFixoPessoa.CPF_CNPJ';
    public $layout;
    public $jumpFirstLine;
    public $map_pos;
    public $delimiter;
    public $folder;
	public $source_year;

    public $hasMany = array(
        'NattFixoTelefone' => array(
            'className' => 'NattFixoTelefone',
            'foreignKey' => 'CPF_CNPJ',
            'type' => 'INNER'
        )
    );

    public function next_binary($row_count){
        $map = array();
        $pessoa = $this->find('all', array(
            'fields' => array(
                'NattFixoPessoa.CPF_CNPJ',
                'NattFixoPessoa.NOME_RAZAO',
                'NattFixoPessoa.MAE',
                'NattFixoPessoa.SEXO',
                'NattFixoPessoa.DT_NASCIMENTO',
                ),
            'conditions' => array(
                // 'CPF_CNPJ' => '10153108770',
                // 'CPF_CNPJ' => '09685634734',
                'CPF_CNPJ !=' => '00000000000000000000',
                ),
            'limit' => "0,{$row_count}"
            ));

        if(count($pessoa)){
            foreach ($pessoa as $k => $v) {
                $map[$k]['pessoa'] = $v['NattFixoPessoa'];
                $map_telefone = $this->NattFixoTelefone->find('all', array(
                    'fields' => array(
                        'NattFixoTelefone.TELEFONE',
                        'NattFixoTelefone.CPF_CNPJ',
                        'NattFixoTelefone.CEP',
                        'NattFixoTelefone.COD_END',
                        'NattFixoTelefone.COMPLEMENTO',
                        'NattFixoTelefone.NUMERO',
                        'NattFixoTelefone.DATA_ATUALIZACAO'
                        ),
                    'conditions' => array('CPF_CNPJ' => $v['NattFixoPessoa']['CPF_CNPJ']),
                    'order' => array('DATA_ATUALIZACAO' => 'DESC'),
                    'limit' => 10
                    ));

                /**
                * Agrupa os telefones iguais ordenando a atualizacao do mais novo para mais antigo
                */
                krsort($map_telefone);
                $telefone = array();
                foreach ($map_telefone as $k3 => $v3) {
                    $telefone[$v3['NattFixoTelefone']['TELEFONE']] = $v3;
                }

                if(count($telefone)){
                    foreach ($telefone as $k2 => $v2) {
                        $endereco = $this->NattFixoTelefone->NattFixoEndereco->find('first', array(
                            'fields' => array(
                                'NattFixoEndereco.RUA',
                                'NattFixoEndereco.NOME_RUA',
                                'NattFixoEndereco.BAIRRO',
                                'NattFixoEndereco.CIDADE',
                                'NattFixoEndereco.UF',
                                'NattFixoEndereco.CEP'
                                ),
                            'conditions' => array('COD_END' => $v2['NattFixoTelefone']['COD_END']),
                            ));
                        $map[$k]['telefone'][$k2] = $v2['NattFixoTelefone'];
                        if(isset($endereco['NattFixoEndereco'])){
                            $map[$k]['telefone'][$k2]['endereco'] = $endereco['NattFixoEndereco'];
                        }
                    }
                }else{
                    /**
                    * Elimina as entidades que nao tiverem ao menos 1 telefone relacionado
                    */
                    unset($map[$k]);
                }

                $this->offset($v['NattFixoPessoa']['CPF_CNPJ']);
            }
        }
        return $map;        
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
                    // $map = isset($map) && is_array($map)?array_merge($map, $this->txt2array($path)):$this->txt2array($path);
                    // $map = $this->txt2array($path);
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
            case '"#"':
                /**
                * Higieniza a string
                */
                $v = preg_replace('/[^0-9a-zA-Z\'"# ]/si', '', $v);
                $v = substr(rtrim($v, "\r\n"), 1, -1);

                /**
                * Gera um array a partir das informacoes contidas na linha
                */
                $v = preg_replace('/###/si', '#""#""#', $v);
                $v = preg_replace('/##/si', '#""#', $v);           
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
                $v = preg_replace('/;;;/si', ';' . null . ';' . null . ';', $v);
                $v = preg_replace('/;;/si', ';' . null . ';', $v);
                $v = preg_split('/;/si', $v);
                break;
        }

        /**
        * Prepra os dados que alimentarao o array
        */
        @$cpf_cnpj          = !empty($v[$this->map_pos['doc']])?trim($v[$this->map_pos['doc']]):null;
        @$nome_razao        = !empty($v[$this->map_pos['name']])?trim($v[$this->map_pos['name']]):null;
        @$mae               = !empty($v[$this->map_pos['mother']])?trim($v[$this->map_pos['mother']]):null;
        @$sexo              = !empty($v[$this->map_pos['gender']])?trim($v[$this->map_pos['gender']]):null;
        @$dt_nascimento     = !empty($v[$this->map_pos['birthday']])?trim($v[$this->map_pos['birthday']]):null;
        @$ddd               = !empty($v[$this->map_pos['ddd']])?$v[$this->map_pos['ddd']]:null;
        @$telefone          = !empty($v[$this->map_pos['tel']])?$v[$this->map_pos['tel']]:null;
        @$tel_full          = !empty($v[$this->map_pos['tel_full']])?$v[$this->map_pos['tel_full']]:"{$ddd}{$telefone}";
        @$tel_full          = "0{$tel_full}";
        @$cep               = !empty($v[$this->map_pos['zipcode']])?trim($v[$this->map_pos['zipcode']]):null;
        @$cod_end           = null;
        @$complemento       = !empty($v[$this->map_pos['complement']])?trim($v[$this->map_pos['complement']]):null;
        @$numero            = !empty($v[$this->map_pos['number']])?trim($v[$this->map_pos['number']]):null;
        @$data_atualizacao  = !empty($v[$this->map_pos['year']])?"{$this->source_year}-01-01":null;
        @$rua               = !empty($v[$this->map_pos['type_address']])?trim($v[$this->map_pos['type_address']]):null;
        @$nome_rua          = !empty($v[$this->map_pos['street']])?trim($v[$this->map_pos['street']]):null;
        @$bairro            = !empty($v[$this->map_pos['neighborhood']])?trim($v[$this->map_pos['neighborhood']]):null;
        @$cidade            = !empty($v[$this->map_pos['city']])?trim($v[$this->map_pos['city']]):null;
        @$uf                = !empty($v[$this->map_pos['state']])?trim($v[$this->map_pos['state']]):null;

        /**
        * Prepara o array para o formato aceito no controller
        */
        $map = array(
            'pessoa' => array(
                'CPF_CNPJ' => $cpf_cnpj,
                'NOME_RAZAO' => $nome_razao,
                'MAE' => $mae,
                'SEXO' => $sexo,
                'DT_NASCIMENTO' => $dt_nascimento
                ),
            'telefone' => array(
                "{$tel_full}" => array(
                    'TELEFONE' => $tel_full,
                    'CPF_CNPJ' => $cpf_cnpj,
                    'CEP' => $cep,
                    'COD_END' => $cod_end,
                    'COMPLEMENTO' => $complemento,
                    'NUMERO' => $numero,
                    'DATA_ATUALIZACAO' => $data_atualizacao,
                    'endereco' => array(
                        'RUA' => $rua,
                        'NOME_RUA' => $nome_rua,
                        'BAIRRO' => $bairro,
                        'CIDADE' => $cidade,
                        'UF' => $uf,
                        'CEP' => $cep,
                        )
                    )
                )
            );   

        return $map;
    }

    public function offset($doc){
        $this->deleteAll(array('NattFixoPessoa.CPF_CNPJ' => $doc), false);
        $this->NattFixoTelefone->deleteAll(array('NattFixoTelefone.CPF_CNPJ' => $doc), false);
    }
}