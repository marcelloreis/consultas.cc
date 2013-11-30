<!-- Telefone 1 -->
<form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
    <?php foreach($landlines as $k => $v):?>
        <div class="control-group">
            <label class="control-label">Atualizado em <?php echo $this->element('Index/Entities/tag-year', array('year' => $v['Association'][0]['year']))?></label>
            <div class="controls">
                <?php echo $this->Html->link($v['Landline']['tel_txt'], array('action' => 'landline', $v['Landline']['ddd'], $v['Landline']['tel']))?>
            </div>
        </div>
    <?php endforeach?>
</form>