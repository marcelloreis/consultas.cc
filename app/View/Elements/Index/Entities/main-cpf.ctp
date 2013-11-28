<div class="control-group">
    <label class="control-label"><?php echo __('Name')?></label>
    <div class="controls">
        <?php echo $entity['Entity']['name']?>
    </div>
</div>

<div class="control-group">
    <label class="control-label"><?php echo __('Mother')?></label>
    <div class="controls">
        <?php echo !empty($entity['Entity']['mother'])?$this->Html->link($entity['Entity']['mother'], array('name' => $entity['Entity']['mother']), array('escape' => false)):$this->element('Components/Entities/notfound');?>
    </div>
</div>

<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo __('CPF')?></label>
        <div class="controls">
            <?php echo $this->AppUtils->cpf($entity['Entity']['doc'])?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Updated')?></label>
        <div class="controls">
            <?php echo $this->AppUtils->dt2br($entity['Entity']['modified'], true)?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Gender')?></label>
        <div class="controls">
            <i class="glyphicon-<?php echo strtolower($entity['Entity']['gender_str'])?>"></i>
            <?php echo !empty($entity['Entity']['gender_str'])?__($entity['Entity']['gender_str']):$this->element('Components/Entities/notfound');?>
        </div>
    </div>
</div>

<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo __('Person')?></label>
        <div class="controls">
            <?php 
            switch ($entity['Entity']['type']) {
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
            <?php echo !empty($entity['Entity']['birthday'])?$this->AppUtils->dt2br($entity['Entity']['birthday']):$this->element('Components/Entities/notfound');?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Age')?></label>
        <div class="controls">
            <?php echo !empty($entity['Entity']['age'])?$entity['Entity']['age']:$this->element('Components/Entities/notfound');?>
        </div>
    </div>
</div>

<!-- Linha divisora -->
<div class="control-group"></div>

<!-- Telefones -->
<div class="control-group">
    <label class="control-label" style="padding:10px 0 10px 30px; width:auto;"><i class="icon-phone"></i>&nbsp;<?php echo __('Telephones')?></label>
</div>
<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo __('Mobile')?></label>
        <div class="controls">
            <?php echo !empty($entity['Mobile'][key($entity['Mobile'])]['tel_full'])?$this->html->link($entity['Mobile'][key($entity['Mobile'])]['tel_txt'], array('action' => 'mobile', $entity['Mobile'][key($entity['Mobile'])]['ddd'], $entity['Mobile'][key($entity['Mobile'])]['tel'])):$this->element('Components/Entities/notfound')?>
        </div>
    </div>
</div>
<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo __('Landline')?></label>
        <div class="controls">
            <?php echo !empty($entity['Landline'][key($entity['Landline'])]['tel_full'])?$this->html->link($entity['Landline'][key($entity['Landline'])]['tel_txt'], array('action' => 'landline', $entity['Landline'][key($entity['Landline'])]['ddd'], $entity['Landline'][key($entity['Landline'])]['tel'])):$this->element('Components/Entities/notfound')?>
        </div>
    </div>
</div>

<!-- Linha divisora -->
<div class="control-group"></div>

<!-- Endereço -->
<div class="control-group">
    <label class="control-label" style="padding:10px 0 10px 30px; width:auto;"><i class="glyphicon-globe"></i>&nbsp;<?php echo __('Address')?></label>
</div>

<div class="control-group">
    <label class="control-label"><?php echo !empty($entity['Address'][key($entity['Address'])]['type_address'])?$entity['Address'][key($entity['Address'])]['type_address']:$this->element('Components/Entities/notfound');?></label>
    <div class="controls">
        <?php echo !empty($entity['Address'][key($entity['Address'])]['street'])?$entity['Address'][key($entity['Address'])]['street']:$this->element('Components/Entities/notfound');?>
    </div>
</div>

<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo __('Number')?></label>
        <div class="controls">
            <?php echo !empty($entity['Address'][key($entity['Address'])]['number'])?$entity['Address'][key($entity['Address'])]['number']:$this->element('Components/Entities/notfound');?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Complement')?></label>
        <div class="controls">
            <?php echo !empty($entity['Address'][key($entity['Address'])]['complement'])?$entity['Address'][key($entity['Address'])]['complement']:$this->element('Components/Entities/notfound');?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('Neighborhood')?></label>
        <div class="controls">
            <?php echo !empty($entity['Address'][key($entity['Address'])]['neighborhood'])?$entity['Address'][key($entity['Address'])]['neighborhood']:$this->element('Components/Entities/notfound');?>
        </div>
    </div>
</div>

<div class="span6">
    <div class="control-group">
        <label class="control-label"><?php echo __('Zipcode')?></label>
        <div class="controls">
            <?php echo !empty($entity['Address'][key($entity['Address'])]['zipcode'])?$this->AppUtils->zipcode($entity['Address'][key($entity['Address'])]['zipcode']):$this->element('Components/Entities/notfound');?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('State')?></label>
        <div class="controls">
            <?php echo !empty($entity['Address'][key($entity['Address'])]['state'])?$entity['Address'][key($entity['Address'])]['state']:$this->element('Components/Entities/notfound');?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('City')?></label>
        <div class="controls">
            <?php echo !empty($entity['Address'][key($entity['Address'])]['city'])?$entity['Address'][key($entity['Address'])]['city']:$this->element('Components/Entities/notfound');?>
        </div>
    </div>
</div>
