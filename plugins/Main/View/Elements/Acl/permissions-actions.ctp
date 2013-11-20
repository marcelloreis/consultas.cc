<div class="row-fluid">
    <div class="span12">
        <div class="box box-perms box-color box-bordered primary">
            <div class="box-title">
                <h3>
                    <i class="glyphicon-cogwheels"></i>
                    <?php echo __($controller)?>
                </h3>
                <div class="actions">
                    <?php echo $this->Html->link('<i class="icon-refresh"></i>', '#', array('class' => 'btn btn-mini perms-toggle allow', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Allow/Deny all'), __($controller)), 'escape' => false)) . '&nbsp;'?>
                    <?php echo $this->Html->link('<i class="icon-angle-down"></i>', '#', array('class' => 'btn btn-mini content-slideUp', 'rel' => 'tooltip', 'data-original-title' => sprintf(__('Close %s'), __($controller)), 'escape' => false)) . '&nbsp;'?>
                </div>
            </div>
            <div class="box-content">
                <p>
                    <h4><?php echo __('Common Actions');?></h4>
                    <?php 
                    foreach($common as $k => $v){
                        $allowed_class = ($v['allowed'])?'btn-success':'btn-red';
                        $perm = ($v['allowed'])?'allow':'deny';

                        echo $this->Form->hidden($v['input_name'], array('value' => $perm));
                        echo $this->Html->link(__($v['alias']), "#aco-{$v['id']}", array('id' => "ped-{$v['id']}", 'title' => $v['Action'], 'class' => "perms-toggle-single btn {$allowed_class}", 'escape' => false)) . "&nbsp;";
                        ?>
                        <?php
                    }
                    ?>
                </p>
                <p>
                    <h4><?php echo __('Specific Actions')?></h4>
                    <?php 
                    foreach($others as $k => $v){
                        $allowed_class = ($v['allowed'])?'btn-success':'btn-red';
                        $perm = ($v['allowed'])?'allow':'deny';

                        echo $this->Form->hidden($v['input_name'], array('value' => $perm));
                        echo $this->Html->link(__($v['alias']), "#aco-{$v['id']}", array('id' => "ped-{$v['id']}", 'title' => $v['Action'], 'class' => "perms-toggle-single btn {$allowed_class}", 'escape' => false)) . "&nbsp;";
                    }
                    ?>                    
                </p>
            </div>
        </div>
    </div>
</div>