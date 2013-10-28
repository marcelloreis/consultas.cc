<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="glyphicon-parents"></i> <?php echo __('Neighbors')?></h3>
            </div>
            <div class="box-content">

                <!-- Vizinhos -->
                <?php foreach($neighborhood['Neighborhood'] as $k => $v):?>
                    <?php foreach($v as $k2 => $v2):?>
                        <blockquote>
                            <h4>
                                <?php echo $this->Html->link(__($k), '#', array('class' => 'btn btn-success'))?>
                                <?php echo $this->Html->link($v2['Entity']['name'], array('controller' => 'entities', 'action' => 'people', 'plugin' => false, $v2['Entity']['id']))?>
                            </h4>
                            <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                                <div class="span4">
                                    <div class="control-group">
                                        <label class="control-label"><?php echo __('CPF')?></label>
                                        <div class="controls">
                                            <?php echo !empty($v2['Entity']['doc'])?$v2['Entity']['doc']:'<small>' . __('Not Found') . '</small>';?>
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
                                            <?php echo !empty($v2['Entity']['modified'])?$v2['Entity']['modified']:'<small>' . __('Not Found') . '</small>';?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </blockquote>
                    <?php endforeach?>
                <?php endforeach?>
            </div>
        </div>
    </div>
</div>