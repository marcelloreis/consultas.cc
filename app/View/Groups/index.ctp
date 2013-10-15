<?php 
/**
* Adiciona o topo com o titulo da grid
*/
echo $this->element('Index/header');

/**
* Adiciona o painel de funcoes da grid
*/
echo $this->element('Index/panel');

/**
* Inicia a montagem da grid
*/
echo $this->AppGrid->create($modelClass);

/**
* Monta o cabeçalho
*/
$columns['id'] = '<input type="checkbox" name="" class="e-checkbox-trigger"/>';
$columns['action'] = __('Edit');
echo $this->Html->tag('thead', $this->AppGrid->tr($columns));

/**
* Monta o body
*/
$map = strtolower($modelClass);
if(count($$map)){
    $body = '';
    foreach($$map as $k => $v){
        $v[$modelClass]['action'] = $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $v[$modelClass]['id']), array('escape' => false));
        $v[$modelClass]['id'] = $this->AppForm->input("{$modelClass}.id.{$k}", array('type' => 'checkbox', 'template' => 'form-input-clean', 'value' => $v[$modelClass]['id'], 'placeholder' => $v[$modelClass][$fieldText]));
        $body .= $this->AppGrid->tr($v[$modelClass]);
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
echo $this->element('Index/footer');