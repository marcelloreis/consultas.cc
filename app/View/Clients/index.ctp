<?php 
/**
* Adiciona o painel de funcoes da grid
*/
echo $this->element('Index/panel');

/**
* Inicia a montagem da grid
*/
echo $this->AppGrid->create($modelClass, array('id' => $modelClass, 'tableClass' => 'index-table table table-hover table-nomargin'));

/**
* Monta o cabeçalho
*/
$columns['id'] = $this->AppForm->input("", array('id' => 'check-all', 'type' => 'checkbox', 'template' => 'form-input-clean'));
$columns['action'] = __('Actions');
$columns['contact_name'] = __('Contact');
$columns['fancy_name'] = __('Name');
$columns['tel1'] = __('Telephone');
unset($columns['cnpj']);
unset($columns['corporate_name']);
unset($columns['address']);
unset($columns['complement']);
unset($columns['number']);
unset($columns['neighborhood']);
unset($columns['city_id']);
unset($columns['tel2']);
unset($columns['tel3']);
unset($columns['maturity']);
unset($columns['limit_exceeded']);
unset($columns['repeat_limit_exceeded']);
echo $this->Html->tag('thead', $this->AppGrid->tr($columns));

/**
* Monta o body
*/
$map = strtolower($modelClass);
if(count($$map)){
    $body = '';
    foreach($$map as $k => $v){
        /**
        * Seta as larguras das colunas
        */
        $v[$modelClass]['action_width'] = '140px';

        $v[$modelClass]['action'] = $this->element('Index/Packages/action', array('id' => $v[$modelClass]['id']));
        $v[$modelClass]['id'] = $this->AppForm->input("{$modelClass}.id.{$k}", array('type' => 'checkbox', 'template' => 'form-input-clean', 'value' => $v[$modelClass]['id']));
        $v[$modelClass]['package_id'] = $v['Package']['name'];
        $v[$modelClass]['state_id'] = $v['State']['uf'];
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