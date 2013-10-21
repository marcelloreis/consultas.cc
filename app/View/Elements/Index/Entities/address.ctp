<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="glyphicon-road"></i> <?php echo __d('app', 'Address Data')?></h3>
            </div>
            <div class="box-content">
                <!-- Telefone 1 -->
                <?php foreach($address as $k => $v):?>
                    <?php 
                        if(isset($landline[$k]['Landline'])){
                            continue;
                        }
                    ?>
                    <blockquote>
                        <h4>
                            <i class="glyphicon-clock"></i>
                            <?php 
                            $yearDiff = date('Y') - $v['Address']['year'];
                            switch ($yearDiff) {
                                case 0:
                                    $yearClass = 'btn-success';
                                    break;
                                case 1:
                                case 2:
                                    $yearClass = 'btn-warning';
                                    break;
                                default:
                                    $yearClass = 'btn-red';
                                    break;
                            }
                            echo sprintf(__d('app', '%s updated in %s'), __d('fields', 'Address'), $this->Html->link($v['Address']['year'], '#', array('class' => 'btn ' . $yearClass)));
                            ?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                            <div class="control-group">
                                <label class="control-label"><?php echo __d('fields', 'Street')?></label>
                                <div class="controls">
                                    <?php echo $v['Address']['street']?>
                                </div>
                            </div>

                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __d('fields', 'Number')?></label>
                                    <div class="controls">
                                        <?php echo $v['Address']['number']?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __d('fields', 'Complement')?></label>
                                    <div class="controls">
                                        <?php echo $v['Address']['complement']?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __d('fields', 'Neighborhood')?></label>
                                    <div class="controls">
                                        <?php echo $v['Address']['neighborhood']?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">

                                <div class="control-group">
                                    <label class="control-label"><?php echo __d('fields', 'Zipcode')?></label>
                                    <div class="controls">
                                        <?php echo $v['Address']['zipcode_id']?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __d('fields', 'State')?></label>
                                    <div class="controls">
                                        <?php echo $v['Address']['state_id']?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __d('fields', 'City')?></label>
                                    <div class="controls">
                                        <?php echo $v['Address']['city']?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </blockquote>
                <?php endforeach?>

            </div>
        </div>
    </div>
</div>