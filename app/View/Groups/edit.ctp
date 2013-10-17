 <!-- <section class="g_1">
    <div class="e-block">
        <header>
            <ul class="etabs">
                <?php $id = (isset($this->data[$modelClass]['id']) && !empty($this->data[$modelClass]['id']))?$this->data[$modelClass]['id']:null;?>
                    <li class="<?php echo (isset($this->params['named']['habtm']))?'':'etabs-active';?>"><?php echo $this->Html->link(sprintf(__('Edit %s'), __($modelClass)), array($id))?></li>
                </ul>       
        </header>
        <div style="display:<?php echo (isset($this->params['named']['habtm']))?'none':'block'?>;" class="etabs-content" id="<?php echo $modelClass?>"> 
            <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'g_1_4'))?>
            <?php echo $this->element('toolbar-edit')?>
            <div class="inner-spacer set-cells">
                <?php echo $this->Form->input('id')?>
                <?php echo $this->AppForm->separator()?>
                <?php echo $this->AppForm->input('name')?>
                <?php echo $this->AppForm->separator()?>
            </div>   
            <?php echo $this->AppForm->end()?>
        </div>
    </div>
</section> -->


<?php //echo $this->element('Edit/panel')?>

        <div class="box box-bordered">
            <div class="box-title">
                <?php if($this->params['action'] == 'add' || ($this->params['action'] == 'edit' && !count($this->params['pass']))):?>
                    <h3><i class="icon-plus"></i> <?php echo __('Enter the data for the new record.')?></h3>
                <?php elseif(isset($this->request->data[$modelClass])):?>
                    <h3><?php echo $this->Html->link("<i class=\"icon-time\"> {$this->request->data[$modelClass]['modified']} </i>", '#', array('class' => 'btn btn-warning', 'escape' => false)) . ' ' . __('Last change of this record.')?></h3>
                <?php endif;?>
            </div>
            <div class="box-content nopadding">
                <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-vertical form-bordered form-striped'))?>
                    <div style="display:none;">
                        <?php echo $this->Form->input('id')?>
                    </div>
                    <?php echo $this->AppForm->input('name')?>
                    <?php echo $this->AppForm->btn('Save changes');?>
                <?php echo $this->AppForm->end()?>
            </div>
        </div>
