<?php 
/**
* Alterando o titulo padrao da view
*/
$this->start('title-view');
echo $this->element('Components/Entities/title-view');
$this->end();


/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/datatable/TableTools'));
$this->end();

echo $this->Html->script(array(
    'plugins/chosen/chosen.jquery.min', 
    'plugins/datatable/jquery.dataTables.min',
    'plugins/datatable/TableTools.min',
    'plugins/datatable/ColReorderWithResize',
    'plugins/datatable/ColVis.min',
    'plugins/datatable/jquery.dataTables.columnFilter',
    'plugins/datatable/jquery.dataTables.grouping'
    ),
    array('defer' => true));

$params = $this->params['named'];

/**
* Inicia a montagem da grid
*/
echo $this->AppGrid->create($modelClass, array('id' => 'index-table', 'tableClass' => 'table table-hover table-nomargin dataTable'));

/**
* Monta o cabeçalho
*/
unset($columns);
$columns['uf'] = 'UF';
$columns['city'] = 'Cidade';
$columns['doc'] = 'Documento';
$columns['name'] = 'Nome';
$columns['age'] = 'Idade';
$columns['action'] = 'Ações';


echo $this->Html->tag('thead', $this->AppGrid->tr($columns, array('sort' => false)));
/**
* Monta o body
*/
$map = strtolower($modelClass);
if(count($$map)){    
// debug($$map);die;
    $body = '';
    foreach($$map as $k => $v){
        /**
        * Seta as larguras das colunas
        */
        $v[$modelClass]['doc_width'] = '50';
        $v[$modelClass]['age_width'] = '10';
        $v[$modelClass]['uf_width'] = '45';
    	
        $v[$modelClass]['action'] = $this->element('Index/Entities/action', array('id' => $v[$modelClass]['id']));

        if(!empty($v['Address'][0])){
            $v['Address']['state'] = $v['Address'][0]['state'];
            $v['Address']['city'] = $v['Address'][0]['city'];
        }

        $v[$modelClass]['uf'] = !empty($v['Address']['state'])?$this->Html->image('flags/mini/' . strtolower($v['Address']['state']) . '.gif', array('class' => 'img-polaroid', 'style' => 'margin-right:5px;')) . $v['Address']['state']:$this->element('Components/Entities/notfound');
        $v[$modelClass]['city'] = !empty($v['Address']['city'])?$v['Address']['city']:$this->element('Components/Entities/notfound');
        $v[$modelClass]['age'] = !empty($v[$modelClass]['age'])?$v[$modelClass]['age']:$this->element('Components/Entities/notfound');
        $v[$modelClass]['doc'] = ($v[$modelClass]['type'] == TP_CNPJ)?$this->AppUtils->cnpj($v[$modelClass]['doc']):$this->AppUtils->cpf($v[$modelClass]['doc']);
        $body .= $this->AppGrid->tr($v[$modelClass]);
    }
    echo $this->Html->tag('tbody', $body);
}

/**
* Fecha a montagem da grid
*/                
echo $this->AppGrid->end();