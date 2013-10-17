<?php 
/**
* Adiciona o painel de funcoes da grid
*/
echo $this->element('Index/panel');

/**
* Inicia a montagem da grid
*/
echo $this->AppGrid->create($modelClass, array('id' => 'index-table', 'tableClass' => 'table table-hover table-nomargin'));

/**
* Monta o cabeçalho
*/
$columns['id'] = $this->AppForm->input("", array('id' => 'check-all', 'type' => 'checkbox', 'template' => 'form-input-clean'));
$columns['action'] = __('Actions');
unset($columns['password']);
unset($columns['google_token']);
unset($columns['google_calendar_key']);
unset($columns['given_name']);
echo $this->Html->tag('thead', $this->AppGrid->tr($columns));

/**
* Monta o body
*/
$map = strtolower($modelClass);
if(count($$map)){
    $body = '';
    foreach($$map as $k => $v){

        //Seta as larguras das colunas
        $v[$modelClass]['picture_width'] = '70px';
        
        $v[$modelClass]['action'] = $this->element('Index/action', array('id' => $v[$modelClass]['id']));
        $v[$modelClass]['id'] = $this->AppForm->input("{$modelClass}.id.{$k}", array('type' => 'checkbox', 'template' => 'form-input-clean', 'value' => $v[$modelClass]['id'], 'placeholder' => $v[$modelClass][$fieldText]));
        $v[$modelClass]['group_id'] = $v['Group']['name'];
        $v[$modelClass]['status'] = $this->AppUtils->boolTxt($v[$modelClass]['status'], 'Ativo', 'Inativo');
        $avatar = isset($v[$modelClass]['picture']) && !empty($v[$modelClass]['picture'])?$v[$modelClass]['picture']:'avatar.jpg';
        $v[$modelClass]['picture'] = $this->Html->image($avatar, array('id' => "avatar-{$v[$modelClass]['id']}", 'width' => 50, 'height' => 50));

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