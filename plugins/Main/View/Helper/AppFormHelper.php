<?php

class AppFormHelper extends AppHelper {

    public $helpers = array('Main.AppUtils', 'Main.AppPermissions', 'Form');

    private $model;
    private $fields;
    private $validate;
    private $modelClass;
    //Atributos do INPUT
    private $input;
    private $inputId;
    private $inputLabel;
    //Atributos diversos
    private $separatorTemplate;
    private $defaultSize;

    public function __construct(View $view, $params){
        parent::__construct($view, $params);

        if(count($params)){
            //Carrega os campos do formulario que esta sendo montado
            $this->fields = $params['fields'];
            //Carrega as regras de validacao do varmulario
            $this->validate = $params['validate'];
            //Carrega o nome do Model
            $this->modelClass = $params['modelClass'];
        }else{
            $this->fields = (isset($this->Form->fieldset[$this->model]['fields']))
                            ?$this->Form->fieldset[$this->model]['fields']
                            :array();            
        }
    }

    public function create($model, $options = array()) {
        //Carrega o nome do modelo do formulario que esta sendo montado
        $this->model = $model;
        //Desativa as DIVs e LABELs criadas pelo cake
        $options['inputDefaults'] = array('label' => false, 'div' => false, 'hiddenField' => false, 'error' => false);
        // $options['error'] = false;
        //Seta as classes de tamanhos dos inputs
        if(isset($options['defaultSize'])){
            $this->defaultSize = $options['defaultSize'];
        }

        //O formulario pode ser montado apartir da AppGrid, entao é verificado se a classe vem setada de lá
        if(isset($options['classForm'])){
            $options['class'] = $options['classForm'];
            unset($options['classForm']);
        }

        //Cria o formulario
        $form = $this->Form->create($this->model, $options); 

        //Retona o formulario montado
        return $form;
    }

    public function end($options = array()) {
        $options = array('label' => false, 'div' => false, 'type' => 'hidden');
        return $this->Form->end($options);
    }    

    public function input($fieldName, $options = array()) {
        //Guarda o label requisistado    
        $this->inputLabel = isset($options['label']) ? __(ucfirst(str_replace('_', ' ', $options['label']))) : __(ucfirst(str_replace('_', ' ', $fieldName)));
        //Remove o LABEL padrao do cake
        unset($options['label']);

        //Verifica se o campo é uma chave estrangeira
        if(preg_match('/[a-z].+?_id/', $fieldName) && !isset($options['type'])){
            $options['template'] = 'form-input-fk';
        }


        //Verifica se foi requisitado um template fora do padrao
        $templateName = (isset($options['template']))?$options['template']:'form-input';

        //Carrega o template do elemento
        $inputTemplate = $this->AppUtils->loadTemplate($templateName);

        //Configura a validacao via HTML5 no campo
        if(isset($this->validate[$fieldName])){
            $message = isset($this->validate[$fieldName]['notempty']['message'])
                ?$this->validate[$fieldName]['notempty']['message']
                :'Este campo deve ser preenchido corretamente.';

            $options['x-moz-errormessage'] = $message;
            $options['title'] = $message;
        }
        //Inicializa a variavel $options['class']
        if(!isset($options['class'])){
            if(!isset($options['class'])){
                $options['class'] = '';
            }
            //Insere a class da mascara de acordo com o tipo do campo
            $options['class'] .= ' ' . $this->classMaskInputs($fieldName, $options);
        }

        //Insere ao tipo do input de acordo com o nome
        if (!isset($options['type'])) {
            $options['type'] =  $this->typeInputs($fieldName);
        }

        //Insere os options de caso o input seja do tipo select e os options ainda nao estejam setados
        if ((isset($options['template']) && $options['template'] == 'form-input-fk') || ($options['type'] == 'select' && !isset($options['options']))) {
            //Insere a label "Selecione" caso nao tenha sido setado nenhuma label
            $options['empty'] =  (!isset($options['empty']))?'Selecione':$options['empty'];
            $options['options'] =  $this->optionsSelect($fieldName);
        }

        //Insere a o nome do campo em placeholder caso nao tenha sido setado nenhum valor
        if ($options['type'] != 'checkbox' && !isset($options['placeholder'])) {
            $options['placeholder'] = __($this->inputLabel);
        }

        //Insere o atributo 'disabled' caso o formulario seja somente para visualizacao
        if($this->params['action'] == 'view'){
            $options['disabled'] = 'disabled';
        }

        //Carrega os valores padroes do input
        $defaults = array(
            'sizeClass' => $this->defaultSize,
            'label' => __($this->inputLabel),
            'input' => $this->Form->input($fieldName, $options),
            );
        $attr = array_merge($defaults, $options);
        //Altera as chaves do array para o padrao de variaveis do template
        $values = $this->AppUtils->key2var($attr);

        //Carrega as variaveis do template do input com os valores passados por parametro
        $this->input = str_replace(array_keys($values), $values, $inputTemplate);

        //Carrega o ID do input
        preg_match('/id=[\"\'](.*?)[\"\']/si', $this->input, $map);
        $id = !empty($map[1])?$map[1]:null;

        if (isset($id)) {
            $this->inputId = $map[1];
            //Insere o atributo FOR na tag LABEL
            $this->input = preg_replace('/\for=[\"\'].*?[\"\']/si', "", $this->input);
            $this->input = preg_replace('/\<label/si', "<label for=\"{$this->inputId}\" ", $this->input);
        }

        return $this->input;
    }

