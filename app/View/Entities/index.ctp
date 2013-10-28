<?php 
$params = $this->params['named'];

/**
* Adiciona o painel de funcoes da grid
*/
echo $this->element('Index/Entities/panel');

/**
* Inicia a montagem da grid
*/
echo $this->AppGrid->create($modelClass, array('id' => 'index-table', 'tableClass' => 'table table-hover table-nomargin'));

/**
* Monta o cabeçalho
*/
unset($columns);
$columns['id'] = $this->AppForm->input("", array('id' => 'check-all', 'type' => 'checkbox', 'template' => 'form-input-clean'));
$columns['doc'] = __('Document');
$columns['type'] = __('Type');
$columns['name'] = __('Name');
$columns['age'] = __('Age');
$columns['state'] = __('State');
$columns['city'] = __('City');
$columns['action'] = __('Actions');

if(isset($params['landline'])){
    $columns['ddd'] = __('DDD');
    $columns['tel'] = __('Tel');
    $columns['year'] = __('Year');
}else if(isset($params['zipcode']) || isset($params['street'])){
    $columns['street'] = __('Street');
    $columns['number'] = __('Number');
    $columns['zipcode'] = __('Zipcode');
    $columns['year'] = __('Year');
}


echo $this->Html->tag('thead', $this->AppGrid->tr($columns));
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
        $v[$modelClass]['type_width'] = '50';
        $v[$modelClass]['age_width'] = '10';
        $v[$modelClass]['state_width'] = '30';
        $v[$modelClass]['action_width'] = '10';
        $v[$modelClass]['ddd_width'] = '10';
        $v[$modelClass]['year_width'] = '10';
        $v[$modelClass]['tel_width'] = '40';
    	
        $v[$modelClass]['action'] = $this->element('Index/Entities/action', array('id' => $v[$modelClass]['id']));
        $v[$modelClass]['id'] = $this->AppForm->input("{$modelClass}.id.{$k}", array('type' => 'checkbox', 'template' => 'form-input-clean', 'value' => $v[$modelClass]['id'], 'placeholder' => $v[$modelClass][$fieldText]));

        switch ($v[$modelClass]['type']) {
            case TP_CPF:
                $v[$modelClass]['type'] = 'Física';
                break;
            case TP_CNPJ:
                $v[$modelClass]['type'] = 'Jurídica';
                break;
            case TP_AMBIGUO:
                $v[$modelClass]['type'] = 'Ambíguo';
                break;
            case TP_INVALID:
                $v[$modelClass]['type'] = 'Inválido';
                break;
        }
        $v[$modelClass]['state'] = isset($v['Address'][0]['state'])?$v['Address'][0]['state']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
        $v[$modelClass]['city'] = isset($v['Address'][0]['city'])?$v['Address'][0]['city']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
        $v[$modelClass]['age'] = isset($v[$modelClass]['age'])?$v[$modelClass]['age']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';

        if(isset($params['landline'])){
            $v[$modelClass]['ddd'] = isset($v['Landline'][0]['ddd'])?$v['Landline'][0]['ddd']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
            $v[$modelClass]['tel'] = isset($v['Landline'][0]['tel'])?$v['Landline'][0]['tel']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
            $v[$modelClass]['year'] = isset($v['Landline'][0]['Association']['year'])?$v['Landline'][0]['Association']['year']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
        }else if(isset($params['zipcode']) || isset($params['street'])){
            $v[$modelClass]['street'] = isset($v['Address'][0]['street'])?$v['Address'][0]['street']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
            $v[$modelClass]['number'] = isset($v['Address'][0]['number'])?$v['Address'][0]['number']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
            $v[$modelClass]['zipcode'] = isset($v['Address'][0]['zipcode'])?$v['Address'][0]['zipcode']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
            $v[$modelClass]['year'] = isset($v['Address'][0]['Association']['year'])?$v['Address'][0]['Association']['year']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';
        }        

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