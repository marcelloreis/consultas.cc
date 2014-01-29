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

    public function next_text() {
        /**
        * Limpa o arquivo de log antes de carregar um novo arquivo
        */
        file_put_contents(dirname(dirname(dirname(__FILE__))) . '/_db/settings/logs', '');

        /**
        * Carrega a pasta onde contem os dados em txt
        */
        $folder = "/var/www/consultas.cc/_db/source/";

        /**
        * Carrega o layout dos dados que sera importados
        */
        // $this->layout = '"DOC"#"DDD"#"TEL"#"NOME"#"TP_LOG"#"LOGR"#"NUM"#"COMPL"#"BAIRRO"#"CIDADE"#"UF"#"CEP"#"FJ"';
        $this->layout = '"NRF"#"DDD"#"FONE"#"NOME"#"INS_TP_END"#"INS_ENDERE"#"INS_NUM_EN"#"INS_COMPL"#"INS_BAIRRO"#"CIDADE"#"UF"#"CEP"#"TIPONRF"';


        /**
        * Abre o diretorio indicado
        */
        $dir = opendir($folder);

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
                $path = $folder . "/" . $file;

                /**
                * Verifica se o arquivo encontrado contem a extensao .txt
                */
                if (!is_dir($path) && strtolower(substr($file, -3)) == 'txt') {
                    
                    /**
                    * Gera um array com os dados contidos do arquivo encontrado
                    */
                    $map = isset($map) && is_array($map)?array_merge($map, $this->txt2array($path)):$this->txt2array($path);
                    // $map = $this->txt2array($path);
                    // $map[] = $path;

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

    private function txt2array($path){
        /**
        * Carrega o mapa de indice a partir do layout informado
        */
        $map_layout = explode('"#"', substr($this->layout, 1, -1));
        $pos_lay = array_flip($map_layout);
    
        /**
        * Percorre por todo o arquivo passado pelo parametro, importando os dados contidos nas linhas para um array
        */
        $i = 0;
        $txt = fopen ($path, "r");
        while (!feof ($txt)) {
            /**
            * Contabiliza os registros processados
            */
            $i++;

            /**
            * Carrega a linha
            */
            $v = fgets($txt, 4096);
            $ln = $v;

            /**
            * Higieniza a string
            */
            $v = preg_replace('/[^0-9a-zA-Z\'"# ]/si', '', $v);
            $v = substr(rtrim($v, "\r\n"), 1, -1);

            /**
            * Gera um array a partir das informacoes contidas na linha
            */
            $v = preg_replace('/##/si', '#""#', $v);
            $v = preg_split('/(\'|")?#(\'|")/si', $v);

            /**
            * Pula a primeira linha (layout do documento)
            */
            if($i === 1){
                /**
                * Verifica se o layout do arquivo esta diferente do layout informado
                */
                if(rtrim($ln, "\r\n") != $this->layout){
                    $log = date('Y-m-d') . ": O Layout do arquivo '{$path}' nao confere com o layout do arquivo.\r\n";
                    $log .= "Layout informado:  {$this->layout}\r\n";
                    $log .= "Layout encontrado: {$ln}\r\n\r\n";
                    file_put_contents(dirname(dirname(dirname(__FILE__))) . '/_db/settings/logs', $log);
                    die($log);
                }
                continue;
            }

            /**
            * Pula a linha caso nao tenha informacoes (linha em branco)
            */
            if(count($v) < 2){
                continue;
            }

            /**
            * Prepra os dados que alimentarao o array
            */
            @$cpf_cnpj = !empty($v[$pos_lay['NRF']])?trim($v[$pos_lay['NRF']]):null;
            @$nome_razao = !empty($v[$pos_lay['NOME']])?trim($v[$pos_lay['NOME']]):null;
            @$mae = !empty($v[$pos_lay['MAE']])?trim($v[$pos_lay['MAE']]):null;
            @$sexo = !empty($v[$pos_lay['SEXO']])?trim($v[$pos_lay['SEXO']]):null;
            @$dt_nascimento = !empty($v[$pos_lay['DT_NASCIMENTO']])?trim($v[$pos_lay['DT_NASCIMENTO']]):null;
            @$ddd = !empty($v[$pos_lay['DDD']])?$v[$pos_lay['DDD']]:null;
            @$telefone = !empty($v[$pos_lay['FONE']])?$v[$pos_lay['FONE']]:null;
            @$tel_full = "0{$ddd}{$telefone}";
            @$cep = !empty($v[$pos_lay['CEP']])?trim($v[$pos_lay['CEP']]):null;
            @$cod_end = null;
            @$complemento = !empty($v[$pos_lay['INS_COMPL']])?trim($v[$pos_lay['INS_COMPL']]):null;
            @$numero = !empty($v[$pos_lay['INS_NUM_EN']])?trim($v[$pos_lay['INS_NUM_EN']]):null;
            @$data_atualizacao = !empty($v[$pos_lay['DATA_ATUALIZACAO']])?'2012-01-01':null;
            @$rua = !empty($v[$pos_lay['INS_TP_END']])?trim($v[$pos_lay['INS_TP_END']]):null;
            @$nome_rua = !empty($v[$pos_lay['INS_ENDERE']])?trim($v[$pos_lay['INS_ENDERE']]):null;
            @$bairro = !empty($v[$pos_lay['INS_BAIRRO']])?trim($v[$pos_lay['INS_BAIRRO']]):null;
            @$cidade = !empty($v[$pos_lay['CIDADE']])?trim($v[$pos_lay['CIDADE']]):null;
            @$uf = !empty($v[$pos_lay['UF']])?trim($v[$pos_lay['UF']]):null;


            /**
            * Prepara o array para o formato aceito no controller
            */
            $map[uniqid()] = array(
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
        }
        fclose ($txt);

        return $map;
    }

    public function offset($doc){
        $this->deleteAll(array('NattFixoPessoa.CPF_CNPJ' => $doc), false);
        $this->NattFixoTelefone->deleteAll(array('NattFixoTelefone.CPF_CNPJ' => $doc), false);
    }
}