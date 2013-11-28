<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/datatable/TableTools'));
$this->end();

$this->append('scrips-on-demand');
echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'));
echo $this->Html->script(array('plugins/datatable/jquery.dataTables.min'));
echo $this->Html->script(array('plugins/datatable/TableTools.min'));
echo $this->Html->script(array('plugins/datatable/ColReorderWithResize'));
echo $this->Html->script(array('plugins/datatable/ColVis.min'));
echo $this->Html->script(array('plugins/datatable/jquery.dataTables.columnFilter'));
echo $this->Html->script(array('plugins/datatable/jquery.dataTables.grouping'));
$this->end();

$params = $this->params['named'];

/**
* Inicia a montagem da grid
*/
echo $this->AppGrid->create($modelClass, array('id' => 'index-table', 'tableClass' => 'table table-hover table-nomargin dataTable'));

/**
* Monta o cabeçalho
*/
unset($columns);
$columns['doc'] = __('Document');
$columns['name'] = __('Name');
$columns['mother'] = __('Mother');
$columns['age'] = __('Age');
$columns['action'] = __('Actions');


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
    	
        $v[$modelClass]['action'] = $this->element('Index/Entities/action', array('id' => $v[$modelClass]['id']));

        $v[$modelClass]['mother'] = !empty($v[$modelClass]['mother'])?$v[$modelClass]['mother']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
        $v[$modelClass]['age'] = !empty($v[$modelClass]['age'])?$v[$modelClass]['age']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
        $v[$modelClass]['doc'] = $this->AppUtils->cpf($v[$modelClass]['doc']);
        $body .= $this->AppGrid->tr($v[$modelClass]);
    }
    echo $this->Html->tag('tbody', $body);
}

/**
* Fecha a montagem da grid
*/                
echo $this->AppGrid->end();