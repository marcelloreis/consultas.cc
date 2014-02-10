<?php 
/**
* Insere o sidebar especifico de usuarios
*/
$this->start('sidebar');
echo $this->element('Components/Users/sidebar-accounts');
$this->end();

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
$columns['action'] = 'Ações';

unset($columns['client_id']);
unset($columns['password']);
unset($columns['google_token']);
unset($columns['google_calendar_key']);
unset($columns['change_pass_token']);
unset($columns['change_pass_expire']);
unset($columns['google_calendar_key']);
unset($columns['given_name']);
unset($columns['theme']);
unset($columns['picture_dir']);
unset($columns['avatar_27']);
unset($columns['avatar_158']);
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
        $v[$modelClass]['picture_width'] = '70px';
        
        $v[$modelClass]['action'] = $this->element('Index/Users/action-accounts', array('id' => $v[$modelClass]['id'], 'client_id' => $v[$modelClass]['client_id']));
        $v[$modelClass]['client_id'] = $v['Client']['fancy_name'];
        $v[$modelClass]['id'] = $this->AppForm->input("{$modelClass}.id.{$k}", array('type' => 'checkbox', 'template' => 'form-input-clean', 'value' => $v[$modelClass]['id'], 'placeholder' => $v[$modelClass][$fieldText]));
        $v[$modelClass]['group_id'] = $v['Group']['name'];
        $v[$modelClass]['status'] = $this->AppUtils->boolTxt($v[$modelClass]['status'], 'Ativo', 'Inativo');
        $v[$modelClass]['unlimited'] = $this->AppUtils->boolTxt($v[$modelClass]['unlimited']);
        $avatar = isset($v[$modelClass]['avatar_27']) && !empty($v[$modelClass]['avatar_27'])?$v[$modelClass]['avatar_27']:'avatar-27x27.png';
        $v[$modelClass]['picture'] = $this->Html->image($avatar, array('id' => "avatar-{$v[$modelClass]['id']}", 'class' => 'img-polaroid'));

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