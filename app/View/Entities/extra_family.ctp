<?php 
$hasInfo = 0;
foreach ($entity['Family'] as $k => $v) {
    if(count($v)){
        $hasInfo++;
    }
}
if(!$hasInfo){
    echo $this->element('Components/flash-message', array('message' => 'Nenhum possível parente encontrado.'));
}
?>
<!-- Mae -->
<?php if(count($entity['Family']['mother'])):?>
    <blockquote>
        <h4>
            <?php echo $this->Html->link('Mãe', '#', array('class' => 'btn btn-success'))?>
            <?php echo $this->Html->link($entity['Family']['mother']['Entity']['name'], array('controller' => 'entities', 'action' => 'index', 'plugin' => false, $entity['Family']['mother']['Entity']['id'], '#' => 'entity-main'))?>
        </h4>
        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">CPF</label>
                    <div class="controls">
                        <?php echo !empty($entity['Family']['mother']['Entity']['doc'])?$this->AppUtils->cpf($entity['Family']['mother']['Entity']['doc']):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Idade</label>
                    <div class="controls">
                        <?php echo !empty($entity['Family']['mother']['Entity']['age'])?$entity['Family']['mother']['Entity']['age']:'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Atualizado</label>
                    <div class="controls">
                        <?php echo !empty($entity['Family']['mother']['Entity']['modified'])?$this->AppUtils->dt2br($entity['Family']['mother']['Entity']['modified'], true):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
        </form>
    </blockquote>
<?php endif?>

<!-- Cônjuge -->
<?php if(count($entity['Family']['spouse'])):?>
    <blockquote>
        <h4>
            <?php echo $this->Html->link('Cônjuge', '#', array('class' => 'btn btn-success'))?>
            <?php echo $this->Html->link($entity['Family']['spouse']['Entity']['name'], array('controller' => 'entities', 'action' => 'index', 'plugin' => false, $entity['Family']['spouse']['Entity']['id']))?>
        </h4>
        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">CPF</label>
                    <div class="controls">
                        <?php echo !empty($entity['Family']['spouse']['Entity']['doc'])?$this->AppUtils->cpf($entity['Family']['spouse']['Entity']['doc']):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Idade</label>
                    <div class="controls">
                        <?php echo !empty($entity['Family']['spouse']['Entity']['age'])?$entity['Family']['spouse']['Entity']['age']:'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Atualizado</label>
                    <div class="controls">
                        <?php echo !empty($entity['Family']['spouse']['Entity']['modified'])?$this->AppUtils->dt2br($entity['Family']['spouse']['Entity']['modified'], true):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
        </form>
    </blockquote>
<?php endif?>

<!-- Filhos -->
<?php foreach($entity['Family']['children'] as $k => $v):?>
    <blockquote>
        <h4>
            <?php 
            $gender_children = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?'Filho':'Filha';
            echo $this->Html->link($gender_children, '#', array('class' => 'btn btn-success')) . '&nbsp;';
            echo $this->Html->link($v['Entity']['name'], array('controller' => 'entities', 'action' => 'index', 'plugin' => false, $v['Entity']['id']));
            ?>
        </h4>
        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
            <div class="control-group">
                <label class="control-label">Mãe</label>
                <div class="controls">
                    <?php echo !empty($v['Entity']['mother'])?$v['Entity']['mother']:'<small>Não Encontrado</small>';?>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">CPF</label>
                    <div class="controls">
                        <?php echo !empty($v['Entity']['doc'])?$this->AppUtils->cpf($v['Entity']['doc']):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Idade</label>
                    <div class="controls">
                        <?php echo !empty($v['Entity']['age'])?$v['Entity']['age']:'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Atualizado</label>
                    <div class="controls">
                        <?php echo !empty($v['Entity']['modified'])?$this->AppUtils->dt2br($v['Entity']['modified'], true):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
        </form>
    </blockquote>
<?php endforeach?>

<!-- Irmaos -->
<?php foreach($entity['Family']['brothers'] as $k => $v):?>
    <blockquote>
        <h4>
            <?php 
            $gender_brothers = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?'Irmão':'Irmã';
            echo $this->Html->link($gender_brothers, '#', array('class' => 'btn btn-success')) . '&nbsp;';
            echo $this->Html->link($v['Entity']['name'], array('controller' => 'entities', 'action' => 'index', 'plugin' => false, $v['Entity']['id']));
            ?>
        </h4>
        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
            <div class="control-group">
                <label class="control-label">Mãe</label>
                <div class="controls">
                    <?php echo !empty($v['Entity']['mother'])?$v['Entity']['mother']:'<small>Não Encontrado</small>';?>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">CPF</label>
                    <div class="controls">
                        <?php echo !empty($v['Entity']['doc'])?$this->AppUtils->cpf($v['Entity']['doc']):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Idade</label>
                    <div class="controls">
                        <?php echo !empty($v['Entity']['age'])?$v['Entity']['age']:'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Atualizado</label>
                    <div class="controls">
                        <?php echo !empty($v['Entity']['modified'])?$this->AppUtils->dt2br($v['Entity']['modified'], true):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
        </form>
    </blockquote>
<?php endforeach?>

<!-- Membros da familia -->
<?php foreach($entity['Family']['members'] as $k => $v):?>
    <blockquote>
        <h4>
            <?php 
            $class_members = isset($v['Entity']['gender']) && $v['Entity']['gender'] == MALE?'info':'pink';
            echo $this->Html->link('Membro da Família', '#', array('class' => 'btn btn-warning')) . '&nbsp;';
            echo $this->Html->link($v['Entity']['name'], array('controller' => 'entities', 'action' => 'index', 'plugin' => false, $v['Entity']['id']));
            ?>
        </h4>
        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
            <div class="control-group">
                <label class="control-label">Mãe</label>
                <div class="controls">
                    <?php echo !empty($v['Entity']['mother'])?$v['Entity']['mother']:'<small>Não Encontrado</small>';?>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">CPF</label>
                    <div class="controls">
                        <?php echo !empty($v['Entity']['doc'])?$this->AppUtils->cpf($v['Entity']['doc']):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Idade</label>
                    <div class="controls">
                        <?php echo !empty($v['Entity']['age'])?$v['Entity']['age']:'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Atualizado</label>
                    <div class="controls">
                        <?php echo !empty($v['Entity']['modified'])?$this->AppUtils->dt2br($v['Entity']['modified'], true):'<small>Não Encontrado</small>';?>
                    </div>
                </div>
            </div>
        </form>
    </blockquote>
<?php endforeach?>