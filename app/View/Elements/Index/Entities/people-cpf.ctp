<div class="control-group">
    <label class="control-label"><?php echo __('Name')?></label>
    <div class="controls">
        <?php echo $people['Entity']['name']?>
    </div>
</div>

<div class="control-group">
    <label class="control-label"><?php echo __('Mother')?></label>
    <div class="controls">
        <?php echo !empty($people['Entity']['mother'])?$this->Html->link($people['Entity']['mother'], array('controller' => '', 'action' => ''), array('escape' => false)):'<small>' . __('Not Found') . '</small>';?>
    </div>
</div>

<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo __('CPF')?></label>
        <div class="controls">
            <?php echo $people['Entity']['doc']?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Updated')?></label>
        <div class="controls">
            <?php echo $people['Entity']['modified']?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Gender')?></label>
        <div class="controls">
            <i class="glyphicon-<?php echo strtolower($people['Entity']['gender_str'])?>"></i>
            <?php echo !empty(__($people['Entity']['gender_str']))?__($people['Entity']['gender_str']):'<small>' . __('Not Found') . '</small>';?>
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
            <?php echo !empty($people['Entity']['birthday'])?$people['Entity']['birthday']:'<small>' . __('Not Found') . '</small>';?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Age')?></label>
        <div class="controls">
            <?php echo !empty($people['Entity']['age'])?$people['Entity']['age']:'<small>' . __('Not Found') . '</small>';?>
        </div>
    </div>
</div>