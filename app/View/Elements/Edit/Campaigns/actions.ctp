<div class="row-fluid">
    <div class="span12">
        <div class="pull-right">
            <ul class="minitiles">
                <li class="<?php echo $status=='1'?'grey':'green';?>">
                    <?php 
                    if($status == '1'){
                        echo $this->Html->link('<i class="glyphicon-inbox_lock"></i>', array('action' => 'deactivate', $id), array('escape' => false));
                    }else{
                        echo $this->Html->link('<i class="glyphicon-inbox_out"></i>', array('action' => 'activate', $id), array('escape' => false));
                    }
                    ?>
                </li>
                <li class="lightgrey">
                    <?php 
                    if($status == '1'){
                        echo $this->Html->link('<i class="icon-copy"></i>', array('controller' => 'sms_sent', 'action' => 'index', 'campaign_id' => $id), array('escape' => false));
                    }
                    ?>
                </li>
            </ul>
        </div>        
    </div>
</div>