<?php 
$hasInfo = 0;
foreach ($neighbors['Neighbors'] as $k => $v) {
    if(count($v)){
        $hasInfo++;
    }
}
if(!$hasInfo){
    echo $this->element('Components/flash-message', array('message' => __('No Neighbors Found')));
}
?>

<!-- Vizinhos -->
<?php foreach($neighbors['Neighbors'] as $k => $v):?>
    <?php foreach($v as $k2 => $v2):?>
        <blockquote>
            <h4>
                <?php 
                switch ($k) {
                    case 'same_address':
                        $neighbor_class = 'btn-success';
                        break;
                    case 'same_floor':
                    case 'same_street':
                        $neighbor_class = 'btn-warning';
                        break;
                }
                ?>
                <?php echo $this->Html->link(__(ucwords(str_replace('_', ' ', $k))), '#', array('class' => 'btn ' . $neighbor_class))?>
                <?php echo $this->Html->link($v2['Entity']['name'], array('controller' => 'entities', 'action' => 'index', 'plugin' => false, $v2['Entity']['id'], '#' => 'entity-main'))?>
            </h4>
            <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                <div class="control-group">
                    <label class="control-label"><?php echo __('Mother')?></label>
                    <div class="controls">
                        <?php echo !empty($v2['Entity']['mother'])?$v2['Entity']['mother']:'<small>' . __('Not Found') . '</small>';?>
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group">
                        <label class="control-label"><?php echo __('CPF')?></label>
                        <div class="controls">
                            <?php echo !empty($v2['Entity']['doc'])?$this->AppUtils->cpf($v2['Entity']['doc']):'<small>' . __('Not Found') . '</small>';?>
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group">
                        <label class="control-label"><?php echo __('Age')?></label>
                        <div class="controls">
                            <?php echo !empty($v2['Entity']['age'])?$v2['Entity']['age']:'<small>' . __('Not Found') . '</small>';?>
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group">
                        <label class="control-label"><?php echo __('Updated')?></label>
                        <div class="controls">
                            <?php echo !empty($v2['Entity']['modified'])?$this->AppUtils->dt2br($v2['Entity']['modified'], true):'<small>' . __('Not Found') . '</small>';?>
                        </div>
                    </div>
                </div>
            </form>
        </blockquote>
    <?php endforeach?>
<?php endforeach?>