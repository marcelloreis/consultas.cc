<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="glyphicon-parents"></i> <?php echo __('Family')?></h3>
            </div>
            <div class="box-content">
                <?php 
                $hasInfo = 0;
                foreach ($family['Family'] as $k => $v) {
                    if(count($v)){
                        $hasInfo++;
                    }
                }
                if(!$hasInfo){
                    echo $this->element('Components/flash-message', array('message' => __('No Family Found')));
                }
                ?>
                <!-- Mae -->
                <?php if(count($family['Family']['mother'])):?>
                    <blockquote>
                        <h4>
                            <?php echo $this->Html->link(__('Mother'), '#', array('class' => 'btn btn-success'))?>
                            <?php echo $this->Html->link($family['Family']['mother']['Entity']['name'], array('controller' => 'entities', 'action' => 'people', 'plugin' => false, $family['Family']['mother']['Entity']['id']))?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('CPF')?></label>
                                    <div class="controls">
                                        <?php echo !empty($family['Family']['mother']['Entity']['doc'])?$this->AppUtils->cpf($family['Family']['mother']['Entity']['doc']):'<small>' . __('Not Found') . '</small>';?>
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
                                        <?php echo !empty($family['Family']['mother']['Entity']['modified'])?$this->AppUtils->dt2br($family['Family']['mother']['Entity']['modified'], true):'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </blockquote>
                <?php endif?>

                <!-- Conjuje -->
                <?php if(count($family['Family']['spouse'])):?>
                    <blockquote>
                        <h4>
                            <?php echo $this->Html->link(__('Spouse'), '#', array('class' => 'btn btn-success'))?>
                            <?php echo $this->Html->link($family['Family']['spouse']['Entity']['name'], array('controller' => 'entities', 'action' => 'people', 'plugin' => false, $family['Family']['spouse']['Entity']['id']))?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('CPF')?></label>
                                    <div class="controls">
                                        <?php echo !empty($family['Family']['spouse']['Entity']['doc'])?$this->AppUtils->cpf($family['Family']['spouse']['Entity']['doc']):'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Age')?></label>
                                    <div class="controls">
                                        <?php echo !empty($family['Family']['spouse']['Entity']['age'])?$family['Family']['spouse']['Entity']['age']:'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Updated')?></label>
                                    <div class="controls">
                                        <?php echo !empty($family['Family']['spouse']['Entity']['modified'])?$this->AppUtils->dt2br($family['Family']['spouse']['Entity']['modified'], true):'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </blockquote>
                <?php endif?>

                <!-- Filhos -->
                <?php foreach($family['Family']['children'] as $k => $v):?>
                    <blockquote>
                        <h4>
                            <?php 
                            $gender_children = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?__('Son'):__('Daughter');
                            echo $this->Html->link($gender_children, '#', array('class' => 'btn btn-success')) . '&nbsp;';
                            echo $this->Html->link($v['Entity']['name'], array('controller' => 'entities', 'action' => 'people', 'plugin' => false, $v['Entity']['id']));
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
                                        <?php echo !empty($v['Entity']['doc'])?$this->AppUtils->cpf($v['Entity']['doc']):'<small>' . __('Not Found') . '</small>';?>
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
                                        <?php echo !empty($v['Entity']['modified'])?$this->AppUtils->dt2br($v['Entity']['modified'], true):'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </blockquote>
                <?php endforeach?>

                <!-- Irmaos -->
                <?php foreach($family['Family']['brothers'] as $k => $v):?>
                    <blockquote>
                        <h4>
                            <?php 
                            $gender_brothers = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?__('Brother'):__('Sister');
                            echo $this->Html->link($gender_brothers, '#', array('class' => 'btn btn-success')) . '&nbsp;';
                            echo $this->Html->link($v['Entity']['name'], array('controller' => 'entities', 'action' => 'people', 'plugin' => false, $v['Entity']['id']));
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
                                        <?php echo !empty($v['Entity']['doc'])?$this->AppUtils->cpf($v['Entity']['doc']):'<small>' . __('Not Found') . '</small>';?>
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
                                        <?php echo !empty($v['Entity']['modified'])?$this->AppUtils->dt2br($v['Entity']['modified'], true):'<small>' . __('Not Found') . '</small>';?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </blockquote>
                <?php endforeach?>

                <!-- Membros da familia -->
                <?php foreach($family['Family']['members'] as $k => $v):?>
                    <blockquote>
                        <h4>
                            <?php 
                            $class_members = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?'info':'pink';
                            echo $this->Html->link(__('Family Member'), '#', array('class' => 'btn btn-warning')) . '&nbsp;';
                            echo $this->Html->link($v['Entity']['name'], array('controller' => 'entities', 'action' => 'people', 'plugin' => false, $v['Entity']['id']));
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
                                        <?php echo !empty($v['Entity']['doc'])?$this->AppUtils->cpf($v['Entity']['doc']):'<small>' . __('Not Found') . '</small>';?>
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
                                        <?php echo !empty($v['Entity']['modified'])?$this->AppUtils->dt2br($v['Entity']['modified'], true):'<small>' . __('Not Found') . '</small>';?>
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