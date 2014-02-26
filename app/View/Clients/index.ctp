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
unset($columns);
$columns['id'] = $this->AppForm->input("", array('id' => 'check-all', 'type' => 'checkbox', 'template' => 'form-input-clean'));
$columns['action'] = 'Ações';
$columns['contact_name'] = 'Contrato';
$columns['fancy_name'] = 'Nome';
$columns['tel1'] = 'Telefone';
$columns['state_id'] = 'UF';

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
        $v[$modelClass]['action_width'] = '170px';

        $v[$modelClass]['action'] = $this->element('Index/Clients/action', array('id' => $v[$modelClass]['id'], 'cnpj' => $v[$modelClass]['cnpj']));
        $v[$modelClass]['id'] = $this->AppForm->input("{$modelClass}.id.{$k}", array('type' => 'checkbox', 'template' => 'form-input-clean', 'value' => $v[$modelClass]['id']));
        $v[$modelClass]['state_id'] = $v['State']['uf'];
        $v[$modelClass]['tel1'] = $this->AppUtils->tel($v[$modelClass]['tel1']);
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