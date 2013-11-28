<div class="row-fluid assoc-extra_landline">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="glyphicon-iphone"></i> <?php echo __('Landline Data')?></h3>
            </div>
            <div class="box-content nopadding">
                <!-- Telefone 1 -->
                <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                    <?php foreach($landlines as $k => $v):?>
                        <div class="control-group">
                            <label class="control-label"><?php echo $v['Association'][0]['year']?></label>
                            <div class="controls">
                                <?php echo $this->Html->link($v['Landline']['tel_txt'], array('action' => 'landline', $v['Landline']['ddd'], $v['Landline']['tel']))?>
                            </div>
                        </div>
                    <?php endforeach?>
                </form>
            </div>
        </div>
    </div>
</div>