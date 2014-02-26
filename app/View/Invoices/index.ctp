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
$columns['package_id'] = 'Pacote';
$columns['client_id'] = 'Cliente';
$columns['maturity'] = 'Vencimento';
$columns['is_paid'] = 'Pago';
$columns['is_signature'] = 'Assinatura';
$columns['is_separete'] = 'Avulso';
$columns['download_qt'] = 'Baixado';
$columns['value_exceeded'] = 'Excedido';
$columns['value'] = 'Valor';
echo $this->Html->tag('thead', $this->AppGrid->tr($columns));

/**
* Monta o body
*/
$map = strtolower($modelClass);
if(count($$map)){
    $body = '';
    foreach($$map as $k => $v){
        $v[$modelClass]['action_width'] = '140';
        
        /**
        * Seta as larguras das colunas
        */
        $v[$modelClass]['action'] = $this->element('Index/Invoices/action', array('id' => $v[$modelClass]['id'], 'token' => $v[$modelClass]['token']));
        $v[$modelClass]['id'] = $this->AppForm->input("{$modelClass}.id.{$k}", array('type' => 'checkbox', 'template' => 'form-input-clean', 'value' => $v[$modelClass]['id']));
        $v[$modelClass]['package_id'] = $v['Package']['name'];
        $v[$modelClass]['client_id'] = $v['Client']['fancy_name'];
        $v[$modelClass]['is_paid'] = $this->AppUtils->boolTxt($v[$modelClass]['is_paid'], 'Sim', 'Não');
        $v[$modelClass]['is_signature'] = $this->AppUtils->boolTxt($v[$modelClass]['is_signature'], 'Sim', 'Não');
        $v[$modelClass]['is_separete'] = $this->AppUtils->boolTxt($v[$modelClass]['is_separete'], 'Sim', 'Não');
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