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
    public $layout_positions;
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

    public function txt2array($v){
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

        /**
        * Pula a linha caso nao tenha informacoes (linha em branco)
        */
        if(count($v) < 2){
            continue;
        }

        /**
        * Prepra os dados que alimentarao o array
        */
        @$cpf_cnpj = !empty($v[$this->layout_positions['NRF']])?trim($v[$this->layout_positions['NRF']]):null;
        @$nome_razao = !empty($v[$this->layout_positions['NOME']])?trim($v[$this->layout_positions['NOME']]):null;
        @$mae = !empty($v[$this->layout_positions['MAE']])?trim($v[$this->layout_positions['MAE']]):null;
        @$sexo = !empty($v[$this->layout_positions['SEXO']])?trim($v[$this->layout_positions['SEXO']]):null;
        @$dt_nascimento = !empty($v[$this->layout_positions['DT_NASCIMENTO']])?trim($v[$this->layout_positions['DT_NASCIMENTO']]):null;
        @$ddd = !empty($v[$this->layout_positions['DDD']])?$v[$this->layout_positions['DDD']]:null;
        @$telefone = !empty($v[$this->layout_positions['FONE']])?$v[$this->layout_positions['FONE']]:null;
        @$tel_full = "0{$ddd}{$telefone}";
        @$cep = !empty($v[$this->layout_positions['CEP']])?trim($v[$this->layout_positions['CEP']]):null;
        @$cod_end = null;
        @$complemento = !empty($v[$this->layout_positions['INS_COMPL']])?trim($v[$this->layout_positions['INS_COMPL']]):null;
        @$numero = !empty($v[$this->layout_positions['INS_NUM_EN']])?trim($v[$this->layout_positions['INS_NUM_EN']]):null;
        @$data_atualizacao = !empty($v[$this->layout_positions['DATA_ATUALIZACAO']])?"{$this->source_year}-01-01":null;
        @$rua = !empty($v[$this->layout_positions['INS_TP_END']])?trim($v[$this->layout_positions['INS_TP_END']]):null;
        @$nome_rua = !empty($v[$this->layout_positions['INS_ENDERE']])?trim($v[$this->layout_positions['INS_ENDERE']]):null;
        @$bairro = !empty($v[$this->layout_positions['INS_BAIRRO']])?trim($v[$this->layout_positions['INS_BAIRRO']]):null;
        @$cidade = !empty($v[$this->layout_positions['CIDADE']])?trim($v[$this->layout_positions['CIDADE']]):null;
        @$uf = !empty($v[$this->layout_positions['UF']])?trim($v[$this->layout_positions['UF']]):null;

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