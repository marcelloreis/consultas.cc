<?php 
/**
* Oculta o sidebar
*/
$this->assign('sidebar-class-hidden', 'nav-hidden');

/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
echo $this->Html->css(array('plugins/icheck/all'));
echo $this->Html->css(array('plugins/datepicker/datepicker'));
echo $this->Html->css(array('plugins/tagsinput/jquery.tagsinput'));
$this->end();

echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'), array('defer' => true));
echo $this->Html->script(array('plugins/icheck/jquery.icheck.min'), array('defer' => true));
echo $this->Html->script(array('plugins/datepicker/bootstrap-datepicker'), array('defer' => true));
echo $this->Html->script(array('plugins/tagsinput/jquery.tagsinput.min'), array('defer' => true));
echo $this->Html->script(array('plugins/fileupload/bootstrap-fileupload.min'), array('defer' => true));
?>
<div class="box box-bordered">
    <?php echo $this->element('Edit/panel')?>
        <div class="box-content nopadding">
            <ul class="tabs tabs-inline tabs-top">
                <li class='active'>
                    <a href="#profile" data-toggle='tab'><i class="icon-user"></i> Perfil</a>
                </li>
                <li>
                    <a href="#notifications" data-toggle='tab'><i class="icon-bullhorn"></i> Configurações</a>
                </li>
            </ul>
                    <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal', 'type' => 'file'));?>
            <div class="tab-content padding tab-content-inline tab-content-bottom">
                <div class="tab-pane active" id="profile">

                    <?php echo $this->Form->input('id');?>
                        <div class="row-fluid">
                            <div class="span2">
                                <?php echo $this->AppForm->input('picture', array('type' => 'file', 'template' => 'Users/form-input-picture'));?>
                            </div>
                            <div class="span10">
                                <?php
                                echo $this->AppForm->input('client_id', array('type' => 'select', 'class' => 'chosen-select', 'template' => 'Users/form-input-fk'));
                                echo $this->Form->hidden('group_id', array('value' => CLIENT_GROUP));
                                echo $this->AppForm->input('name', array('template' => 'Users/form-input'));
                                echo $this->AppForm->input('given_name', array('template' => 'Users/form-input'));
                                echo $this->AppForm->input('email', array('template' => 'Users/form-input'));
                                echo $this->AppForm->input('password', array('template' => 'Users/form-input'));

                                echo $this->AppForm->btn('Salvar Alterações', array('template' => 'Users/form-input-btn'));
                                ?>
                            </div>
                        </div>
                </div>

                <div class="tab-pane" id="notifications">
                        <div class="control-group">
                            <label for="asdf" class="control-label">Email notifications</label>
                            <div class="controls">
                                <?php echo $this->Form->hidden('theme');?>
                                
                                <ul style="list-style-type:none;" class="theme-colors chosen-color">
                                    <li>
                                        <span class='red'></span>
                                        <span class='orange'></span>
                                        <span class='green'></span>
                                        <span class="brown"></span>
                                        <span class="blue"></span>
                                        <span class='lime'></span>
                                        <span class="teal"></span>
                                        <span class="purple"></span>
                                        <span class="pink"></span>
                                        <span class="magenta"></span>
                                        <span class="grey"></span>
                                        <span class="darkblue"></span>
                                        <span class="lightred"></span>
                                        <span class="lightgrey"></span>
                                        <span class="satblue"></span>
                                        <span class="satgreen"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <?php echo $this->AppForm->btn('Salvar Alterações', array('template' => 'Users/form-input-btn'));?>
                </div>
            </div>
                    <?php echo $this->AppForm->end();?>
        </div>
</div>

