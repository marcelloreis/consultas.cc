<div class="control-group">
    <label class="control-label"><?php echo __('Company')?></label>
    <div class="controls">
        <?php echo $this->Html->link($people['Entity']['name'], array('controller' => '', 'action' => ''))?>
    </div>
</div>

<div class="span4">
    <div class="control-group">
        <label class="control-label"><?php echo __('CNPJ')?></label>
        <div class="controls">
            <?php echo $people['Entity']['doc']?>
        </div>
    </div>
</div>

<div class="span4">
    <div class="control-group">
        <label class="control-label"><?php echo __('Person')?></label>
        <div class="controls">
            Jurídica
        </div>
    </div>
</div>

<div class="span4">
    <div class="control-group">
        <label class="control-label"><?php echo __('Updated')?></label>
        <div class="controls">
            <?php echo $people['Entity']['modified']?>
        </div>
    </div>
</div>