<div class="box-title">
    <?php if($this->params['action'] == 'add' || ($this->params['action'] == 'edit' && !count($this->params['pass']))):?>
        <h3><i class="icon-plus"></i> <?php echo __('Enter the data for the new record.')?></h3>
    <?php elseif(isset($this->request->data[$modelClass])):?>
        <h3><?php echo $this->Html->link("<i class=\"icon-time\"> {$this->request->data[$modelClass]['modified']} </i>", '#', array('class' => 'btn btn-warning', 'escape' => false)) . ' ' . __('Last change of this record.')?></h3>
    <?php endif;?>
</div>