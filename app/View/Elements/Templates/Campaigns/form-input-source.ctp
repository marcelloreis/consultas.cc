<div class="control-group">
    <label for="%id%" class="control-label">Arquivo</label>
    <div class="controls">
        <div class="span12">
            <?php if(!empty($this->data['Campaign']['source'])):?>
                <h5><?php echo $this->data['Campaign']['source']?> 
                    <code><?php echo $this->data['Campaign']['source_type']?></code> 
                    <code><?php echo $this->data['Campaign']['source_size']?></code></h5>
            <?php endif?>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="input-append">
                    <div class="uneditable-input span3">
                        <i class="icon-file fileupload-exists"></i> 
                        <span class="fileupload-preview"></span>
                    </div>
                    <span class="btn btn-file">
                        <span class="fileupload-new">Selecione o arquivo</span>
                        <span class="fileupload-exists">Alterar</span>
                        %input%
                    </span>
                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
                </div>
            </div>
        </div>
    </div>
</div>