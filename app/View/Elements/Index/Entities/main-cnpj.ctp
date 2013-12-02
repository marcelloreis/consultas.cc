<div class="control-group">
    <label class="control-label">Nome</label>
    <div class="controls">
        <?php echo $entity['Entity']['name']?>
    </div>
</div>

<div class="span4">    
    <div class="control-group">
        <label class="control-label">CNPJ</label>
        <div class="controls">
            <?php echo $this->AppUtils->cnpj($entity['Entity']['doc'])?>
        </div>
    </div>
</div>
<div class="span4">
    <div class="control-group">
        <label class="control-label">Pessoa</label>
        <div class="controls">
            <?php 
            switch ($entity['Entity']['type']) {
                case TP_CNPJ:
                    echo 'Jurídica';
                    break;
                case TP_AMBIGUO:
                    echo 'Ambíguo';
                    break;
                case TP_INVALID:
                    echo 'Documento Inválido';
                    break;
            }
            ?>
        </div>
    </div>
</div>
<div class="span4">
    <div class="control-group">
        <label class="control-label">Atualizado</label>
        <div class="controls">
            <?php echo $this->AppUtils->dt2br($entity['Entity']['modified'], true)?>
        </div>
    </div>
</div>

