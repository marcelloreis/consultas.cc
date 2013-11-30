<?php 
/**
* Insere o sidebar especifico de usuarios
*/
$this->start('sidebar');
echo $this->element('Components/Users/sidebar');
$this->end();

/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/icheck/all'));
echo $this->Html->css(array('plugins/datepicker/datepicker'));
$this->end();

echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'), array('defer' => true));
echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'), array('defer' => true));
echo $this->Html->script(array('plugins/datepicker/bootstrap-datepicker'), array('defer' => true));
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
    <div class="box-content nopadding">
        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xxlarge', 'classForm' => 'form-horizontal form-bordered form-striped'))?>
            <div style="display:none;">
                <?php echo $this->Form->input('id')?>
            </div>
            <?php 
            if($isAccount){
                $selected = (!empty($this->params['named']['client_id']))?(!empty($this->params['named']['client_id'])):'';
                echo $this->AppForm->input('client_id', array('selected' => $selected, 'class' => 'chosen-select'));
                echo $this->Form->hidden('group_id', array('value' => CLIENT_GROUP));
            }else{
                echo $this->AppForm->input('group_id', array('class' => 'chosen-select'));
            }
            echo $this->AppForm->input('name');
            echo $this->AppForm->input('given_name');
            echo $this->AppForm->input('expire');
            echo $this->AppForm->input('email');
            echo $this->AppForm->input('password');
            echo $this->AppForm->input('picture');
            echo $this->AppForm->input('infinite', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'));
            echo $this->AppForm->input('status', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue'));

            echo $this->AppForm->btn('Save changes');
            ?>


        <?php echo $this->AppForm->end()?>
    </div>
</div>