    public function btn($value=false, $options=array()) {
        $value = !$value?'Salvar':$value;
        /**
         * Libera o acesso ao botao somente se o usuario tiver acesso a ação/função
         */
        // debug($this->params['action']);die;
        if($this->params['action'] != 'view'){
            //Verifica se foi requisitado um template fora do padrao
            $templateName = (isset($options['template']))?$options['template']:'form-input-btn';
            //Carrega o template do elemento
            $inputTemplate = $this->AppUtils->loadTemplate($templateName);
            //Carrega os valores padroes do input
            $defaults = array(
                'class' => 'btn',
                'side' => 'right',
                'value' => __($value)
                );

            $attr = array_merge($defaults, $options);
            //Altera as chaves do array para o padrao de variaveis do template
            $values = $this->AppUtils->key2var($attr);
            
            //Carrega as variaveis do template do input com os valores passados por parametro
            $this->input = str_replace(array_keys($values), $values, $inputTemplate);

            return $this->input;
        }
    }

    public function separator($options=array()) {
        //Verifica se foi requisitado um template fora do padrao
        $templateName = (isset($options['template']))?$options['template']:'form-input-separator';
        //Carrega o template do elemento
        $inputTemplate = $this->AppUtils->loadTemplate($templateName);

        //Carrega os valores padroes do input
        $defaults = array();

        $attr = array_merge($defaults, $options);
        //Altera as chaves do array para o padrao de variaveis do template
        $values = $this->AppUtils->key2var($attr);
        //Carrega as variaveis do template do input com os valores passados por parametro
        $this->input = str_replace(array_keys($values), $values, $inputTemplate);

        return $this->input;
    }

