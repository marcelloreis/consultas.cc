<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xxlarge', 'classForm' => 'form-vertical form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
            </div>
            <?php 
            echo $this->AppForm->input('group_id');
            echo $this->AppForm->input('name');
            echo $this->AppForm->input('given_name');
            echo $this->AppForm->input('email');
            echo $this->AppForm->input('password');
            echo $this->AppForm->input('picture');
            echo $this->AppForm->input('status');

            echo $this->AppForm->btn('Save changes');
            ?>


        <?php echo $this->AppForm->end()?>
    </div>
</div>

