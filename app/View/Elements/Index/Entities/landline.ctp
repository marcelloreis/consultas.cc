<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="icon-phone"></i> <?php echo __('Landline Data')?></h3>
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
                            echo sprintf(__('%s updated in %s'), __('Landline'), $this->Html->link($v['Landline']['year'], '#', array('class' => 'btn ' . $yearClass)));
                            ?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">

                            <div class="control-group">
                                <label class="control-label"><?php echo __('Landline')?></label>
                                <div class="controls">
                                    <?php echo $v['Landline']['tel_full']?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" style="padding:10px 0 10px 30px;"><i class="glyphicon-globe"></i>&nbsp;<?php echo __('Address Installation')?></label>
                            </div>

                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Landline')?></label>
                                    <div class="controls">
                                        <?php echo $v['Landline']['tel_full']?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Number')?></label>
                                    <div class="controls">
                                        <?php echo !empty($address[$k]['Address']['number'])?$address[$k]['Address']['number']:'<small>' . __('Not Foud') . '</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Complement')?></label>
                                    <div class="controls">
                                        <?php echo !empty($address[$k]['Address']['complement'])?$address[$k]['Address']['complement']:'<small>' . __('Not Foud') . '</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Neighborhood')?></label>
                                    <div class="controls">
                                        <?php echo !empty($address[$k]['Address']['neighborhood'])?$address[$k]['Address']['neighborhood']:'<small>' . __('Not Foud') . '</small>';?>
                                    </div>
                                </div>
                            </div>

                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label"><?php echo !empty($address[$k]['Address']['type_address'])?$address[$k]['Address']['type_address']:'<small>' . __('Not Foud') . '</small>';?></label>
                                    <div class="controls">
                                        <?php echo !empty($address[$k]['Address']['street'])?$address[$k]['Address']['street']:'<small>' . __('Not Foud') . '</small>';?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Zipcode')?></label>
                                    <div class="controls">
                                        <?php echo !empty($address[$k]['Address']['zipcode'])?$address[$k]['Address']['zipcode']:'<small>' . __('Not Foud') . '</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('State')?></label>
                                    <div class="controls">
                                        <?php echo !empty($address[$k]['Address']['state'])?$address[$k]['Address']['state']:'<small>' . __('Not Foud') . '</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('City')?></label>
                                    <div class="controls">
                                        <?php echo !empty($address[$k]['Address']['city'])?$address[$k]['Address']['city']:'<small>' . __('Not Foud') . '</small>';?>
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