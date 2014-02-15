<?php 
/**
* Insere o sidebar especifico de usuarios
*/
$this->start('sidebar');
echo $this->element('Components/Campaigns/sidebar-sms');
$this->end();

/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/icheck/all'));
$this->end();

echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'), array('defer' => true));
echo $this->Html->script(array('plugins/wizard/jquery.form.wizard.min'), array('defer' => true));

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
unset($columns);
$columns['id'] = $this->AppForm->input("", array('id' => 'check-all', 'type' => 'checkbox', 'template' => 'form-input-clean'));
$columns['action'] = 'Ações';
$columns['title'] = 'Título';
$columns['status'] = 'Status';
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
        $v[$modelClass]['action'] = $this->element('Index/Campaigns/action-sms', array('id' => $v[$modelClass]['id']));
        $v[$modelClass]['id'] = $this->AppForm->input("{$modelClass}.id.{$k}", array('type' => 'checkbox', 'template' => 'form-input-clean', 'value' => $v[$modelClass]['id'], 'placeholder' => $v[$modelClass][$fieldText]));
        $v[$modelClass]['status'] = $this->AppUtils->boolTxt($v[$modelClass]['status'], 'Ativo', 'Inativo');
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