    private function classMaskInputs($fieldName, $options) {
        //Inicializa a variavel $class
        $class = '';
        $type = isset($options['type'])?$options['type']:null;

        //Verifica se o campo é uma chave estrangeira
        if(!preg_match('/[a-z].+?_id/', $fieldName)){
            if(!$type){
                $type = isset($this->fields[$fieldName])?$this->fields[$fieldName]:false;
            }

            /**
            * Carrega a classe do input de acordo com o seu tipo
            */
            switch ($type) {
                case 'date':
                    $class = 'datepick';
                case 'datetime':
                    //Inicio
                    if (preg_match('/^date_ini|data_inicio|ini|inicio$/si', $fieldName))
                        $class = 'datepick datetimepicker-ini-icon';
                    //Fim
                    // if (preg_match('/^date_end|data_fim|end|fim$/si', $fieldName))
                    //     $class = 'daterangepick';
                break;
                
                case 'integer':
                    $class = 'msk-int';
                break;
                
                case 'float':
                    $class = 'msk-money';
                break;
            }

            if(empty($class)){
                //Cartao
                if (preg_match('/^card|cartao$/si', $fieldName))
                    $class = 'msk-card';
                //Telefones
                if (preg_match('/^tel.*?ne[0-9]?|tel|fone|phone$/si', $fieldName))
                    $class = 'msk-phone';
                //Dinheiro, moeda
                if (preg_match('/^money|moeda|balance|value|price$/si', $fieldName))
                    $class = 'msk-money';
                //CEP
                if (preg_match('/^zipcode|cep|CEP|ZIPCODE$/si', $fieldName))
                    $class = 'msk-zipcode';
                //CPF
                if (preg_match('/^cpf|doc$/si', $fieldName))
                    $class = 'msk-cpf';
                //CNPJ
                if (preg_match('/^cnpj$/si', $fieldName))
                    $class = 'msk-cnpj';
                //Ano
                if (preg_match('/^year|ano$/si', $fieldName))
                    $class = 'msk-4Digits';
                //Mes
                if (preg_match('/^month|mes$/si', $fieldName))
                    $class = 'msk-2Digits';
                //Ano
                if (preg_match('/^day|dia$/si', $fieldName))
                    $class = 'msk-2Digits';
            }
        }        

        return $class;
    }

    private function typeInputs($fieldName) {
        //Inicializa a variavel $typeInput
        $typeInput = null;

        //Verifica se o campo é checkbox de indice
        if(($fieldName === null && $type == 'checkbox') || preg_match('/[a-zA-Z]+?\.id\.([0-9])+/', $fieldName)){
            //Verifica se a requisicao é via ajax
            if($this->params['isAjax']){
                // Verifica se o input esta sendo invocando por um indice HABTM
                if(isset($this->params['named']['fkbox'])){
                    switch ($this->params['named']['fkbox']) {
                        case 'belongsto':
                            $typeInput = 'radio';
                            break;
                    }
                }
            }
        }

        if(!$typeInput && !preg_match('/[a-z].+?_id/', $fieldName)){
            $type = isset($this->fields[$fieldName])?$this->fields[$fieldName]:false;
         
            switch ($type) {
                case 'order':
                case 'date':
                case 'datetime':
                    $typeInput = 'text';
                break;
                
                case 'tinyint':
                case 'boolean':
                    $typeInput = 'checkbox';
                break;
                
                //Por padrao, o cake seta o tipo do  input Integer e Float como number
                case 'integer':
                case 'float':
                    $typeInput = 'text';
                break;
                
                default:
                    //Status
                    if (preg_match('/^sexo.*|sex.*|in_out.*|has_.*|is_.*|check.*|published.*|trashed.*|deleted.*|status.*|active.*$/si', $fieldName)){
                        $typeInput = 'select';
                    }
                break;
            }
        }        
        return $typeInput;
    }

    private function optionsSelect($fieldName) {
        $options = null;
        //Chaves SIM|NAO
        if (preg_match('/in_out.*|has_.*|is_.*|check.*|published.*|trashed.*|deleted.*/si', $fieldName)){
            $options = array('1' => 'Sim', '0' => 'Não');
        }
        //Status
        if (preg_match('/status.*|active.*/si', $fieldName)){
            $options = array('1' => 'Ativo', '0' => 'Inativo');
        }
        //Sexo
        if (preg_match('/sexo.*|sex.*/si', $fieldName)){
            $options = array('1' => 'Feminino', '0' => 'Masculino');
        }

        return $options;
    }
}