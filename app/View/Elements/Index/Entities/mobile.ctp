<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="glyphicon-iphone"></i> <?php echo __('Mobile Data')?></h3>
            </div>
            <div class="box-content">
                <!-- Telefone 1 -->
                <blockquote>
                    <h4>
                        <i class="glyphicon-clock"></i>
                        <?php 
                        $year = '2013';
                        $yearDiff = date('Y') - $year;
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
                        echo sprintf(__('%s updated in %s'), __('Mobile'), $this->Html->link($year, '#', array('class' => 'btn ' . $yearClass)));
                        ?>
                    </h4>
                    <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Mobile')?></label>
                                <div class="controls">
                                    (27) 3019-9792
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo __('Number')?></label>
                                <div class="controls">
                                    14
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Complement')?></label>
                                <div class="controls">
                                    Rua da Cesan
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Neighborhood')?></label>
                                <div class="controls">
                                    Bonfim
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Address')?></label>
                                <div class="controls">
                                    Rua Mario Loureiro Nunes
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo __('Zipcode')?></label>
                                <div class="controls">
                                    29047066
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('State')?></label>
                                <div class="controls">
                                    ES
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Name in List')?></label>
                                <div class="controls">
                                    Marcelo Martins dos Reis
                                </div>
                            </div>
                        </div>
                    </form>
                </blockquote>

                <!-- Telefone 2 -->
                <blockquote>
                    <h4>
                        <i class="glyphicon-clock"></i>
                        <?php 
                        $year = '2012';
                        $yearDiff = date('Y') - $year;
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
                        echo sprintf(__('%s updated in %s'), __('Mobile'), $this->Html->link($year, '#', array('class' => 'btn ' . $yearClass)));
                        ?>
                    </h4>
                    <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Mobile')?></label>
                                <div class="controls">
                                    (27) 3019-9792
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo __('Number')?></label>
                                <div class="controls">
                                    14
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Complement')?></label>
                                <div class="controls">
                                    Rua da Cesan
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Neighborhood')?></label>
                                <div class="controls">
                                    Bonfim
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Address')?></label>
                                <div class="controls">
                                    Rua Mario Loureiro Nunes
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo __('Zipcode')?></label>
                                <div class="controls">
                                    29047066
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('State')?></label>
                                <div class="controls">
                                    ES
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Name in List')?></label>
                                <div class="controls">
                                    Marcelo Martins dos Reis
                                </div>
                            </div>
                        </div>
                    </form>
                </blockquote>

                <!-- Telefone 3 -->
                <blockquote>
                    <h4>
                        <i class="glyphicon-clock"></i>
                        <?php 
                        $year = '2010';
                        $yearDiff = date('Y') - $year;
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
                        echo sprintf(__('%s updated in %s'), __('Mobile'), $this->Html->link($year, '#', array('class' => 'btn ' . $yearClass)));
                        ?>
                    </h4>
                    <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Mobile')?></label>
                                <div class="controls">
                                    (27) 3019-9792
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo __('Number')?></label>
                                <div class="controls">
                                    14
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Complement')?></label>
                                <div class="controls">
                                    Rua da Cesan
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Neighborhood')?></label>
                                <div class="controls">
                                    Bonfim
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Address')?></label>
                                <div class="controls">
                                    Rua Mario Loureiro Nunes
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo __('Zipcode')?></label>
                                <div class="controls">
                                    29047066
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('State')?></label>
                                <div class="controls">
                                    ES
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Name in List')?></label>
                                <div class="controls">
                                    Marcelo Martins dos Reis
                                </div>
                            </div>
                        </div>
                    </form>
                </blockquote>

            </div>
        </div>
    </div>
</div>