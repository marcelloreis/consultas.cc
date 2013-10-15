<a class="block" data-toggle="collapse" href=".<?php echo $key?>"><?php echo $controller?></a>
<div class="well m-b-none in <?php echo $key?>">
    <dl class="m-t-none m-b-none">
        <dt class="m-b-small">Ações comuns</dt>
        <dd class="m-b">
            <p class="text-small">
                <input type="checkbox">&nbsp;Selecionar todos&nbsp;
            </p>
            <?php 
            foreach($common as $k => $v){
                $allowed_class = ($v['allowed'])?'btn-success':'';
                echo $this->Form->hidden($v['input_name']);
                echo $this->Html->link(__($v['alias']), "#ped-{$v['id']}", array('id' => "ped-{$v['id']}", 'title' => $v['Action'], 'class' => "btn btn-xs {$allowed_class}", 'data-toggle' => 'class:btn-success')) . "&nbsp;";
            }
            ?>
        </dd>
        <dt class="m-b-small">Ações específicas</dt>
        <dd class="m-b-none">
            <p class="text-small">
                <input type="checkbox">&nbsp;Selecionar todos&nbsp;
            </p>
            <?php 
            foreach($others as $k => $v){
                $allowed_class = ($v['allowed'])?'btn-success':'';
                echo $this->Form->hidden($v['input_name']);
                echo $this->Html->link(__($v['alias']), "#ped-{$v['id']}", array('id' => "ped-{$v['id']}", 'title' => $v['Action'], 'class' => "btn btn-xs {$allowed_class}", 'data-toggle' => 'class:btn-success')) . "&nbsp;";
            }
            ?>
        </dd>
    </dl>
</div>