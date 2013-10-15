<?php
/**
* Inicializa as variaveis que guardao os IDs dos grupos ou usuario que estarao sendo editados
*/
$aro_id = (isset($this->params['named']['aro_id']) && !empty($this->params['named']['aro_id']))?$this->params['named']['aro_id']:'';

/**
* Adiciona o topo com o titulo da grid
*/
echo $this->element('Index/header', array('title' => __('%s Permissions', __($aro_list[$aro_id]))));

/**
* Adiciona o painel de funcoes da grid
*/
echo $this->element('Acl/index-panel');

/**
* Inicia a montagem da grid
*/
echo $this->AppGrid->create($modelClass, array('tableClass' => "table table-striped b-t m-b-none"));

/**
* Monta o cabeçalho
*/
$columns['controllers'] = __('Controllers');
echo $this->Html->tag('thead', $this->AppGrid->tr($columns, array('sort' => false)));

/**
* Monta o body
*/
if(count($acos)){
    /**
    * Guarda o ID Raiz do controller e dos Plugins
    */
    $matriz_id = array($acos[0]['Aco']['id']);
    foreach ($acos as $k => $v) {
        if(CakePlugin::loaded($v['Aco']['alias'])){
            $matriz_id[] = $v['Aco']['id'];
        }
    }

    /**
    * Remove o ACO Raiz
    */
    array_shift($acos);

    /**
    * Agrupa os actions pelos controllers
    */
    foreach($acos as $k => $v){
        //Verifica se o ACO é um controller
        $isController = (in_array($v['Aco']['parent_id'], $matriz_id));

        if($isController){
            $controller_id = $v['Aco']['id'];
            $controller_alias = str_replace('controllers/', '', $v['Action']);
        }else{
            $actions[$controller_id]['Model'] = Inflector::classify($controller_alias);
            $actions[$controller_id]['Controller'] = $controller_alias;

            /**
            * Carrega a permissao atual do ARO sobre ACO em questao
            */
            $v['Aco']['input_name'] = str_replace('/', ':', "Perms.{$v['Action']}.{$aroAlias}:{$aro_id}");
            $v['Aco']['allowed'] = $this->Form->value($v['Aco']['input_name']);
            $v['Aco']['Action'] = $v['Action'];

            /**
            * Agrupa os actions por 2 grupos: Comuns e Outros
            */
            $actions_group = preg_match('/index|edit|view/si', $v['Aco']['alias'])?'common':'others';
            $actions[$controller_id][$actions_group][] = $v['Aco'];
        }
    }

    /**
    * Monta o body
    */
    $body = '';
    foreach ($actions as $k => $v) {
        $tr['controllers'] = $this->element('Acl/permissions-actions', array('controller' => __($v['Controller']), 'key' => "collapse-{$k}", 'common' => $v['common'], 'others' => $v['others']));
        $body .= $this->AppGrid->tr($tr);                                
    }

    echo $this->Html->tag('tbody', $body);
}

/**
* Fecha a montagem da grid
*/                
echo $this->AppGrid->end();

/**
* Adiciona o rodapé da grid
*/
echo $this->element('Index/footer', array('paginate' => false));
?>        
