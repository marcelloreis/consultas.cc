<?php 
/**
* Oculta o sidebar
*/
$this->assign('sidebar-class-hidden', 'nav-hidden');

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
$columns['sms_campaign_id'] = 'Campanha';
$columns['cost'] = 'Custo';
unset($columns['entity_id']);
unset($columns['mobile_id']);
echo $this->Html->tag('thead', $this->AppGrid->tr($columns));
/**
* Monta o body
*/
if(count($smsSent)){
    $body = '';
    foreach($smsSent as $k => $v){
        /**
        * Seta as larguras das colunas
        */
        $v[$modelClass]['id'] = $this->AppForm->input("{$modelClass}.id.{$k}", array('type' => 'checkbox', 'template' => 'form-input-clean', 'value' => $v[$modelClass]['id'], 'placeholder' => $v[$modelClass][$fieldText]));
        $v[$modelClass]['sms_campaign_id'] = $v['SmsCampaign']['title'];
        $v[$modelClass]['status'] = $this->AppUtils->boolTxt($v[$modelClass]['status'], 'Enviado', 'Aguardando');
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