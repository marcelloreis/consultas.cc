<?php 
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
            <div class="tab-content padding tab-content-inline tab-content-bottom">
                <div class="tab-pane active" id="profile">

                    <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal', 'type' => 'file'));?>
                    <?php echo $this->Form->input('id');?>
                        <div class="row-fluid">
                            <div class="span2">
                                <?php echo $this->AppForm->input('picture', array('type' => 'file', 'template' => 'Users/form-input-picture'));?>
                            </div>
                            <div class="span10">
                                <?php
                                echo $this->AppForm->input('group_id', array('type' => 'select', 'class' => 'chosen-select', 'template' => 'Users/form-input-fk'));
                                echo $this->AppForm->input('name', array('template' => 'Users/form-input'));
                                echo $this->AppForm->input('given_name', array('template' => 'Users/form-input'));
                                echo $this->AppForm->input('expire', array('template' => 'Users/form-input'));
                                echo $this->AppForm->input('email', array('template' => 'Users/form-input'));
                                echo $this->AppForm->input('password', array('template' => 'Users/form-input'));
                                echo $this->AppForm->input('unlimited', array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue', 'template' => 'Users/form-input'));
                                echo $this->AppForm->input('status', array('label' => 'Ativo', 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue', 'template' => 'Users/form-input'));

                                echo $this->AppForm->btn('Salvar Alterações', array('template' => 'Users/form-input-btn'));
                                ?>
                            </div>
                        </div>
                        <?php echo $this->AppForm->end();?>

                </div>

                <div class="tab-pane" id="notifications">
                    <form action="#" class="form-horizontal">
                        <div class="control-group">
                            <label for="asdf" class="control-label">Email notifications</label>
                            <div class="controls">
                                <label class="checkbox"><input type="checkbox" name="asdf"> Send me security emails</label>
                                <label class="checkbox"><input type="checkbox" name="asdf"> Send system emails</label>
                                <label class="checkbox"><input type="checkbox" name="asdf"> Lorem ipsum dolor</label>
                                <label class="checkbox"><input type="checkbox" name="asdf"> Minim veli</label>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="asdf" class="control-label">Email for notifications</label>
                            <div class="controls">
                                <select name="email" id="email">
                                    <option value="1">asdf@blasdas.com</option>
                                    <option value="2">johnDoe@asdasf.de</option>
                                    <option value="3">janeDoe@janejanejane.net</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" class='btn btn-primary' value="Save">
                            <input type="reset" class='btn' value="Discard changes">
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>

