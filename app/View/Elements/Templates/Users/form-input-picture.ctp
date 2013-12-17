<div class="fileupload fileupload-new" data-provides="fileupload">
    <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 150px;">
    	<?php 
    	$avatar = !empty($this->data['User']['avatar_158']) && is_file(PATH_WEBROOT . $this->data['User']['avatar_158'])?$this->data['User']['avatar_158']:'avatar-158x158.png';
    	echo $this->Html->image($avatar, array('class' => 'img-polaroid'));
    	?>
    </div>
    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
    <div>
        <span class="btn btn-file">
        	<span class="fileupload-new">Selecione uma imagem</span>
        	<span class="fileupload-exists">Alterar</span>
        	%input%
        </span>
        <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
    </div>
</div>