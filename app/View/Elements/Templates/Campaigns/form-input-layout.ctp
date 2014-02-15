<?php 
$half_1 = array_slice($layout, 0, (count($layout) /2));
$half_2 = array_slice($layout, (count($layout) /2));
?>
<div class="control-group">
    <label for="%id%" class="control-label">Layout</label>
        <div class="controls">
            <div class="row-fluid">
                <div class="span6">
                    <?php foreach($half_1 as $k => $v):?>
                        <div class="check-line">
                            <?php $checked = in_array($k, $layout_checked)?'checked':''?>
                            <?php echo $this->AppForm->input("{$modelClass}.layout.{$k}", array('type' => 'checkbox', 'id' => $k, 'checked' => $checked, 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue', 'template' => 'form-input-clean'));?>
                            <label for="<?php echo $k?>" class="inline"><?php echo $v?></label>
                        </div>
                    <?php endforeach?>
                </div>
                <div class="span6">
                    <?php foreach($half_2 as $k => $v):?>
                        <div class="check-line">
                            <?php $checked = in_array($k, $layout_checked)?'checked':''?>
                            <?php echo $this->AppForm->input("{$modelClass}.layout.{$k}", array('type' => 'checkbox', 'id' => $k, 'checked' => $checked, 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue', 'template' => 'form-input-clean'));?>
                            <label for="<?php echo $k?>" class="inline"><?php echo $v?></label>
                        </div>
                    <?php endforeach?>
                </div>
            </div>
        </div>
</div>

