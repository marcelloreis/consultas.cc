<!-- Telefone 1 -->
<form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
    <?php foreach($entity as $k => $v):?>
        <div class="control-group">
            <label class="control-label">Atualizado em <?php echo $this->element('Index/Entities/tag-year', array('year' => $v['Association'][0]['year']))?></label>
            <div style="padding-top: 20px;" class="controls">
                <?php echo $this->Html->link($v['Mobile']['tel_txt'], array('action' => 'mobile', $v['Mobile']['ddd'], $v['Mobile']['tel']))?>
            </div>
        </div>
    <?php endforeach?>
</form>