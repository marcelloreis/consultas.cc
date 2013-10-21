<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="glyphicon-road"></i> <?php echo __('Address Data')?></h3>
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
                            echo sprintf(__('%s updated in %s'), __('Address'), $this->Html->link($v['Address']['year'], '#', array('class' => 'btn ' . $yearClass)));
                            ?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                            <div class="control-group">
                                <label class="control-label"><?php echo isset($v['Address']['type_address'])?$v['Address']['type_address']:'<small>' . __('Not Foud') . '</small>';?></label>
                                <div class="controls">
                                    <?php echo !empty($v['Address']['street'])?$v['Address']['street']:'<small>' . __('Not Found') . '</small>';?>
                                </div>
                            </div>

                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Number')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['number'])?$v['Address']['number']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Complement')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['complement'])?$v['Address']['complement']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Neighborhood')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['neighborhood'])?$v['Address']['neighborhood']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">

                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Zipcode')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['zipcode'])?$v['Address']['zipcode']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('State')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['state'])?$v['Address']['state']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('City')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['city'])?$v['Address']['city']:'<small>' . __('Not Found') . '</small>';?>
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