<div class="control-group">
    <label class="control-label"><?php echo __('Name')?></label>
    <div class="controls">
        <?php echo $people['Entity']['name']?>
    </div>
</div>

<div class="control-group">
    <label class="control-label"><?php echo __('Mother')?></label>
    <div class="controls">
        <?php 
        $url = isset($family['Family']['mother']['Entity']['id'])?array($family['Family']['mother']['Entity']['id']):array('name' => $people['Entity']['mother']);
        ?>
        <?php echo !empty($people['Entity']['mother'])?$this->Html->link($people['Entity']['mother'], $url, array('escape' => false)):'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';?>
    </div>
</div>

<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo __('CPF')?></label>
        <div class="controls">
            <?php echo $this->AppUtils->cpf($people['Entity']['doc'])?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Updated')?></label>
        <div class="controls">
            <?php echo $this->AppUtils->dt2br($people['Entity']['modified'], true)?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Gender')?></label>
        <div class="controls">
            <i class="glyphicon-<?php echo strtolower($people['Entity']['gender_str'])?>"></i>
            <?php echo !empty($people['Entity']['gender_str'])?__($people['Entity']['gender_str']):'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';?>
        </div>
    </div>
</div>

<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo __('Person')?></label>
        <div class="controls">
            <?php 
            switch ($people['Entity']['type']) {
                case TP_CPF:
                    echo 'Física';
                    break;
                case TP_AMBIGUO:
                    echo 'Ambíguo';
                    break;
                case TP_INVALID:
                    echo 'Documento Inválido';
                    break;
            }
            ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Birthday')?></label>
        <div class="controls">
            <?php echo !empty($people['Entity']['birthday'])?$this->AppUtils->dt2br($people['Entity']['birthday']):'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Age')?></label>
        <div class="controls">
            <?php echo !empty($people['Entity']['age'])?$people['Entity']['age']:'<small style="color: #999999;display: block;line-height: 20px;">— ' . __('Not Found') . '</small>';?>
        </div>
    </div>
</div>