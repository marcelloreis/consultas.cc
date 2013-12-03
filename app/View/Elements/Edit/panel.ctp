<div class="box-title">
    <?php if(!count($this->params['pass'])):?>
        <h3><i class="icon-plus"></i> <?php echo 'Insira os dados do novo registro.'?></h3>
    <?php elseif(isset($this->request->data[$modelClass])):?>
        <h3><?php echo $this->Html->link("<i class=\"icon-time\"> {$this->request->data[$modelClass]['modified']} </i>", '#', array('class' => 'btn btn-warning', 'escape' => false)) . ' ' . 'Última alteração deste registro.'?></h3>
    <?php endif;?>
</div>