<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="icon-phone"></i> <?php echo __d('app', 'Landline Data')?></h3>
            </div>
            <div class="box-content">
                <!-- Telefone 1 -->
                <?php foreach($landline as $k => $v):?>
                    <blockquote>
                        <h4>
                            <i class="glyphicon-clock"></i>
                            <?php 
                            $yearDiff = date('Y') - $v['Landline']['year'];
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
                            echo sprintf(__d('app', '%s updated in %s'), __d('fields', 'Landline'), $this->Html->link($v['Landline']['year'], '#', array('class' => 'btn ' . $yearClass)));
                            ?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                            <?php if(!isset($address[$k])):?>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __d('app', 'Landline')?></label>
                                    <div class="controls">
                                        <?php echo $v['Landline']['tel_full']?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __d('app', 'Warning')?></label>
                                    <div class="controls">
                                        <?php echo __d('app', 'No address for this landline')?>
                                    </div>
                                </div>
                            <?php else:?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label"><?php echo __d('app', 'Landline')?></label>
                                        <div class="controls">
                                            <?php echo $v['Landline']['tel_full']?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label"><?php echo __d('fields', 'Number')?></label>
                                        <div class="controls">
                                            <?php echo $address[$k]['Address']['number']?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"><?php echo __d('fields', 'Complement')?></label>
                                        <div class="controls">
                                            <?php echo $address[$k]['Address']['complement']?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"><?php echo __d('fields', 'Neighborhood')?></label>
                                        <div class="controls">
                                            <?php echo $address[$k]['Address']['neighborhood']?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label"><?php echo __d('fields', 'Street')?></label>
                                        <div class="controls">
                                            <?php echo $address[$k]['Address']['street']?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label"><?php echo __d('fields', 'Zipcode')?></label>
                                        <div class="controls">
                                            <?php echo $address[$k]['Address']['zipcode_id']?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"><?php echo __d('fields', 'State')?></label>
                                        <div class="controls">
                                            <?php echo $address[$k]['Address']['state_id']?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"><?php echo __d('fields', 'City')?></label>
                                        <div class="controls">
                                            <?php echo $address[$k]['Address']['city']?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif?>

                        </form>
                    </blockquote>
                <?php endforeach?>
            </div>
        </div>
    </div>
</div>