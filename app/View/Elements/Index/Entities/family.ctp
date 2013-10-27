<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="glyphicon-parents"></i> <?php echo __('Family')?></h3>
            </div>
            <div class="box-content">

                <!-- Mae -->
                <?php if(count($family['Family']['mother'])):?>
                    <blockquote>
                        <h4>
                            <?php echo $this->Html->link(__('Mother'), '#', array('class' => 'btn btn-purple'))?>
                            <?php echo $this->Html->link($family['Family']['mother']['Entity']['name'], array('controller' => '', 'action' => ''))?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('CPF')?></label>
                                    <div class="controls">
                                        <?php echo !empty($family['Family']['mother']['Entity']['doc'])?$family['Family']['mother']['Entity']['doc']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Age')?></label>
                                    <div class="controls">
                                        <?php echo !empty($family['Family']['mother']['Entity']['age'])?$family['Family']['mother']['Entity']['age']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Updated')?></label>
                                    <div class="controls">
                                        <?php echo !empty($family['Family']['mother']['Entity']['modified'])?$family['Family']['mother']['Entity']['modified']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </blockquote>
                <?php endif?>

                <!-- Irmaos -->
                <?php foreach($family['Family']['brothers'] as $k => $v):?>
                    <blockquote>
                        <h4>
                            <?php 
                            $class_brothers = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?'info':'pink';
                            $gender_brothers = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?__('Brother'):__('Sister');
                            echo $this->Html->link($gender_brothers, '#', array('class' => 'btn btn-' . $class_brothers)) . '&nbsp;';
                            echo $this->Html->link($v['Entity']['name'], array('controller' => '', 'action' => ''));
                            ?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Mother')?></label>
                                <div class="controls">
                                    <?php echo !empty($v['Entity']['mother'])?$v['Entity']['mother']:'<small>' . __('Not Found') . '</small>';?>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('CPF')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Entity']['doc'])?$v['Entity']['doc']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Age')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Entity']['age'])?$v['Entity']['age']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Updated')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Entity']['modified'])?$v['Entity']['modified']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </blockquote>
                <?php endforeach?>

                <!-- Filhos -->
                <?php foreach($family['Family']['children'] as $k => $v):?>
                    <blockquote>
                        <h4>
                            <?php 
                            $class_children = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?'info':'pink';
                            $gender_children = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?__('Son'):__('Daughter');
                            echo $this->Html->link($gender_children, '#', array('class' => 'btn btn-' . $class_children)) . '&nbsp;';
                            echo $this->Html->link($v['Entity']['name'], array('controller' => '', 'action' => ''));
                            ?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                            <div class="control-group">
                                <label class="control-label"><?php echo __('Mother')?></label>
                                <div class="controls">
                                    <?php echo !empty($v['Entity']['mother'])?$v['Entity']['mother']:'<small>' . __('Not Found') . '</small>';?>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('CPF')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Entity']['doc'])?$v['Entity']['doc']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Age')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Entity']['age'])?$v['Entity']['age']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Updated')?></label>
                                    <div class="controls">
                                        <?php echo !empty($v['Entity']['modified'])?$v['Entity']['modified']:'<small>' . __('Not Found') . '</small>';?>
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