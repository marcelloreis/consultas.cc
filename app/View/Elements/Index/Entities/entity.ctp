<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>
                    <i class="glyphicon-vcard"></i>
                    <?php echo __d('app', 'Document Data')?>
                </h3>
            </div>
            <div class="box-content nopadding">
                <form class="form-horizontal form-column form-bordered" method="POST" action="#">
                    <div class="control-group">
                        <label class="control-label"><?php echo __d('app', 'Name')?></label>
                        <div class="controls">
                            <?php echo $entity['Entity']['name']?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><?php echo __d('app', 'Mother')?></label>
                        <div class="controls">
                            <?php echo $this->Html->link($entity['Entity']['mother'], array('controller' => '', 'action' => ''), array('escape' => false))?>
                        </div>
                    </div>

                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label"><?php echo __d('app', 'Document')?></label>
                            <div class="controls">
                                <?php echo $entity['Entity']['doc']?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo __d('app', 'Updated')?></label>
                            <div class="controls">
                                <?php echo $entity['Entity']['modified']?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo __d('app', 'Gender')?></label>
                            <div class="controls">
                                <i class="glyphicon-<?php echo strtolower($entity['Entity']['gender_str'])?>"></i>
                                <?php echo __($entity['Entity']['gender_str'])?>
                            </div>
                        </div>
                    </div>

                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label"><?php echo __d('app', 'Birthday')?></label>
                            <div class="controls">
                                <?php echo $entity['Entity']['birthday']?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo __d('app', 'Age')?></label>
                            <div class="controls">
                                <?php echo $entity['Entity']['age']?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo __d('app', 'Zodiac Sign')?></label>
                            <div class="controls">
                                fazer funcao para isso
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>