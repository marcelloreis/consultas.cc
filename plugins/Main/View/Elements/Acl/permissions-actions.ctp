<div class="row-fluid">
    <div class="span12">
        <div class="box box-color box-bordered primary">
            <div class="box-title">
                <h3>
                    <i class="icon-cogs"></i>
                    <?php echo $controller?>
                </h3>
                <div class="actions">
                    <a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
                </div>
            </div>
            <div class="box-content">
                <p>
                    <h4><?php echo __('Common Actions');?></h4>
                    <?php 
                    foreach($common as $k => $v){
                        $allowed_class = ($v['allowed'])?'btn-success':'';
                        echo $this->Form->hidden($v['input_name']);
                        echo $this->Html->link(__($v['alias']), "#ped-{$v['id']}", array('id' => "ped-{$v['id']}", 'title' => $v['Action'], 'class' => "btn {$allowed_class}")) . "&nbsp;";
                        ?>
                        <?php
                    }
                    ?>
                </p>
                <p>
                    <h4><?php echo __('Specific Actions')?></h4>
                    <?php 
                    foreach($others as $k => $v){
                        $allowed_class = ($v['allowed'])?'btn-success':'';
                        echo $this->Form->hidden($v['input_name']);
                        echo $this->Html->link(__($v['alias']), "#ped-{$v['id']}", array('id' => "ped-{$v['id']}", 'title' => $v['Action'], 'class' => "btn {$allowed_class}")) . "&nbsp;";
                    }
                    ?>                    
                </p>
            </div>
        </div>
    </div>
</div>