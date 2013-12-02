<?php 
$this->start('sidebar');
echo $this->element('Components/Translations/sidebar');
$this->end();
?>

<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-vertical form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
            </div>
            <?php echo $this->AppForm->input('msgid', array('label' => 'Mensagem'))?>
            <?php echo $this->AppForm->input('msgstr', array('label' => 'Tradução'))?>
            <?php echo $this->AppForm->btn();?>
        <?php echo $this->AppForm->end()?>
    </div>
</div>
